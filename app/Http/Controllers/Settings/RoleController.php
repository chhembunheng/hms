<?php

namespace App\Http\Controllers\Settings;

use App\DataTables\Settings\RoleDataTable;
use App\Http\Controllers\Controller;
use App\Models\Settings\Role;
use App\Models\Settings\RoleTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct() {}

    /**
     * Display a listing of the roles.
     */
    public function index(RoleDataTable $dataTable)
    {
        return $dataTable->render('settings.roles.index');
    }

    /**
     * Show the form for creating a new role.
     */
    public function add(Request $request)
    {
        $form = new Role();
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
                    'administrator' => 'nullable|boolean',
                    'order' => 'nullable|integer|min:0',
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                // Create role
                $role = Role::create([
                    'administrator' => $request->input('administrator', 0),
                    'order' => $request->input('order', 0),
                    'created_by' => auth()->id(),
                ]);

                // Create translations
                $names = $request->input('name', []);
                $descriptions = $request->input('description', []);
                
                foreach ($locales as $locale) {
                    RoleTranslation::create([
                        'role_id' => $role->id,
                        'locale' => $locale,
                        'name' => $names[$locale] ?? $names['en'] ?? '',
                        'description' => $descriptions[$locale] ?? null,
                        'created_by' => auth()->id(),
                    ]);
                }

                return success(message: 'Role created successfully.');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('settings.roles.form', compact('form', 'locales'));
    }

    /**
     * Show the form for editing the specified role.
     */
    public function edit(Request $request, $id)
    {
        $form = Role::findOrFail($id);
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
                    'administrator' => 'nullable|boolean',
                    'order' => 'nullable|integer|min:0',
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                // Update role
                $form->update([
                    'administrator' => $request->input('administrator', 0),
                    'order' => $request->input('order', 0),
                    'updated_by' => auth()->id(),
                ]);

                // Update translations
                $names = $request->input('name', []);
                $descriptions = $request->input('description', []);
                
                foreach ($locales as $locale) {
                    RoleTranslation::updateOrCreate(
                        ['role_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $names[$locale] ?? $names['en'] ?? '',
                            'description' => $descriptions[$locale] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Role updated successfully.');
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

        return view('settings.roles.form', compact('form', 'locales', 'translations', 'id'));
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy($id)
    {
        try {
            $role = Role::findOrFail($id);

            // Delete translations first
            RoleTranslation::where('role_id', $role->id)->delete();

            // Delete the role
            $role->delete();

            return success(message: 'Role deleted successfully.');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
