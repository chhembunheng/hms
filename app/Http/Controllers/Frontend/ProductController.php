<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\ProductDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Product;
use App\Models\Frontend\ProductTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\ImageRule;

class ProductController extends Controller
{
    protected $locales;
    protected $categories;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
        $this->categories = [
            1 => 'Human Resources',
            2 => 'Finance',
            3 => 'IT Services',
            4 => 'Marketing',
            5 => 'Sales',
            6 => 'Customer Support',
            7 => 'Research and Development',
            8 => 'Operations',
            9 => 'Legal',
            10 => 'Administration',
        ];
    }

    public function index(ProductDataTable $dataTable)
    {
        return $dataTable->render('frontend.products.index');
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
                $product = Product::create([
                    'slug' => slug($request->input('slug', null)),
                    'image' => null,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                if ($request->image) {
                    $product->image = uploadImage($request->image, 'products');
                    $product->save();
                }

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

                return success(message: 'Product created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.products.form', compact('form', 'locales', 'translations', 'categories'));
    }

    public function show($slug)
    {
        $locale = app()->getLocale();
        $product = Product::where('slug', $slug)->with('translations')->firstOrFail();

        return view('frontend.products.show', compact('product', 'locale'));
    }

    public function edit(Request $request, $id)
    {
        $form = Product::with('translations')->findOrFail($id);
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

                $form->slug = slug($request->input('slug', null));
                $form->sort = $request->input('sort', 0);
                $form->updated_by = auth()->id();

                if ($request->image) {
                    $form->image = uploadImage($request->image, 'products');
                }

                $form->save();

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

                return success(message: 'Product updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.products.form', compact('form', 'locales', 'translations', 'categories'));
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
