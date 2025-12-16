<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Models\Settings\Menu;
use Illuminate\Support\Facades\DB;
use App\Models\Settings\Permission;
use App\Http\Controllers\Controller;
use App\Models\Settings\MenuTranslation;
use Illuminate\Support\Facades\Validator;
use App\DataTables\Settings\MenuDataTable;
use App\Models\Settings\PermissionTranslation;

class MenuController extends Controller
{

    public function index(MenuDataTable $dataTable)
    {
        return $dataTable->render('settings.menus.index');
    }

    private function menuList($exclude = null)
    {
        $locale = app()->getLocale();

        return Menu::select([
            'menus.id',
            DB::raw("COALESCE(
                    (SELECT name FROM menu_translations
                     WHERE menu_id = menus.id AND locale = '$locale' LIMIT 1),
                    (SELECT name FROM menu_translations
                     WHERE menu_id = menus.id AND locale = 'en' LIMIT 1)
                ) AS name")
        ])
            ->when($exclude, fn($q) => $q->where('menus.id', '!=', $exclude))
            ->orderBy('menus.sort')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function add(Request $request)
    {
        $locales = collect(config('init.languages'));
        $form = new Menu();

        if ($request->isMethod('post')) {

            DB::beginTransaction();
            try {

                Validator::make($request->all(), [
                    'name.en' => 'required|string|max:255',
                ])->validate();

                $menu = Menu::create([
                    'icon'       => $request->icon,
                    'route'      => $request->route,
                    'sort'       => $request->sort ?? 0,
                    'parent_id'  => $request->parent_id,
                    'created_by' => auth()->id(),
                ]);

                /* Prepare payload */
                $permissions = [];
                $translations = [];
                $i = 0;

                foreach ($request->permissions_new ?? [] as $row) {
                    $action_route = trim($row['action_route'] ?? '');
                    if ($action_route === '') continue;

                    $parts = explode('.', $action_route);
                    $action = end($parts);
                    $slug   = slug($action_route);
                    $permission_id = Permission::updateOrCreate([
                        'menu_id' => $menu->id,
                        'slug' => $slug,
                    ],[
                        'menu_id' => $menu->id,
                        'action' => $action,
                        'action_route' => $action_route,
                        'slug' => $slug,
                        'icon' => $row['icon'] ?? null,
                        'sort' => $i++,
                        'created_by' => auth()->id(),
                    ])->id;

                    foreach ($row['translations'] ?? [] as $lc => $name) {
                        if (trim($name) === '') continue;
                        $translations[] = [
                            'permission_id' => $permission_id,
                            'locale' => $lc,
                            'name' => trim($name),
                            'created_by' => auth()->id(),
                        ];
                    }
                }

                /* Insert/Upsert permissions */
                Permission::upsert(
                    $permissions,
                    ['id'],
                    ['menu_id', 'action', 'action_route', 'slug', 'icon', 'sort']
                );
                PermissionTranslation::upsert(
                    $translations,
                    ['permission_id', 'locale'],
                    ['name']
                );

                /* Menu translations */
                foreach ($locales->keys() as $lc) {
                    MenuTranslation::create([
                        'menu_id' => $menu->id,
                        'locale' => $lc,
                        'name' => $request->name[$lc] ?? '',
                        'description' => $request->description[$lc] ?? null,
                        'created_by' => auth()->id(),
                    ]);
                }

                DB::commit();
                return success(message: 'Menu created successfully.');
            } catch (\Throwable $e) {
                DB::rollBack();
                return errors($e->getMessage());
            }
        }

        $menus = $this->menuList();

        return view('settings.menus.form', compact('form', 'menus', 'locales'));
    }

    public function edit(Request $request, $id)
    {
        $locales = collect(config('init.languages'));
        $form = Menu::with('translations', 'permissions.translations')->findOrFail($id);

        if ($request->isMethod('post')) {

            DB::beginTransaction();
            try {

                Validator::make($request->all(), [
                    'name.en' => 'required',
                ])->validate();

                $form->update([
                    'icon' => $request->icon,
                    'route' => $request->route,
                    'sort' => $request->sort ?? 0,
                    'parent_id'  => $request->parent_id,
                    'updated_by' => auth()->id(),
                ]);

                $permissions = [];
                $translations = [];
                $i = 0;

                /* Existing */
                foreach ($request->permissions_existing ?? [] as $p) {
                    $action_route = trim($p['action_route'] ?? '');
                    if ($action_route === '') continue;

                    $parts = explode('.', $action_route);
                    $action = end($parts);
                    $slug   = $p['slug'] ?: slug($action_route);
                    $permission_id = (int) $p['id'];

                    $permissions[] = [
                        'id' => $permission_id,
                        'menu_id' => $form->id,
                        'action' => $action,
                        'action_route' => $action_route,
                        'slug' => $slug,
                        'icon' => $p['icon'] ?? null,
                        'sort' => $i++,
                    ];

                    foreach ($p['translations'] ?? [] as $lc => $name) {
                        $translations[] = [
                            'permission_id' => $permission_id,
                            'locale' => $lc,
                            'name' => trim($name),
                        ];
                    }
                }

                /* New */
                foreach ($request->permissions_new ?? [] as $p) {
                    $action_route = trim($p['action_route'] ?? '');
                    if ($action_route === '') continue;
                    $parts = explode('.', $action_route);
                    $action = end($parts);
                    $slug = slug($action_route);
                    $permissionNew = [
                        'menu_id' => $form->id,
                        'action' => $action,
                        'action_route' => $action_route,
                        'slug' => $slug,
                        'icon' => $p['icon'] ?? null,
                        'sort' => $i++,
                    ];
                    $permission_id = Permission::updateOrCreate(
                        [
                            'menu_id' => $form->id,
                            'slug' => $slug,
                        ],
                        $permissionNew
                    )->id;
                    foreach ($p['translations'] ?? [] as $lc => $name) {
                        if (trim($name) === '') continue;
                        $translations[] = [
                            'permission_id' => $permission_id,
                            'locale' => $lc,
                            'name' => trim($name),
                        ];
                    }
                }

                /* Upsert */
                Permission::upsert(
                    $permissions,
                    ['action_route', 'menu_id'],
                    ['menu_id', 'action', 'action_route', 'slug', 'icon', 'sort']
                );
                PermissionTranslation::upsert(
                    $translations,
                    ['permission_id', 'locale'],
                    ['name']
                );

                /* Menu translations */
                foreach ($locales->keys() as $lc) {
                    MenuTranslation::updateOrCreate(
                        ['menu_id' => $form->id, 'locale' => $lc],
                        [
                            'name' => $request->name[$lc] ?? '',
                            'description' => $request->description[$lc] ?? null,
                            'updated_by'  => auth()->id(),
                        ]
                    );
                }

                DB::commit();
                return success(message: 'Menu updated successfully.');
            } catch (\Throwable $e) {
                DB::rollBack();
                return errors($e->getMessage());
            }
        }

        $translations = [];
        foreach ($form->translations as $t) {
            $translations[$t->locale] = [
                'name' => $t->name,
                'description' => $t->description,
            ];
        }

        $menus = $this->menuList($form->id);

        return view('settings.menus.form', compact(
            'form',
            'menus',
            'locales',
            'translations',
            'id'
        ));
    }

    public function destroy($id)
    {
        try {
            $menu = Menu::findOrFail($id);

            if (Menu::where('parent_id', $id)->exists()) {
                return errors("Cannot delete menu with sub-menus. Delete child menus first.");
            }

            $menu->delete();

            return success(message: "Menu deleted successfully.");
        } catch (\Exception $e) {
            return errors("Failed to delete menu: " . $e->getMessage());
        }
    }


    public function select2(Request $request)
    {
        $search = $request->input('q', '');
        $page = (int) $request->input('page', 1);
        $perPage = 10;

        $query = Menu::query();

        if ($search !== '') {
            $locale = app()->getLocale();
            $query->whereHas('translations', function ($q) use ($search, $locale) {
                $q->where('locale', $locale)
                  ->where('name', 'like', '%' . $search . '%');
            });
        }

        $total = $query->count();
        $menus = $query->with('translations')
                       ->skip(($page - 1) * $perPage)
                       ->take($perPage)
                       ->get();

        $results = [];
        foreach ($menus as $menu) {
            $name = $menu->translations->where('locale', app()->getLocale())->first()?->name
                    ?? $menu->translations->where('locale', 'en')->first()?->name
                    ?? 'N/A';
            $results[] = [
                'id' => $menu->id,
                'text' => $name,
            ];
        }

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => ($page * $perPage) < $total,
            ],
        ]);
    }
}
