<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Frontend\Service;
use App\Models\Frontend\ServiceTranslation;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('seeders/data/frontend/services.json');

        if (! File::exists($path)) {
            $this->command?->warn("ServiceSeeder: services.json not found at $path");
            return;
        }

        $data = json_decode(File::get($path), true);

        if (! is_array($data)) {
            $this->command?->error('ServiceSeeder: Invalid JSON structure.');
            return;
        }

        DB::transaction(function () use ($data) {
            foreach ($data as $index => $service) {
                // Basic structure validation
                if (! isset($service['slug'])) {
                    $this->command?->warn("ServiceSeeder: Skipping entry at index {$index} (missing slug)");
                    continue;
                }

                $serviceModel = Service::query()->firstOrNew(['slug' => $service['slug']]);
                $serviceModel->fill([
                    'icon' => $service['icon'] ?? null,
                    'image' => $service['image'] ?? null,
                    'sort' => $service['id'] ?? ($index + 1), // fallback to index for ordering
                ]);
                $serviceModel->save();

                // Translations: we treat original record (single language entries) as English
                // JSON only provides a single set of fields per service (no explicit locale list)
                $locale = 'en';
                $translationPayload = [
                    'service_id' => $serviceModel->id,
                    'locale' => $locale,
                    'name' => $service['name'] ?? ucfirst(str_replace('-', ' ', $service['slug'])),
                    'description' => $service['description'] ?? null,
                    'content' => $service['content'] ?? null,
                ];

                $translation = ServiceTranslation::query()->firstOrNew([
                    'service_id' => $serviceModel->id,
                    'locale' => $locale,
                ]);
                $translation->fill($translationPayload);
                $translation->save();
            }
        });
    }
}
