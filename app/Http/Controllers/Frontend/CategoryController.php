<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Frontend\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Frontend\CategoryTranslation;
use App\DataTables\Frontend\CategoryDataTable;
use App\Rules\ImageRule;

class CategoryController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('frontends.categories.index');
    }

    public function add(Request $request)
    {
        $form = new Category();
        $locales = $this->locales;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                ];
                foreach ($this->locales->keys() as $locale) {
                    if ($locale === config('app.locale')) {
                        $rules["translations.{$locale}.name"] = 'required|string|max:255';
                        $rules["translations.{$locale}.description"] = 'nullable|string';
                    }
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }
                DB::transaction(function () use ($request, $form) {
                    $category = Category::create([
                        'icon' => $request->input('icon', null),
                        'sort' => $request->input('sort', 0),
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);

                    foreach ($this->locales->keys() as $locale) {
                        $trans = $request->input("translations.{$locale}");
                        if(empty($trans['name'])){
                            continue;
                        }
                        CategoryTranslation::create([
                            'category_id' => $category->id,
                            'locale' => $locale,
                            'name' => $trans['name'],
                            'description' => $trans['description'] ?? null,
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                        ]);
                    }
                });
                return success(message: 'Category created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.categories.form', compact('form', 'locales', 'translations'));
    }

    public function edit($id, Request $request)
    {
        $form = Category::findOrFail($id);
        $locales = $this->locales;
        $translations = [];

        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'description' => $translation->description,
            ];
        }

        foreach ($this->locales->keys() as $locale) {
            if (!isset($translations[$locale])) {
                $translations[$locale] = [
                    'name' => '',
                    'description' => '',
                ];
            }
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                ];
                foreach ($this->locales->keys() as $locale) {
                    if ($locale === config('app.locale')) {
                        $rules["translations.{$locale}.name"] = 'required|string|max:255';
                        $rules["translations.{$locale}.description"] = 'nullable|string';
                    }
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                DB::transaction(function () use ($request, $form) {
                    $form->icon = $request->input('icon', $form->icon);
                    $form->sort = $request->input('sort', $form->sort);
                    $form->updated_by = auth()->id();
                    $form->save();

                    foreach ($this->locales->keys() as $locale) {
                        $trans = $request->input("translations.{$locale}");
                        if(empty($trans['name'])){
                            continue;
                        }
                        $translation = $form->translations->where('locale', $locale)->first();
                        if ($translation) {
                            $translation->update([
                                'name' => $trans['name'],
                                'description' => $trans['description'] ?? null,
                                'updated_by' => auth()->id(),
                            ]);
                        } else {
                            CategoryTranslation::create([
                                'category_id' => $form->id,
                                'locale' => $locale,
                                'name' => $trans['name'],
                                'description' => $trans['description'] ?? null,
                                'created_by' => auth()->id(),
                                'updated_by' => auth()->id(),
                            ]);
                        }
                    }
                });
                return success(message: 'Category updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.categories.form', compact('form', 'locales', 'translations'));
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->delete();
            return success(message: 'Category deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
