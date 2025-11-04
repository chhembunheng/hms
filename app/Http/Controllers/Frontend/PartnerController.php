<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\PartnerDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Partner;
use App\Models\Frontend\PartnerTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\ImageRule;

class PartnerController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(PartnerDataTable $dataTable)
    {
        return $dataTable->render('frontend.partners.index');
    }

    public function add(Request $request)
    {
        $form = new Partner();
        $locales = $this->locales;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'logo' => ['nullable', new ImageRule()],
                    'website_url' => 'nullable|url',
                    'is_active' => 'nullable|boolean',
                    'sort' => 'nullable|integer|min:0',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $partner = Partner::create([
                    'logo' => null,
                    'website_url' => $request->input('website_url'),
                    'is_active' => $request->input('is_active', true),
                    'sort' => $request->input('sort', 0),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                if ($request->logo) {
                    $partner->logo = uploadImage($request->logo, 'partners');
                    $partner->save();
                }

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    PartnerTranslation::create([
                        'partner_id' => $partner->id,
                        'locale' => $locale,
                        'name' => $trans['name'],
                        'description' => $trans['description'] ?? null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }

                return success(message: 'Partner created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.partners.form', compact('form', 'locales', 'translations'));
    }

    public function edit(Request $request, $id)
    {
        $form = Partner::with('translations')->findOrFail($id);
        $locales = $this->locales;

        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'description' => $translation->description,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'logo' => ['nullable', new ImageRule()],
                    'website_url' => 'nullable|url',
                    'is_active' => 'nullable|boolean',
                    'sort' => 'nullable|integer|min:0',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->website_url = $request->input('website_url');
                $form->is_active = $request->input('is_active', true);
                $form->sort = $request->input('sort', 0);
                $form->updated_by = auth()->id();

                if ($request->logo) {
                    $form->logo = uploadImage($request->logo, 'partners');
                }

                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    PartnerTranslation::updateOrCreate(
                        ['partner_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $trans['name'],
                            'description' => $trans['description'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Partner updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.partners.form', compact('form', 'locales', 'translations'));
    }

    public function destroy($id)
    {
        try {
            $partner = Partner::findOrFail($id);
            $partner->deleted_by = auth()->id();
            $partner->save();
            $partner->delete();
            return success(message: 'Partner deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
