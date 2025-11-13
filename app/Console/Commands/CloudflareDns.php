<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;

class CloudflareDns extends Command
{
    // Usage: php artisan cloudflare:dns --debug
    protected $signature = 'cloudflare:dns {--debug}';

    protected $description = 'Parse an HTML DNS table and create/update Cloudflare DNS records (normalize www)';

    public function handle(): int
    {
        $htmlPath = storage_path('app/public/dns-records.html');
        $debug    = (bool) $this->option('debug');

        $apiToken    = env('CF_API_TOKEN', '');
        $rootDomain  = env('CF_ROOT_DOMAIN', '');

        if (! $apiToken) {
            $this->error('CF_API_TOKEN is not set in .env');
            return 1;
        }

        if (! $rootDomain) {
            $this->error('CF_ROOT_DOMAIN is not set in .env');
            return 1;
        }

        if (! is_file($htmlPath)) {
            $this->error("HTML file not found: {$htmlPath}");
            return 1;
        }

        $html = file_get_contents($htmlPath);
        if ($html === false) {
            $this->error("Unable to read HTML file: {$htmlPath}");
            return 1;
        }

        $client = Http::withToken($apiToken)->baseUrl('https://api.cloudflare.com/client/v4');

        // 🔹 Get zone ID from ROOT DOMAIN using: /zones?name=${ROOT_DOMAIN}
        $zoneResp = $client->get('zones', [
            'name' => $rootDomain,
        ]);

        if (! $zoneResp->successful()) {
            $this->error('Failed to fetch zone from Cloudflare: ' . $zoneResp->body());
            return 1;
        }

        $zoneId = $zoneResp->json('result.0.id');

        if (! $zoneId) {
            $this->error("No Cloudflare zone found for domain: {$rootDomain}");
            return 1;
        }

        if ($debug) {
            $this->info('DEBUG mode ON (dry-run, no DNS changes).');
            $this->info("Using zone {$rootDomain} (id={$zoneId})");
        }

        $crawler = new Crawler($html);
        $rows    = $crawler->filter('table.sg-table tbody tr');

        if ($rows->count() === 0) {
            $this->warn('No rows found in HTML table.');
            return 0;
        }

        $rows->each(function (Crawler $row) use ($client, $debug, $zoneId) {
            // Extract Type, Name, Value (IP/target)
            $type = trim($row->filter('td[data-label="Type"] p')->text(''));
            $name = trim($row->filter('td[data-label="Name"] p')->text(''));

            // Remove "www." at the beginning
            $name = preg_replace('/^www\./i', '', $name);

            // Value: last <span> in the Value <td> (e.g. "points to 146.190.4.204")
            $valueNode = $row->filter('td[data-label="Value"] span')->last();
            $value     = trim($valueNode->text(''));

            if ($type === '' || $name === '' || $value === '') {
                $this->warn('Skipping row with missing type/name/value.');
                return;
            }

            // Clean trailing dot (e.g. "psspos.wintech.com.kh.")
            $cleanName = rtrim($name, '.');

            $this->line("Processing: type={$type}, name={$cleanName}, value={$value}");

            // Only allow A and CNAME (you can adjust this)
            if (! in_array($type, ['A', 'CNAME'], true)) {
                $this->warn("  Unsupported type {$type}, skipping.");
                return;
            }

            // Check if record already exists
            $resp = $client->get("zones/{$zoneId}/dns_records", [
                'type' => $type,
                'name' => $cleanName,
            ]);

            if (! $resp->successful()) {
                $this->error('  Failed to check existing DNS record: ' . $resp->body());
                return;
            }

            $existing = $resp->json('result.0');

            // Default settings (you can tweak)
            $ttl     = 3600; // 1 = auto, else seconds
            $proxied = true; // usually only for A/AAAA

            // If record exists -> UPDATE
            if ($existing) {
                $recordId = $existing['id'] ?? null;

                if (! $recordId) {
                    $this->error("  Existing record found for {$cleanName} ({$type}) but no ID, skipping update.");
                    return;
                }

                if ($debug) {
                    $this->info("  [DRY RUN] Would update {$type} {$cleanName} → {$value} (proxied=" . ($proxied && $type === 'A' ? 'true' : 'false') . ", ttl={$ttl})");
                    return;
                }

                $updateResp = $client->put("zones/{$zoneId}/dns_records/{$recordId}", [
                    'type'    => $type,
                    'name'    => $cleanName,
                    'content' => $value,
                    'ttl'     => $ttl,
                    'proxied' => $type === 'A' ? $proxied : false,
                ]);

                $data = $updateResp->json();

                if (($data['success'] ?? false) === true) {
                    $this->info("  Updated: {$cleanName} ({$type}) → {$value}");
                } else {
                    $this->error('  Failed to update record: ' . json_encode($data['errors'] ?? $data));
                }

                return;
            }

            // If record does NOT exist -> CREATE
            if ($debug) {
                $this->info("  [DRY RUN] Would create {$type} {$cleanName} → {$value} (proxied=" . ($proxied ? 'true' : 'false') . ", ttl={$ttl})");
                return;
            }

            $createResp = $client->post("zones/{$zoneId}/dns_records", [
                'type'    => $type,
                'name'    => $cleanName,
                'content' => $value,
                'ttl'     => $ttl,
                'proxied' => $type === 'A' ? $proxied : false,
            ]);

            $data = $createResp->json();

            if (($data['success'] ?? false) === true) {
                $this->info("  Created: {$cleanName} ({$type}) → {$value}");
            } else {
                $this->error('  Failed to create record: ' . json_encode($data['errors'] ?? $data));
            }
        });

        $this->info('Done.');
        return 0;
    }
}
