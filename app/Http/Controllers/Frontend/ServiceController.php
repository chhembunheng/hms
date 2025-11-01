<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\ServiceDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Service;
use App\Models\Frontend\ServiceTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('app.languages'));
    }

    public function index(ServiceDataTable $dataTable)
    {
        return $dataTable->render('frontend.services.index');
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
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'slug' => 'required|string|unique:services,slug',
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

                $service = Service::create([
                    'slug' => $request->input('slug'),
                    'image' => null,
                    'sort' => $request->input('sort', 0),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                if ($request->hasFile('image')) {
                    $service->image = uploadFile($request->file('image'), 'services');
                    $service->save();
                }

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    ServiceTranslation::create([
                        'service_id' => $service->id,
                        'locale' => $locale,
                        'name' => $trans['name'],
                        'short_description' => $trans['short_description'] ?? null,
                        'description' => $trans['description'] ?? null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }

                return success(message: 'Service created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.services.form', compact('form', 'locales', 'translations'));
    }

    public function show($slug)
    {
        $locale = app()->getLocale();
        $service = Service::where('slug', $slug)->with('translations')->firstOrFail();
        return view('frontend.services.show', compact('service', 'locale'));
    }

    public function edit(Request $request, $id)
    {
        $form = Service::with('translations')->findOrFail($id);
        $locales = $this->locales;

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
                    'sort' => 'nullable|integer',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'slug' => 'required|string|unique:services,slug,' . $form->id,
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

                $form->slug = $request->input('slug');
                $form->sort = $request->input('sort', 0);
                $form->updated_by = auth()->id();
                if ($request->hasFile('image')) {
                    $form->image = uploadFile($request->file('image'), 'services');
                }
                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    ServiceTranslation::updateOrCreate(
                        ['service_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $trans['name'],
                            'short_description' => $trans['short_description'] ?? null,
                            'description' => $trans['description'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Service updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.services.form', compact('form', 'locales', 'translations'));
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
