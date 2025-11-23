<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\IntegrationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Integration;
use App\Models\Frontend\IntegrationTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\ImageRule;

class IntegrationController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(IntegrationDataTable $dataTable)
    {
        return $dataTable->render('frontends.integrations.index');
    }

    public function add(Request $request)
    {
        $form = new Integration();
        $locales = $this->locales;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                    'image' => ['nullable', new ImageRule()],
                    'logo' => ['nullable', new ImageRule()],
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.slug"] = 'required|string|unique:integration_translations,slug';
                    $rules["translations.{$locale}.content"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $integration = Integration::create([
                    'image' => null,
                    'logo' => null,
                    'sort' => $request->input('sort', 0),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                if ($request->image) {
                    $integration->image = uploadImage($request->image, 'uploads/integrations');
                    $integration->save();
                }

                if ($request->logo) {
                    $integration->logo = uploadImage($request->logo, 'uploads/integrations/logos');
                    $integration->save();
                }
                if ($request->has('images') && is_array($request->input('images'))) {
                    $integration->images = processGalleryImages($request->input('images'), 'uploads/integrations/gallery');
                    $integration->save();
                }

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    IntegrationTranslation::create([
                        'integration_id' => $integration->id,
                        'locale' => $locale,
                        'name' => $trans['name'],
                        'slug' => $trans['slug'],
                        'content' => $trans['content'] ?? null,
                        'description' => $trans['description'] ?? null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }

                return success(message: 'Integration created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.integrations.form', compact('form', 'locales', 'translations'));
    }

    public function show($slug)
    {
        $locale = app()->getLocale();
        $integration = Integration::whereHas('translations', function ($query) use ($locale, $slug) {
            $query->where('locale', $locale)->where('slug', $slug);
        })->with('translations')->firstOrFail();
        return view('frontends.integrations.show', compact('integration', 'locale'));
    }

    public function edit(Request $request, $id)
    {
        $form = Integration::with('translations')->findOrFail($id);
        $locales = $this->locales;

        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'slug' => $translation->slug,
                'content' => $translation->content,
                'description' => $translation->description,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                    'image' => ['nullable', new ImageRule()],
                    'logo' => ['nullable', new ImageRule()],
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.slug"] = 'required|string|unique:integration_translations,slug,' . $form->id . ',integration_id';
                    $rules["translations.{$locale}.content"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->sort = $request->input('sort', 0);
                $form->updated_by = auth()->id();

                if ($request->image) {
                    $form->image = uploadImage($request->image, 'uploads/integrations');
                }

                if ($request->logo) {
                    $form->logo = uploadImage($request->logo, 'uploads/integrations/logos');
                }

                if ($request->has('images') && is_array($request->input('images'))) {
                    $form->images = processGalleryImages($request->input('images'), 'uploads/integrations/gallery');
                }

                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    IntegrationTranslation::updateOrCreate(
                        ['integration_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $trans['name'],
                            'slug' => $trans['slug'],
                            'content' => $trans['content'] ?? null,
                            'description' => $trans['description'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Integration updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.integrations.form', compact('form', 'locales', 'translations'));
    }

    public function destroy($id)
    {
        try {
            $integration = Integration::findOrFail($id);
            $integration->deleted_by = auth()->id();
            $integration->save();
            $integration->delete();
            return success(message: 'Integration deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
