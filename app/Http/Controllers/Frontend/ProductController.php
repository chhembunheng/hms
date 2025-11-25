<?php

namespace App\Http\Controllers\Frontend;

use App\Rules\ImageRule;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\FrontendTrait;
use App\Models\Frontend\Product;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Frontend\ProductFeature;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use App\Models\Frontend\ProductTranslation;
use App\DataTables\Frontend\ProductDataTable;
use App\Models\Frontend\ProductFeatureDetail;
use App\Models\Frontend\NavigationTranslation;
use App\Models\Frontend\ProductFeatureTranslation;
use App\Models\Frontend\ProductFeatureDetailTranslation;

class ProductController extends Controller
{
    use FrontendTrait;

    protected $locales;
    protected $categories;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('frontends.products.index');
    }

    public function add(Request $request)
    {
        $form = new Product();
        $locales = $this->locales;
        $categories = $this->getCategoriesForDropdown();
        $tags = $this->getTagsForDropdown();
        $navigations = $this->getNavigationsForDropdown();
        $translations = [];

        $navigations = $this->getNavigationsForDropdown();

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'slug' => 'required|string|unique:products,slug',
                    'sort' => 'nullable|integer',
                    'image' => ['nullable', new ImageRule()],
                    'is_slider' => 'nullable|boolean',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.content"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }
                DB::transaction(function () use ($request) {

                    $form = Product::create([
                        'slug' => slug($request->input('slug', null)),
                        'image' => null,
                        'icon' => $request->input('icon', null),
                        'sort' => $request->input('sort', 0),
                        'is_slider' => $request->boolean('is_slider'),
                        'slider_image' => null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);

                    if ($request->image) {
                        $form->image = uploadImage($request->image, 'uploads/products');
                        $form->save();
                    }

                    if ($request->slider_image) {
                        $form->slider_image = uploadImage($request->slider_image, 'uploads/products/slider');
                        $form->save();
                    }

                    if ($request->has('images') && is_array($request->input('images'))) {
                        $form->images = processGalleryImages($request->input('images'), 'uploads/products/gallery');
                        $form->save();
                    }

                    $form->categories()->sync($request->input('category_id', []));
                    $form->tags()->sync($request->input('tag_id', []));
                    $translations = [];
                    foreach ($this->locales->keys() as $locale) {
                        $trans = $request->input("translations[{$locale}]");
                        $name = $trans['name'] ?? '';
                        $description = $trans['description'] ?? '';
                        $sliderTitle = $trans['slider_title'] ?? $name;
                        $sliderDescription = $trans['slider_description'] ?? $description;
                        $translations[] = [
                            'product_id' => $form->id,
                            'name' => $name,
                            'description' => $description,
                            'slider_title' => $sliderTitle,
                            'slider_description' => $sliderDescription,
                            'content' => $trans['content'] ?? null,
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                        ];
                    }
                    ProductTranslation::insert($translations);
                });
                return success(message: 'Product created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.products.form', compact('form', 'locales', 'translations', 'categories', 'navigations', 'tags'));
    }

    public function show($slug)
    {
        $locale = app()->getLocale();
        $product = Product::where('slug', $slug)->with('translations')->firstOrFail();

        return view('frontends.products.show', compact('product', 'locale'));
    }

    public function edit(Request $request, $id)
    {
        $form = Product::with(['translations', 'features.details.translations'])->findOrFail($id);
        $locales = $this->locales;
        $categories = $this->getCategoriesForDropdown();
        $tags = $this->getTagsForDropdown();
        $navigations = $this->getNavigationsForDropdown();
        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'description' => $translation->description,
                'content' => $translation->content,
                'slider_title' => $translation->slider_title,
                'slider_description' => $translation->slider_description,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'slug' => 'required|string|unique:products,slug,' . $form->id,
                    'sort' => 'nullable|integer',
                    'image' => ['nullable', new ImageRule()],
                    'is_slider' => 'nullable|boolean',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.content"] = 'nullable|string';
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
                    $form->is_slider = $request->boolean('is_slider');
                    $form->updated_by = auth()->id();

                    if ($request->image) {
                        $form->image = uploadImage($request->image, 'uploads/products');
                    }

                    if ($request->slider_image) {
                        $form->slider_image = uploadImage($request->slider_image, 'uploads/products/slider');
                    }

                    if ($request->has('images') && is_array($request->input('images'))) {
                        $form->images = processGalleryImages($request->input('images'), 'uploads/products/gallery');
                    }

                    $form->save();
                    $form->generateSEO();
                    if ($request->navigation_id) {
                        $linked = $form->navigations()->updateOrCreate(
                            [
                                'linked_type' => Product::class,
                                'linked_id' => $form->id,
                            ],
                            [
                                'parent_id' => $request->input('navigation_id', null),
                                'icon' => $request->input('icon', null),
                                'url' => 'products' . '/' . $form->slug,
                                'sort' => $request->input('sort', 0),
                                'created_by' => auth()->id(),
                                'updated_by' => auth()->id(),
                            ]
                        );
                    } else {
                        $form->navigations()->delete();
                    }
                    $form->categories()->sync($request->input('category_id', []));
                    $form->tags()->sync($request->input('tag_id', []));
                    $translations = [];
                    $linkeds = [];
                    foreach ($this->locales->keys() as $locale) {
                        $trans = $request->input("translations.{$locale}");
                        $name = $trans['name'] ?? '';
                        $description = $trans['description'] ?? '';
                        $sliderTitle = $trans['slider_title'] ?? $name;
                        $sliderDescription = $trans['slider_description'] ?? $description;
                        $translations[] = [
                            'product_id' => $form->id,
                            'name' => $name,
                            'description' => $description,
                            'slider_title' => $sliderTitle,
                            'slider_description' => Str::of($sliderDescription)->stripTags()->trim(),
                            'content' => $trans['content'] ?? null,
                            'locale' => $locale,
                            'updated_by' => auth()->id(),
                        ];
                        $linkeds[] = [
                            'navigation_id' => $linked->id,
                            'locale' => $locale,
                            'name' => $trans['name'],
                            'label' => $trans['name'],
                            'updated_by' => auth()->id(),
                        ];
                    }
                    NavigationTranslation::upsert($linkeds, ['navigation_id', 'locale'], ['name', 'label', 'updated_by']);
                    ProductTranslation::upsert($translations, ['product_id', 'locale'], ['name', 'description', 'content', 'slider_title', 'slider_description', 'updated_by']);
                    Artisan::call('cache:clear');
                });
                return success(message: 'Product updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.products.form', compact('form', 'locales', 'translations', 'categories', 'navigations', 'tags'));
    }

    public function feature(Request $request, $id)
    {
        $form = Product::with(['translations', 'features.translations', 'features.details.translations'])->findOrFail($id);
        $locales = $this->locales;
        $categories = $this->categories;
        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'content' => $translation->content,
                'description' => $translation->description,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'slug' => 'required|string|unique:products,slug,' . $form->id,
                    'sort' => 'nullable|integer',
                    'image' => ['nullable', new ImageRule()],
                    'is_slider' => 'nullable|boolean',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.content"] = 'nullable|string';
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
                    $form->is_slider = $request->boolean('is_slider');
                    $form->updated_by = auth()->id();
                    if ($request->image) {
                        $form->image = uploadImage($request->image, 'uploads/products');
                    }

                    if ($request->slider_image) {
                        $form->slider_image = uploadImage($request->slider_image, 'uploads/products/slider');
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
                                'content' => $trans['content'] ?? null,
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

        return view('frontends.products.partials.product-features', compact('form', 'locales', 'translations', 'categories'));
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
