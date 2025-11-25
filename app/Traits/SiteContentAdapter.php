<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use App\Models\Frontend\Team;
use App\Models\Frontend\Product;
use App\Models\Frontend\Service;
use App\Models\Frontend\Faq;
use App\Models\Frontend\Choosing;
use App\Models\Frontend\Career;
use App\Models\Frontend\Achievement;
use App\Models\Frontend\Integration;
use App\Models\Frontend\Plan;

trait SiteContentAdapter
{
    public function getCommonContent(string $locale): array
    {
        // Per-dataset caching allows selective invalidation and parallel warmup.
        $ttl = 900; // 15 minutes

        $load = function (string $key, callable $callback) use ($locale, $ttl) {
            return Cache::remember("site_content_{$key}_{$locale}", $ttl, function () use ($callback) {
                $data = $callback();
                return $data instanceof \Illuminate\Support\Collection ? $data : collect($data);
            });
        };

        $translationScope = function ($relation) use ($locale) {
            $relation->where('locale', $locale)->orWhere('locale', 'en');
        };

        $teams = $load('teams', fn() => $this->safeQuery(fn() => Team::select('id', 'photo', 'is_active')
            ->with(['translations' => $translationScope])
            ->orderBy('sort')->limit(50)->get()));

        $services = $load('services', fn() => $this->safeQuery(fn() => Service::select('id', 'slug', 'icon', 'image', 'sort', 'is_slider', 'slider_image')
            ->with(['translations' => $translationScope])
            ->orderBy('sort')->limit(50)->get()));


        $products = $load('products', fn() => $this->safeQuery(fn() => Product::select('id', 'slug', 'image', 'icon', 'sort', 'is_slider', 'slider_image')
            ->with([
                'translations' => $translationScope,
                'features' => function ($q) {
                    $q->select('id', 'product_id', 'icon', 'is_highlighted', 'sort');
                },
            ])
            ->orderBy('sort')->limit(50)->get()));

        $faqs = $load('faqs', fn() => $this->safeQuery(fn() => Faq::select('id', 'slug', 'sort', 'parent_id')
            ->with(['translations' => $translationScope])
            ->whereNull('parent_id')->orderBy('sort')->limit(100)->get()));

        $choosing = $load('choosing', fn() => $this->safeQuery(fn() => Choosing::select('id', 'image', 'sort', 'is_active')
            ->with(['translations' => $translationScope])
            ->orderBy('sort')->limit(50)->get()));

        $careers = $load('careers', fn() => $this->safeQuery(fn() => Career::select('id', 'slug', 'location', 'deadline', 'type', 'level', 'sort', 'is_active', 'priority', 'created_at')
            ->with(['translations' => $translationScope])
            ->orderBy('sort')->limit(50)->get()));

        $achievements = $load('achievements', fn() => $this->safeQuery(fn() => Achievement::select('id', 'sort')
            ->with(['translations' => $translationScope])
            ->orderBy('sort')->limit(50)->get()));

        $integrations = $load('integrations', fn() => $this->safeQuery(fn() => Integration::select('id', 'slug')
            ->with(['translations' => $translationScope])
            ->orderByDesc('id')->limit(50)->get()));

        $pricing = $load('pricing', fn() => $this->safeQuery(fn() => Plan::select('id', 'sort')
            ->with([
                'translations' => $translationScope,
                'features' => function ($q) {
                    $q->select('id', 'plan_id');
                },
            ])
            ->orderBy('sort')->limit(50)->get()));

        $sliders = $services->where('is_slider', true)->merge($products->where('is_slider', true))->sortBy('sort');
        return [
            'talk' => true,
            'teams' => $teams,
            'services' => $services,
            'products' => $products,
            'categories' => $faqs,
            'choosing' => $choosing,
            'careers' => $careers,
            'achievements' => $achievements,
            'integrations' => $integrations,
            'pricing' => $pricing,
            'sliders' => $services->where('is_slider', true)->merge($products->where('is_slider', true))->sortBy('sort'),
        ];
    }

    private function safeQuery(callable $cb)
    {
        try {
            return $cb();
        } catch (\Throwable $e) {
            return collect();
        }
    }
}
