<?php

namespace App\Http\Controllers\Frontend;

use App\Rules\ImageRule;
use Illuminate\Http\Request;
use App\Models\Frontend\Product;
use App\Models\Frontend\Category;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\ProductFeature;
use Illuminate\Support\Facades\Validator;
use App\Models\Frontend\ProductTranslation;
use App\DataTables\Frontend\ProductDataTable;
use App\Models\Frontend\ProductFeatureDetail;
use App\Models\Frontend\ProductFeatureTranslation;
use App\Models\Frontend\ProductFeatureDetailTranslation;

class ProductController extends Controller
{
    protected $locales;
    protected $categories;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
        $this->categories = Category::with('translations')->orderBy('sort')->get()->map(function ($category) {
            return [
                $category->id => $category->getName(app()->getLocale()),
            ];
        })->collapse();
    }

    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('frontends.products.index');
    }

    public function add(Request $request)
    {
        $form = new Product();
        $locales = $this->locales;
        $categories = $this->categories;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'slug' => 'required|string|unique:products,slug',
                    'sort' => 'nullable|integer',
                    'image' => ['nullable', new ImageRule()],
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.short_description"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }
                DB::transaction(function () use ($request) {

                    $product = Product::create([
                        'slug' => slug($request->input('slug', null)),
                        'image' => null,
                        'icon' => $request->input('icon', null),
                        'sort' => $request->input('sort', 0),
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);

                    if ($request->image) {
                        $product->image = uploadImage($request->image, 'products');
                        $product->save();
                    }

                    $product->categories()->sync($request->input('category_id', []));
                    $product->tags()->sync($request->input('tag_id', []));

                    foreach ($this->locales->keys() as $locale) {
                        $trans = $request->input("translations.{$locale}");
                        ProductTranslation::create([
                            'product_id' => $product->id,
                            'locale' => $locale,
                            'name' => $trans['name'],
                            'short_description' => $trans['short_description'] ?? null,
                            'description' => $trans['description'] ?? null,
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                        ]);
                    }

                    // Handle features and feature details
                    if ($request->has('features')) {
                        foreach ($request->input('features') as $featureData) {
                            $feature = ProductFeature::create([
                                'product_id' => $product->id,
                                'icon' => $featureData['icon'] ?? null,
                                'sort' => $featureData['sort'] ?? 0,
                                'created_by' => auth()->id(),
                                'updated_by' => auth()->id(),
                            ]);

                            // Create feature translations
                            if (isset($featureData['translations'])) {
                                foreach ($featureData['translations'] as $locale => $translation) {
                                    ProductFeatureTranslation::create([
                                        'product_feature_id' => $feature->id,
                                        'locale' => $locale,
                                        'title' => $translation['title'] ?? null,
                                        'description' => $translation['description'] ?? null,
                                        'created_by' => auth()->id(),
                                        'updated_by' => auth()->id(),
                                    ]);
                                }
                            }

                            // Handle feature details
                            if (isset($featureData['details'])) {
                                foreach ($featureData['details'] as $detailData) {
                                    $detail = ProductFeatureDetail::create([
                                        'product_feature_id' => $feature->id,
                                        'icon' => $detailData['icon'] ?? null,
                                        'sort' => $detailData['sort'] ?? 0,
                                        'created_by' => auth()->id(),
                                        'updated_by' => auth()->id(),
                                    ]);

                                    // Create detail translations
                                    if (isset($detailData['translations'])) {
                                        foreach ($detailData['translations'] as $locale => $translation) {
                                            ProductFeatureDetailTranslation::create([
                                                'product_feature_detail_id' => $detail->id,
                                                'locale' => $locale,
                                                'title' => $translation['title'] ?? null,
                                                'description' => $translation['description'] ?? null,
                                                'created_by' => auth()->id(),
                                                'updated_by' => auth()->id(),
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                });

                return success(message: 'Product created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.products.form', compact('form', 'locales', 'translations', 'categories'));
    }

    public function show($slug)
    {
        $locale = app()->getLocale();
        $product = Product::where('slug', $slug)->with('translations')->firstOrFail();

        return view('frontends.products.show', compact('product', 'locale'));
    }

    public function edit(Request $request, $id)
    {
        $form = Product::with(['translations', 'features.translations', 'features.details.translations'])->findOrFail($id);
        $locales = $this->locales;
        $categories = $this->categories;
        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'short_description' => $translation->short_description,
                'description' => $translation->description,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'slug' => 'required|string|unique:products,slug,' . $form->id,
                    'sort' => 'nullable|integer',
                    'image' => ['nullable', new ImageRule()],
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.short_description"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }
                DB::transaction(function () use ($request, $form) {

                    $form->slug = slug($request->input('slug', null));
                    $form->sort = $request->input('sort', 0);
                    $form->icon = $request->input('icon', null);
                    $form->updated_by = auth()->id();

                    if ($request->image) {
                        $form->image = uploadImage($request->image, 'products');
                    }

                    $form->save();

                    $form->categories()->sync($request->input('category_id', []));
                    $form->tags()->sync($request->input('tag_id', []));

                    foreach ($this->locales->keys() as $locale) {
                        $trans = $request->input("translations.{$locale}");
                        ProductTranslation::updateOrCreate(
                            ['product_id' => $form->id, 'locale' => $locale],
                            [
                                'name' => $trans['name'],
                                'short_description' => $trans['short_description'] ?? null,
                                'description' => $trans['description'] ?? null,
                                'updated_by' => auth()->id(),
                            ]
                        );
                    }

                    // Delete existing features and recreate (simpler approach)
                    $form->features()->each(function ($feature) {
                        $feature->details()->each(function ($detail) {
                            $detail->translations()->delete();
                            $detail->delete();
                        });
                        $feature->translations()->delete();
                        $feature->delete();
                    });

                    // Handle features and feature details
                    if ($request->has('features')) {
                        foreach ($request->input('features') as $featureData) {
                            $feature = ProductFeature::create([
                                'product_id' => $form->id,
                                'icon' => $featureData['icon'] ?? null,
                                'sort' => $featureData['sort'] ?? 0,
                                'created_by' => auth()->id(),
                                'updated_by' => auth()->id(),
                            ]);

                            // Create feature translations
                            if (isset($featureData['translations'])) {
                                foreach ($featureData['translations'] as $locale => $translation) {
                                    ProductFeatureTranslation::create([
                                        'product_feature_id' => $feature->id,
                                        'locale' => $locale,
                                        'title' => $translation['title'] ?? null,
                                        'description' => $translation['description'] ?? null,
                                        'created_by' => auth()->id(),
                                        'updated_by' => auth()->id(),
                                    ]);
                                }
                            }

                            // Handle feature details
                            if (isset($featureData['details'])) {
                                foreach ($featureData['details'] as $detailData) {
                                    $detail = ProductFeatureDetail::create([
                                        'product_feature_id' => $feature->id,
                                        'icon' => $detailData['icon'] ?? null,
                                        'sort' => $detailData['sort'] ?? 0,
                                        'created_by' => auth()->id(),
                                        'updated_by' => auth()->id(),
                                    ]);

                                    // Create detail translations
                                    if (isset($detailData['translations'])) {
                                        foreach ($detailData['translations'] as $locale => $translation) {
                                            ProductFeatureDetailTranslation::create([
                                                'product_feature_detail_id' => $detail->id,
                                                'locale' => $locale,
                                                'title' => $translation['title'] ?? null,
                                                'description' => $translation['description'] ?? null,
                                                'created_by' => auth()->id(),
                                                'updated_by' => auth()->id(),
                                            ]);
                                        }
                                    }
                                }
                            }
                        }
                    }
                });

                return success(message: 'Product updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.products.form', compact('form', 'locales', 'translations', 'categories'));
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            $product->deleted_by = auth()->id();
            $product->save();
            $product->delete();

            return success(message: 'Product deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
