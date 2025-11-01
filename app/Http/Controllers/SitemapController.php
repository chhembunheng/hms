<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class SitemapController extends Controller
{
    public function index()
    {
        $locales = config('app.available_locales', ['en']);
        $defaultLocale = $locales[0];
        $services = $this->loadJson('services.json');
        $products = $this->loadJson('products.json');
        $articles = $this->loadJson('articles.json');
        $integrations = $this->loadJson('integrations.json');
        $careers = $this->loadJson('careers.json');

        $now = Carbon::now()->toAtomString();

        $urls = collect()
            ->merge($this->staticPageEntries($locales, $defaultLocale, $now))
            ->merge($this->collectionEntries($locales, $defaultLocale, $now))
            ->merge($this->detailEntries($locales, $defaultLocale, $now, $services, fn($item) => "services/{$item->slug}", changefreq: 'monthly', priority: '0.8'))
            ->merge($this->detailEntries($locales, $defaultLocale, $now, $products, fn($item) => "products/{$item->slug}", changefreq: 'monthly', priority: '0.8'))
            ->merge($this->detailEntries($locales, $defaultLocale, $now, $articles, fn($item) => "blogs/{$item->slug}", changefreq: 'weekly', priority: '0.7'))
            ->merge($this->detailEntries($locales, $defaultLocale, $now, $integrations, fn($item) => "integrations/{$item->slug}", changefreq: 'monthly', priority: '0.6'))
            ->merge($this->detailEntries($locales, $defaultLocale, $now, $careers, fn($item) => "careers/{$item->slug}", changefreq: 'weekly', priority: '0.6'));
        $xml = view('sitemap.xml', ['urls' => $urls])->render();
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    protected function loadJson(string $filename): Collection
    {
        $enPath = public_path('site/data/km/' . $filename);
        $kmPath = public_path('site/data/en/' . $filename);

        if (!file_exists($kmPath) || !file_exists($enPath)) {
            return collect();
        }

        $kmData = json_decode(file_get_contents($kmPath));
        $enData = json_decode(file_get_contents($enPath));
        $data = array_merge($kmData ?: [], $enData ?: []);
        return collect($data);
    }

    protected function buildAlternates(array $locales, string $path): array
    {
        return collect($locales)->map(function ($locale) use ($path) {
            $cleanPath = trim($path, '/');
            $full = $cleanPath === '' ? "/{$locale}" : "/{$locale}/{$cleanPath}";
            return ['hreflang' => $locale, 'url' => url($full)];
        })->values()->all();
    }

    protected function canonicalUrl(string $defaultLocale, string $path): string
    {
        $cleanPath = trim($path, '/');
        $full = $cleanPath === '' ? "/{$defaultLocale}" : "/{$defaultLocale}/{$cleanPath}";
        return url($full);
    }

    protected function staticPageEntries(array $locales, string $defaultLocale, string $lastmod): Collection
    {
        $staticPaths = [
            '' => ['changefreq' => 'daily', 'priority' => '1.0'],
            'about' => ['changefreq' => 'monthly', 'priority' => '0.8'],
            'contact' => ['changefreq' => 'monthly', 'priority' => '0.8'],
            'teams' => ['changefreq' => 'monthly', 'priority' => '0.6'],
            'faq' => ['changefreq' => 'weekly', 'priority' => '0.6'],
            'pricing' => ['changefreq' => 'weekly', 'priority' => '0.7'],
            'privacy-policy' => ['changefreq' => 'yearly', 'priority' => '0.3'],
            'cookie-policy' => ['changefreq' => 'yearly', 'priority' => '0.3'],
            'terms-condition' => ['changefreq' => 'yearly', 'priority' => '0.3']
        ];

        return collect($staticPaths)->map(function ($meta, $path) use ($locales, $defaultLocale, $lastmod) {
            $alternates = $this->buildAlternates($locales, $path);
            return [
                'loc' => $this->canonicalUrl($defaultLocale, $path),
                'lastmod' => $lastmod,
                'changefreq' => $meta['changefreq'],
                'priority' => $meta['priority'],
                'alternates' => array_merge($alternates, [['hreflang' => 'x-default', 'url' => $this->canonicalUrl($defaultLocale, $path)]])
            ];
        })->values();
    }

    protected function collectionEntries(array $locales, string $defaultLocale, string $lastmod): Collection
    {
        $collections = [
            'services' => ['weekly', '0.8'],
            'products' => ['weekly', '0.8'],
            'blogs' => ['daily', '0.9'],
            'integrations' => ['monthly', '0.6'],
            'careers' => ['weekly', '0.6']
        ];

        return collect($collections)->map(function ($meta, $path) use ($locales, $defaultLocale, $lastmod) {
            [$changefreq, $priority] = $meta;
            $alternates = $this->buildAlternates($locales, $path);
            return [
                'loc' => $this->canonicalUrl($defaultLocale, $path),
                'lastmod' => $lastmod,
                'changefreq' => $changefreq,
                'priority' => $priority,
                'alternates' => array_merge($alternates, [['hreflang' => 'x-default', 'url' => $this->canonicalUrl($defaultLocale, $path)]])
            ];
        })->values();
    }

    protected function detailEntries(
        array $locales,
        string $defaultLocale,
        string $lastmod,
        Collection $items,
        \Closure $pathCallback,
        string $changefreq = 'weekly',
        string $priority = '0.8'
    ): Collection {
        return $items->map(function ($item) use ($locales, $defaultLocale, $lastmod, $pathCallback, $changefreq, $priority) {
            $relativePath = $pathCallback($item);
            $alternates = $this->buildAlternates($locales, $relativePath);
            return [
                'loc' => $this->canonicalUrl($defaultLocale, $relativePath),
                'lastmod' => $lastmod,
                'changefreq' => $changefreq,
                'priority' => $priority,
                'alternates' => array_merge($alternates, [['hreflang' => 'x-default', 'url' => $this->canonicalUrl($defaultLocale, $relativePath)]])
            ];
        })->values();
    }
}
