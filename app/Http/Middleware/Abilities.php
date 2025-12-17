<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
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
        $user = $request->user();

        $this->rebuildAccessSession($user);

        if ($user && $user->roles->contains(function ($role) {
            return isset($role->administrator) && $role->administrator == 1;
        })) {
            Session::put('administrator', true);
        } else {
            Session::forget('administrator');
        }

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

        if ($this->administrator) {
            View::share([
                'menus' => $this->menus,
                'navbars' => collect($this->navbars),
                'actions' => collect($this->actions)->filter(function ($item) {
                    return isset($this->access[$item->action_route]) || $item->action  !== 'index';
                }),
            ]);
        } else {
            View::share([
                'menus' => $this->filterByAccess($this->menus, $this->permissions, $this->access),

                'navbars' => $this->filterByAccess(
                    collect($this->navbars),
                    $this->permissions,
                    $this->access
                ),

                'actions' => $this->filterByAccess(
                    collect($this->actions),
                    $this->permissions,
                    $this->access
                )->filter(fn($item) => $item->action !== 'index'),
            ]);
        }

        if ($this->administrator || isset($this->access[$this->route])) {
            return $next($request);
        }


        // Block all other access for non-admins
        if ($request->ajax()) {
            return response()->json([
                'status' => 'error',
                'message' => __('messages.access_denied'),
            ], 403);
        }

        return abort(403, __('messages.access_denied'));
    }

    // Helper method to filter menus, navbars, and actions by permissions and access
    private function filterByAccess($items, $permissions, $access)
    {
        return collect($items)->filter(function ($item) use ($permissions, $access) {

            if ($item instanceof \App\Models\Settings\Permission) {
                return isset($access[$item->action_route]);
            }

            if (is_object($item)) {

                // Filter children first
                if (isset($item->children)) {
                    $item->children = $this->filterByAccess($item->children, $permissions, $access);
                }

                // Check menu permissions
                $hasPermission = false;

                if (isset($item->permissions)) {
                    foreach ($item->permissions as $perm) {
                        if (isset($access[$perm->action_route])) {
                            $hasPermission = true;
                            break;
                        }
                    }
                }

                // Keep menu if it has permission OR visible children
                return $hasPermission || (isset($item->children) && $item->children->isNotEmpty());
            }

            return false;
        })->values();
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
                if (isset($permission['action_route']) && !Route::has($permission['action_route'])) continue;
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

    private function rebuildAccessSession($user)
    {
        if (!$user) {
            Session::forget('access');
            return;
        }

        $permissions = $user->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('action_route')
            ->unique()
            ->mapWithKeys(fn ($route) => [$route => true])
            ->toArray();

        Session::put('access', $permissions);
    }

}
