<?php

namespace App\Http\Controllers\Settings;

use App\DataTables\Settings\PermissionDataTable;
use App\Models\Settings\Permission;
use App\Models\Settings\PermissionTranslation;
use App\Models\Settings\Menu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(PermissionDataTable $dataTable)
    {
        return $dataTable->render('settings.permissions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function add(Request $request)
    {
        $form = null;
        $menus = Menu::with('translations')->get();
        $locales = $this->locales;
        $translations = [];
        
        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'menu_id' => 'required|exists:menus,id',
                    'icon' => 'nullable|string',
                    'target' => 'required|string',
                    'sort' => 'required|integer|min:0',
                    'action_route' => 'nullable|string',
                    'name.en' => 'required|string|max:255',
                    'name.km' => 'required|string|max:255',
                    'description.en' => 'nullable|string',
                    'description.km' => 'nullable|string',
                ];

                $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $permission = Permission::create([
                    'menu_id' => $request->input('menu_id'),
                    'icon' => $request->input('icon'),
                    'target' => $request->input('target'),
                    'sort' => $request->input('sort'),
                    'action_route' => $request->input('action_route'),
                    'created_by' => auth()->id(),
                ]);

                $names = $request->input('name', []);
                $descriptions = $request->input('description', []);

                foreach ($this->locales->keys() as $locale) {
                    PermissionTranslation::create([
                        'permission_id' => $permission->id,
                        'locale' => $locale,
                        'name' => $names[$locale] ?? $names['en'] ?? '',
                        'description' => $descriptions[$locale] ?? null,
                        'created_by' => auth()->id(),
                    ]);
                }

                return success(message: 'Permission created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('settings.permissions.form', compact('form', 'menus', 'locales', 'translations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, $id)
    {
        $form = Permission::with('translations')->findOrFail($id);
        $menus = Menu::with('translations')->get();
        $locales = $this->locales;
        
        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'description' => $translation->description,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'menu_id' => 'required|exists:menus,id',
                    'icon' => 'nullable|string',
                    'target' => 'required|string',
                    'sort' => 'required|integer|min:0',
                    'action_route' => 'nullable|string',
                    'name.en' => 'required|string|max:255',
                    'name.km' => 'required|string|max:255',
                    'description.en' => 'nullable|string',
                    'description.km' => 'nullable|string',
                ];

                $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->update([
                    'menu_id' => $request->input('menu_id'),
                    'icon' => $request->input('icon'),
                    'target' => $request->input('target'),
                    'sort' => $request->input('sort'),
                    'action_route' => $request->input('action_route'),
                    'updated_by' => auth()->id(),
                ]);

                $names = $request->input('name', []);
                $descriptions = $request->input('description', []);

                foreach ($this->locales->keys() as $locale) {
                    PermissionTranslation::updateOrCreate(
                        ['permission_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $names[$locale] ?? $names['en'] ?? '',
                            'description' => $descriptions[$locale] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Permission updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('settings.permissions.form', compact('form', 'menus', 'locales', 'translations'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();
            return success(message: 'Permission deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
