<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\IntegrationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Integration;
use App\Models\Frontend\IntegrationTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IntegrationController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(IntegrationDataTable $dataTable)
    {
        return $dataTable->render('frontend.integrations.index');
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
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.slug"] = 'required|string|unique:integration_translations,slug';
                    $rules["translations.{$locale}.short_description"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $integration = Integration::create([
                    'image' => null,
                    'sort' => $request->input('sort', 0),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                if ($request->hasFile('image')) {
                    $integration->image = uploadFile($request->file('image'), 'integrations');
                    $integration->save();
                }

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    IntegrationTranslation::create([
                        'integration_id' => $integration->id,
                        'locale' => $locale,
                        'name' => $trans['name'],
                        'slug' => $trans['slug'],
                        'short_description' => $trans['short_description'] ?? null,
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

        return view('frontend.integrations.form', compact('form', 'locales', 'translations'));
    }

    public function show($slug)
    {
        $locale = app()->getLocale();
        $integration = Integration::whereHas('translations', function ($query) use ($locale, $slug) {
            $query->where('locale', $locale)->where('slug', $slug);
        })->with('translations')->firstOrFail();
        return view('frontend.integrations.show', compact('integration', 'locale'));
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
                'short_description' => $translation->short_description,
                'description' => $translation->description,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.slug"] = 'required|string|unique:integration_translations,slug,' . $form->id . ',integration_id';
                    $rules["translations.{$locale}.short_description"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->sort = $request->input('sort', 0);
                $form->updated_by = auth()->id();

                if ($request->hasFile('image')) {
                    $form->image = uploadFile($request->file('image'), 'integrations');
                }

                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    IntegrationTranslation::updateOrCreate(
                        ['integration_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $trans['name'],
                            'slug' => $trans['slug'],
                            'short_description' => $trans['short_description'] ?? null,
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

        return view('frontend.integrations.form', compact('form', 'locales', 'translations'));
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
