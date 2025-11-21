<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Frontend\Navigation;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class Navigations
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    private $navigations = [];
    private $route;

    public function handle(Request $request, Closure $next): Response
    {
        // Cache navigation data for 24 hours
        $this->route = $request->route()->getName();
        $locale = $request->route()->parameter('locale');
        $cacheKey = 'navigations';
        $navigationData = Cache::remember($cacheKey, 60 * 60 * 24, function () {
            return Navigation::with(['children', 'translations'])
                ->whereNull('parent_id')
                ->orderBy('sort')
                ->get();
        });

        $processedCacheKey = "processed_navigations_{$locale}_{$this->route}";
        $this->navigations = Cache::remember($processedCacheKey, 60 * 60, function () use ($navigationData) {
            $processedNavigations = $this->recursives($navigationData);
            return $processedNavigations;
        });
        View::share(['navigations' => $this->navigations]);
        return $next($request);
    }
    private function recursives($navigations, $parent = null, $currentRoute = null)
    {
        $data = collect();
        $locale = App::getLocale();
        $currentRoute = $currentRoute ?? request()->route()->getName();
        foreach ($navigations as $navigation) {
            $navigationName = null;
            if (!is_array($navigation)) {
                $translation = $navigation->translations
                    ->where('locale', $locale)
                    ->first();

                if (!$translation) {
                    $translation = $navigation->translations
                        ->where('locale', 'en')
                        ->first();
                }
                $navigationName = $translation?->name ?? 'N/A';
            } else {
                $navigationName = $navigation['name_' . $locale] ?? $navigation['name_en'] ?? $navigation['name'] ?? 'N/A';
            }
            if (!is_null($navigation->url) && !Route::has($navigation->url) && is_null($navigation->linked_id)) continue;
            $id = $navigation->id ?? $navigation['id'];
            $parentId = $navigation->parent_id ?? $navigation['parent_id'] ?? null;
            if ($parentId != $parent) continue;
            $children = $this->recursives($navigation->children ?? $navigation['children'] ?? [], $id, $currentRoute);
            $route = $navigation->url ?? $navigation['url'];
            $isActive = ($route === $currentRoute);
            $isActive = $isActive || ($children->isNotEmpty() && $children->pluck('active')->contains(true));
            $navigationObject = (object) [
                'id' => $id,
                'name' => $navigationName,
                'parent_id' => $parentId ?? null,
                'route' => $route,
                'linked_type' => $navigation->linked_type ?? $navigation['linked_type'] ?? null,
                'linked_id' => $navigation->linked_id ?? $navigation['linked_id'] ?? null,
                'icon' => $navigation->icon ?? $navigation['icon'] ?? null,
                'children' => $children,
                'active' => $isActive
            ];
            $data->put($id, $navigationObject);
        }
        return $data;
    }
}
