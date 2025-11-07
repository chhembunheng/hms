<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Settings\Menu;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Abilities
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    private $access;
    private $administrator;
    private $permissions;
    private $route;
    private $navbars = [];
    private $actions = [];
    private $menus = [];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale', config('app.locale'));
        App::setLocale($locale);
        Session::put('administrator', true);

        // Cache menu data for 24 hours
        $cacheKey = 'menus_raw_data';
        $menusData = Cache::remember($cacheKey, 60 * 60 * 24, function () {
            return Menu::with(['children', 'permissions.translations', 'translations'])
                ->whereNull('parent_id')
                ->orderBy('sort')
                ->get();
        });

        $this->route = $request->route()->getName();
        if (Session::has('administrator')) {
            $this->administrator = Session::get('administrator');
        }
        if (Session::has('access')) {
            $this->access = Session::get('access');
        }

        // Cache processed menus with locale and route context
        $processedCacheKey = "processed_menus_{$locale}_{$this->route}";
        $cachedResult = Cache::remember($processedCacheKey, 60 * 60, function () use ($menusData) {
            $processedMenus = $this->recursives($menusData);
            return [
                'menus' => $processedMenus,
                'navbars' => $this->navbars,
                'actions' => $this->actions,
                'permissions' => $this->permissions,
            ];
        });

        $this->menus = $cachedResult['menus'] ?? [];
        $this->navbars = $cachedResult['navbars'] ?? [];
        $this->actions = $cachedResult['actions'] ?? [];
        $this->permissions = $cachedResult['permissions'] ?? [];

        View::share([
            'menus' => $this->menus,
            'navbars' => $this->navbars,
            'actions' => $this->actions,
        ]);

        if ($this->administrator) {
            return $next($request);
        }

        if (!isset($this->permissions[$this->route])) {
            return $next($request);
        }
        if (isset($this->permissions[$this->route]) && isset($this->access[$this->route])) {
            return $next($request);
        }
        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => __('messages.access_denied'),
            ], 403);
        }

        return abort(403, __('messages.access_denied'));
    }
    private function recursives($menus, $parent = null, $currentRoute = null)
    {
        $dataMenus = collect();
        $locale = App::getLocale();
        $currentRoute = $currentRoute ?? request()->route()->getName();
        foreach ($menus as $menu) {
            // Get name from translations
            $menuName = null;
            if (!is_array($menu)) {
                // It's an object (Model)
                $translation = $menu->translations
                    ->where('locale', $locale)
                    ->first();
                
                if (!$translation) {
                    $translation = $menu->translations
                        ->where('locale', 'en')
                        ->first();
                }
                
                $menuName = $translation?->name ?? 'N/A';
            } else {
                // It's an array
                $menuName = $menu['name_' . $locale] ?? $menu['name_en'] ?? $menu['name'] ?? 'N/A';
            }
            
            $id = $menu->id ?? $menu['id'];
            $parentId = $menu->parent_id ?? $menu['parent_id'] ?? null;
            if ($parentId != $parent) continue;
            $children = $this->recursives($menu->children ?? $menu['children'] ?? [], $id, $currentRoute);
            $route = $menu->route ?? $menu['route'];
            $isActive = ($route === $currentRoute) || collect($menu->permissions ?? $menu['permissions'] ?? [])->pluck('action_route')->contains($currentRoute);
            $isActive = $isActive || ($children->isNotEmpty() && $children->pluck('active')->contains(true));
            $menuObject = (object) [
                'id' => $id,
                'name' => $menuName,
                'route' => $route,
                'icon' => $menu->icon ?? $menu['icon'] ?? null,
                'children' => $children,
                'permissions' => collect($menu->permissions ?? $menu['permissions'] ?? [])->sortBy('sort')->values(),
                'active' => $isActive
            ];
            foreach ($menuObject->permissions as $permission) {
                if(isset($permission['action_route']) && !Route::has($permission['action_route'])) continue;
                // Get permission name from translation
                $permissionName = $permission['name'] ?? 'N/A';
                if (isset($permission->translations) && $permission->translations->count() > 0) {
                    $permTranslation = $permission->translations
                        ->where('locale', $locale)
                        ->first();
                    
                    if (!$permTranslation) {
                        $permTranslation = $permission->translations
                            ->where('locale', 'en')
                            ->first();
                    }
                    
                    $permissionName = $permTranslation?->name ?? $permission['name'] ?? 'N/A';
                    $permission['name'] = $permissionName;
                }
                
                $this->permissions[$permission['action_route']] = true;
                if ($permission && !$this->administrator && !isset($this->access[$permission['action_route']])) continue;
                if ($permission && $isActive) {
                    if ($permission['target'] === 'navbar') $this->navbars[] = $permission;
                    elseif ($permission['target'] !== 'index') $this->actions[] = $permission;
                }
            }
            $dataMenus->put($id, $menuObject);
        }
        return $dataMenus;
    }
}
