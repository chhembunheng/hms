<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Settings\MenuDataTable;
use App\Models\Settings\Menu;
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

                // Create translations
                $names = $request->input('name', []);
                $descriptions = $request->input('description', []);
                
                foreach ($locales as $locale) {
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

                // Update translations
                $names = $request->input('name', []);
                $descriptions = $request->input('description', []);
                
                foreach ($locales as $locale) {
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
