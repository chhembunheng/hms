<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\CareerDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Career;
use App\Models\Frontend\CareerTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CareerController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(CareerDataTable $dataTable)
    {
        return $dataTable->render('frontends.careers.index');
    }

    public function add(Request $request)
    {
        $form = new Career();
        $locales = $this->locales;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.title"] = 'required|string|max:255';
                    $rules["translations.{$locale}.slug"] = 'required|string|unique:career_translations,slug';
                    $rules["translations.{$locale}.content"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $career = Career::create([
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    CareerTranslation::create([
                        'career_id' => $career->id,
                        'locale' => $locale,
                        'title' => $trans['title'],
                        'slug' => $trans['slug'],
                        'content' => $trans['content'] ?? null,
                        'description' => $trans['description'] ?? null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }

                return success(message: 'Career created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.careers.form', compact('form', 'locales', 'translations'));
    }

    public function show($slug)
    {
        $locale = app()->getLocale();
        $career = Career::whereHas('translations', function ($query) use ($locale, $slug) {
            $query->where('locale', $locale)->where('slug', $slug);
        })->with('translations')->firstOrFail();
        return view('frontends.careers.show', compact('career', 'locale'));
    }

    public function edit(Request $request, $id)
    {
        $form = Career::with('translations')->findOrFail($id);
        $locales = $this->locales;

        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'title' => $translation->title,
                'slug' => $translation->slug,
                'content' => $translation->content,
                'description' => $translation->description,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.title"] = 'required|string|max:255';
                    $rules["translations.{$locale}.slug"] = 'required|string|unique:career_translations,slug,' . $form->id . ',career_id';
                    $rules["translations.{$locale}.content"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->updated_by = auth()->id();
                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    CareerTranslation::updateOrCreate(
                        ['career_id' => $form->id, 'locale' => $locale],
                        [
                            'title' => $trans['title'],
                            'slug' => $trans['slug'],
                            'content' => $trans['content'] ?? null,
                            'description' => $trans['description'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Career updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.careers.form', compact('form', 'locales', 'translations'));
    }

    public function destroy($id)
    {
        try {
            $career = Career::findOrFail($id);
            $career->deleted_by = auth()->id();
            $career->save();
            $career->delete();
            return success(message: 'Career deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
