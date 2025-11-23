<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Frontend\Service;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Frontend\ServiceTranslation;
use App\DataTables\Frontend\ServiceDataTable;
use App\Rules\ImageRule;

class ServiceController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(ServiceDataTable $dataTable)
    {
        return $dataTable->render('frontends.services.index');
    }

    public function add(Request $request)
    {
        $form = new Service();
        $locales = $this->locales;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                    'image' => ['nullable', new ImageRule()],
                    'slug' => 'required|string|unique:services,slug',
                    'is_slider' => 'nullable|boolean',
                ];
                foreach ($this->locales->keys() as $locale) {
                    if ($locale === config('app.locale')) {
                        $rules["translations.{$locale}.name"] = 'required|string|max:255';
                        $rules["translations.{$locale}.content"] = 'nullable|string';
                        $rules["translations.{$locale}.description"] = 'nullable|string';
                    }
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }
                DB::transaction(function () use ($request, $form) {
                    $service = Service::create([
                        'slug' => slug($request->input('slug', null)),
                        'image' => null,
                        'sort' => $request->input('sort', 0),
                        'is_slider' => $request->boolean('is_slider'),
                        'slider_image' => null,
                        'content' => $request->input('content', null),
                        'description' => $request->input('description', null),
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);

                    if ($request->image) {
                        $service->image = uploadImage($request->image, 'uploads/services');
                        $service->save();
                    }

                    if ($request->slider_image) {
                        $service->slider_image = uploadImage($request->slider_image, 'uploads/services/slider');
                        $service->save();
                    }

                    if ($request->has('images') && is_array($request->input('images'))) {
                        $service->images = processGalleryImages($request->input('images'), 'uploads/services/gallery');
                        $service->save();
                    }

                    foreach ($this->locales->keys() as $locale) {
                        $trans = $request->input("translations.{$locale}");
                        if(empty($trans['name'])){
                            continue;
                        }
                        ServiceTranslation::create([
                            'service_id' => $service->id,
                            'locale' => $locale,
                            'name' => $trans['name'],
                            'content' => $trans['content'] ?? null,
                            'description' => $trans['description'] ?? null,
                            'slider_title' => $trans['slider_title'] ?? null,
                            'slider_description' => $trans['slider_description'] ?? null,
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                        ]);
                    }
                });
                return success(message: 'Service created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.services.form', compact('form', 'locales', 'translations'));
    }

    public function show($slug)
    {
        $locale = app()->getLocale();
        $service = Service::where('slug', $slug)->with('translations')->firstOrFail();
        return view('frontends.services.show', compact('service', 'locale'));
    }

    public function edit(Request $request, $id)
    {
        $form = Service::with('translations')->findOrFail($id);
        $locales = $this->locales;

        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'content' => $translation->content,
                'description' => $translation->description,
                'content' => $translation->content,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                    'image' => ['nullable', new ImageRule()],
                    'slug' => 'required|string|unique:services,slug,' . $form->id,
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

                $form->slug = slug($request->input('slug', null));
                $form->sort = $request->input('sort', 0);
                $form->is_slider = $request->boolean('is_slider');
                $form->updated_by = auth()->id();
                if ($request->image) {
                    $form->image = uploadImage($request->image, 'uploads/services');
                }
                if ($request->slider_image) {
                    $form->slider_image = uploadImage($request->slider_image, 'uploads/services/slider');
                }

                if ($request->has('images') && is_array($request->input('images'))) {
                    $form->images = processGalleryImages($request->input('images'), 'uploads/services/gallery');
                }

                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    ServiceTranslation::updateOrCreate(
                        ['service_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $trans['name'],
                            'content' => $trans['content'] ?? null,
                            'description' => $trans['description'] ?? null,
                            'slider_title' => $trans['slider_title'] ?? null,
                            'slider_description' => $trans['slider_description'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Service updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.services.form', compact('form', 'locales', 'translations'));
    }

    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->deleted_by = auth()->id();
            $service->save();
            $service->delete();
            return success(message: 'Service deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
