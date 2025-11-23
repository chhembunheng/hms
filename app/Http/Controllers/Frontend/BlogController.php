<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\BlogDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Blog;
use App\Models\Frontend\BlogTranslation;
use App\Models\Frontend\SeoMeta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\ImageRule;

class BlogController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(BlogDataTable $dataTable)
    {
        return $dataTable->render('frontends.blogs.index');
    }

    public function add(Request $request)
    {
        $form = new Blog();
        $locales = $this->locales;
        $translations = [];
    $seo = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'image' => ['nullable', new ImageRule()],
                    'slug' => 'required|string|unique:blogs,slug',
                ];
                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.title"] = 'required|string|max:255';
                    $rules["translations.{$locale}.content"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $blog = Blog::create([
                    'slug' => slug($request->input('slug', null)),
                    'image' => null,
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                if ($request->image) {
                    $blog->image = uploadImage($request->image, 'blogs');
                    $blog->save();
                }

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    BlogTranslation::create([
                        'blog_id' => $blog->id,
                        'locale' => $locale,
                        'title' => $trans['title'],
                        'content' => $trans['content'] ?? null,
                        'description' => $trans['description'] ?? null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                    $seoInput = $request->input("seo.{$locale}", []);
                    if (!empty($seoInput)) {
                        SeoMeta::updateOrCreate(
                            [
                                'seoable_type' => Blog::class,
                                'seoable_id' => $blog->id,
                                'locale' => $locale,
                            ],
                            [
                                'meta_title' => $seoInput['meta_title'] ?? null,
                                'meta_description' => $seoInput['meta_description'] ?? null,
                                'meta_keywords' => $seoInput['meta_keywords'] ?? null,
                                'canonical_url' => $seoInput['canonical_url'] ?? null,
                                'og_title' => $seoInput['og_title'] ?? null,
                                'og_description' => $seoInput['og_description'] ?? null,
                                'updated_by' => auth()->id(),
                                'created_by' => auth()->id(),
                            ]
                        );
                    }
                }

                return success(message: 'Blog created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

    return view('frontends.blogs.form', compact('form', 'locales', 'translations', 'seo'));
    }

    public function show($slug)
    {
        $locale = app()->getLocale();
        $blog = Blog::where('slug', $slug)->with('translations')->firstOrFail();
        return view('frontends.blogs.show', compact('blog', 'locale'));
    }

    public function edit(Request $request, $id)
    {
    $form = Blog::with(['translations','seoMetas'])->findOrFail($id);
        $locales = $this->locales;

        $translations = [];
        $seo = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'title' => $translation->title,
                'content' => $translation->content,
                'description' => $translation->description,
            ];
        }
        foreach ($form->seoMetas as $meta) {
            $seo[$meta->locale] = [
                'meta_title' => $meta->meta_title,
                'meta_description' => $meta->meta_description,
                'meta_keywords' => $meta->meta_keywords,
                'canonical_url' => $meta->canonical_url,
                'og_title' => $meta->og_title,
                'og_description' => $meta->og_description,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'image' => ['nullable', new ImageRule()],
                    'slug' => 'required|string|unique:blogs,slug,' . $form->id,
                ];
                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.title"] = 'required|string|max:255';
                    $rules["translations.{$locale}.content"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->slug = slug($request->input('slug', null));
                $form->updated_by = auth()->id();

                if ($request->image) {
                    $form->image = uploadImage($request->image, 'blogs');
                }

                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    BlogTranslation::updateOrCreate(
                        ['blog_id' => $form->id, 'locale' => $locale],
                        [
                            'title' => $trans['title'],
                            'content' => $trans['content'] ?? null,
                            'description' => $trans['description'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                    $seoInput = $request->input("seo.{$locale}", []);
                    if (!empty($seoInput)) {
                        SeoMeta::updateOrCreate(
                            [
                                'seoable_type' => Blog::class,
                                'seoable_id' => $form->id,
                                'locale' => $locale,
                            ],
                            [
                                'meta_title' => $seoInput['meta_title'] ?? null,
                                'meta_description' => $seoInput['meta_description'] ?? null,
                                'meta_keywords' => $seoInput['meta_keywords'] ?? null,
                                'canonical_url' => $seoInput['canonical_url'] ?? null,
                                'og_title' => $seoInput['og_title'] ?? null,
                                'og_description' => $seoInput['og_description'] ?? null,
                                'updated_by' => auth()->id(),
                            ]
                        );
                    }
                }

                return success(message: 'Blog updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

    return view('frontends.blogs.form', compact('form', 'locales', 'translations', 'seo'));
    }

    public function destroy($id)
    {
        try {
            $blog = Blog::findOrFail($id);
            $blog->deleted_by = auth()->id();
            $blog->save();
            $blog->delete();
            return success(message: 'Blog deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
