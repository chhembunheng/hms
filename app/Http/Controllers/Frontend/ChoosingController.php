<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\ChoosingDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Choosing;
use App\Models\Frontend\ChoosingTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChoosingController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(ChoosingDataTable $dataTable)
    {
        return $dataTable->render('frontends.choosings.index');
    }

    public function add(Request $request)
    {
        $form = new Choosing();
        $locales = $this->locales;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                    // icon captured from form but optional for now; image kept for backward compatibility
                    'icon' => 'nullable|string|max:255',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $choosing = Choosing::create([
                    'sort' => $request->input('sort', 0),
                    'is_active' => $request->boolean('is_active', true),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    ChoosingTranslation::create([
                        'choosing_id' => $choosing->id,
                        'locale' => $locale,
                        'title' => $trans['name'],
                        'description' => $trans['description'] ?? null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }

                return success(message: 'Choosing created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.choosings.form', compact('form', 'locales', 'translations'));
    }

    public function show($id)
    {
        $choosing = Choosing::with('translations')->findOrFail($id);
        return view('frontends.choosings.show', compact('choosing'));
    }

    public function edit(Request $request, $id)
    {
        $form = Choosing::with('translations')->findOrFail($id);
        $locales = $this->locales;

        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'title' => $translation->title,
                'description' => $translation->description,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                    'icon' => 'nullable|string|max:255',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->sort = $request->input('sort', 0);
                $form->is_active = $request->boolean('is_active', true);
                $form->updated_by = auth()->id();

                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    ChoosingTranslation::updateOrCreate(
                        ['choosing_id' => $form->id, 'locale' => $locale],
                        [
                            'title' => $trans['name'],
                            'description' => $trans['description'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Choosing updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.choosings.form', compact('form', 'locales', 'translations'));
    }

    public function destroy($id)
    {
        try {
            $choosing = Choosing::findOrFail($id);
            $choosing->deleted_by = auth()->id();
            $choosing->save();
            $choosing->delete();
            return success(message: 'Choosing deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
