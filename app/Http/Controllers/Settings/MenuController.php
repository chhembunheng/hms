<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Settings\MenuDataTable;
use App\Models\Settings\Menu;
use App\Models\Settings\Permission;
use App\Models\Settings\MenuTranslation;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    public function __construct() {}

    /**
     * Display a listing of the menus.
     */
    public function index(MenuDataTable $dataTable)
    {
        return $dataTable->render('settings.menus.index');
    }

    /**
     * Show the form for creating a new menu.
     */
    public function add(Request $request)
    {
        $form = new Menu();
        $locales = collect(config('init.languages'));
        
        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'name.en' => 'required|string|max:255',
                    'name.km' => 'nullable|string|max:255',
                    'name.zh' => 'nullable|string|max:255',
                    'description.en' => 'nullable|string',
                    'description.km' => 'nullable|string',
                    'description.zh' => 'nullable|string',
                    'icon' => 'nullable|string|max:255',
                    'route' => 'nullable|string|max:255',
                    'sort' => 'nullable|integer|min:0',
                    'parent_id' => 'nullable|integer|exists:menus,id',
                    'permissions_new' => 'nullable|array',
                    'permissions_new.*.action' => 'nullable|string|max:255',
                    'permissions_new.*.slug' => 'nullable|string|max:255',
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $menu = Menu::create([
                    'icon' => $request->input('icon'),
                    'route' => $request->input('route'),
                    'sort' => $request->input('sort', 0),
                    'parent_id' => $request->input('parent_id'),
                    'created_by' => auth()->id(),
                ]);

                // Persist new permissions (if any)
                $permissionsNew = $request->input('permissions_new', []);
                if (is_array($permissionsNew) && count($permissionsNew)) {
                    foreach ($permissionsNew as $p) {
                        $action = trim($p['action'] ?? '');
                        $slug = trim($p['slug'] ?? '');
                        if ($action === '' && $slug === '') continue;
                        $perm = Permission::create([
                            'action' => $action ?: null,
                            'slug' => $slug ?: null,
                            'menu_id' => $menu->id,
                            'created_by' => auth()->id(),
                        ]);
                        // persist translations if provided
                        $translations = $p['translations'] ?? [];
                        if (is_array($translations) && count($translations)) {
                            foreach ($translations as $locale => $name) {
                                $name = trim($name);
                                if ($name === '') continue;
                                \App\Models\Settings\PermissionTranslation::create([
                                    'permission_id' => $perm->id,
                                    'locale' => $locale,
                                    'name' => $name,
                                    'created_by' => auth()->id(),
                                ]);
                            }
                        }
                    }
                }

                // Create translations
                $names = $request->input('name', []);
                $descriptions = $request->input('description', []);

                // $locales is a collection keyed by locale code; iterate keys to get locale codes
                foreach ($locales->keys() as $locale) {
                    MenuTranslation::create([
                        'menu_id' => $menu->id,
                        'locale' => $locale,
                        'name' => $names[$locale] ?? $names['en'] ?? '',
                        'description' => $descriptions[$locale] ?? null,
                        'created_by' => auth()->id(),
                    ]);
                }

                return success(message: 'Menu created successfully.');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        $menus = Menu::whereNull('parent_id')->orderBy('sort')->get();
        return view('settings.menus.form', compact('form', 'menus', 'locales'));
    }

    /**
     * Show the form for editing the specified menu.
     */
    public function edit(Request $request, $id)
    {
        $form = Menu::findOrFail($id);
        $locales = collect(config('init.languages'));

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'name.en' => 'required|string|max:255',
                    'name.km' => 'nullable|string|max:255',
                    'name.zh' => 'nullable|string|max:255',
                    'description.en' => 'nullable|string',
                    'description.km' => 'nullable|string',
                    'description.zh' => 'nullable|string',
                    'icon' => 'nullable|string|max:255',
                    'route' => 'nullable|string|max:255',
                    'sort' => 'nullable|integer|min:0',
                    'parent_id' => 'nullable|integer|exists:menus,id',
                    'permissions_existing' => 'nullable|array',
                    'permissions_existing.*.id' => 'nullable|integer|exists:permissions,id',
                    'permissions_existing.*.action' => 'nullable|string|max:255',
                    'permissions_existing.*.slug' => 'nullable|string|max:255',
                    'permissions_new' => 'nullable|array',
                    'permissions_new.*.action' => 'nullable|string|max:255',
                    'permissions_new.*.slug' => 'nullable|string|max:255',
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->update([
                    'icon' => $request->input('icon'),
                    'route' => $request->input('route'),
                    'sort' => $request->input('sort', 0),
                    'parent_id' => $request->input('parent_id'),
                    'updated_by' => auth()->id(),
                ]);

                // Handle permissions update/create/delete
                $existing = $request->input('permissions_existing', []);
                $existingIds = [];
                if (is_array($existing) && count($existing)) {
                    foreach ($existing as $e) {
                        $id = isset($e['id']) ? intval($e['id']) : null;
                        $action = trim($e['action'] ?? '');
                        $slug = trim($e['slug'] ?? '');
                        $translations = $e['translations'] ?? [];
                        if ($id) {
                            $perm = Permission::where('id', $id)->where('menu_id', $form->id)->first();
                            if ($perm) {
                                $perm->update([
                                    'action' => $action ?: null,
                                    'slug' => $slug ?: null,
                                    'updated_by' => auth()->id(),
                                ]);
                                $existingIds[] = $perm->id;

                                // update translations
                                if (is_array($translations) && count($translations)) {
                                    foreach ($translations as $locale => $name) {
                                        $name = trim($name ?? '');
                                        \App\Models\Settings\PermissionTranslation::updateOrCreate(
                                            ['permission_id' => $perm->id, 'locale' => $locale],
                                            ['name' => $name, 'updated_by' => auth()->id()]
                                        );
                                    }
                                }
                            }
                        }
                    }
                }

                // Create new permissions
                $permissionsNew = $request->input('permissions_new', []);
                if (is_array($permissionsNew) && count($permissionsNew)) {
                    foreach ($permissionsNew as $p) {
                        $action = trim($p['action'] ?? '');
                        $slug = trim($p['slug'] ?? '');
                        $translations = $p['translations'] ?? [];
                        if ($action === '' && $slug === '') continue;
                        $perm = Permission::create([
                            'action' => $action ?: null,
                            'slug' => $slug ?: null,
                            'menu_id' => $form->id,
                            'created_by' => auth()->id(),
                        ]);
                        $existingIds[] = $perm->id;
                        if (is_array($translations) && count($translations)) {
                            foreach ($translations as $locale => $name) {
                                $name = trim($name);
                                if ($name === '') continue;
                                \App\Models\Settings\PermissionTranslation::create([
                                    'permission_id' => $perm->id,
                                    'locale' => $locale,
                                    'name' => $name,
                                    'created_by' => auth()->id(),
                                ]);
                            }
                        }
                    }
                }

                // Delete permissions that were removed in the form
                $toDelete = Permission::where('menu_id', $form->id)
                    ->when(count($existingIds) > 0, fn($q) => $q->whereNotIn('id', $existingIds))
                    ->get();
                foreach ($toDelete as $del) {
                    $del->deleted_by = auth()->id();
                    $del->save();
                    $del->delete();
                }

                // Update translations
                $names = $request->input('name', []);
                $descriptions = $request->input('description', []);
                
                // iterate locale keys (collection preserves keys from config)
                foreach ($locales->keys() as $locale) {
                    MenuTranslation::updateOrCreate(
                        ['menu_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $names[$locale] ?? $names['en'] ?? '',
                            'description' => $descriptions[$locale] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Menu updated successfully.');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        // Load translations into array for view
        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'description' => $translation->description,
            ];
        }

        $menus = Menu::whereNull('parent_id')->where('id', '!=', $form->id)->orderBy('sort')->get();
        return view('settings.menus.form', compact('form', 'menus', 'locales', 'translations', 'id'));
    }

    /**
     * Remove the specified menu from storage.
     */
    public function destroy($id)
    {
        try {
            $menu = Menu::findOrFail($id);
            
            // Delete child menus first
            Menu::where('parent_id', $menu->id)->update(['deleted_by' => auth()->id()]);
            
            $menu->deleted_by = auth()->id();
            $menu->save();
            $menu->delete();
            
            return success(message: 'Menu deleted successfully.');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
