<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Frontend\Tag;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\Frontend\TagTranslation;
use App\DataTables\Frontend\TagDataTable;
use App\Rules\ImageRule;

class TagController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(TagDataTable $dataTable)
    {
        return $dataTable->render('frontends.tags.index');
    }

    public function add(Request $request)
    {
        $form = new Tag();
        $locales = $this->locales;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                ];
                foreach ($this->locales->keys() as $locale) {
                    if ($locale === config('app.locale')) {
                        $rules["translations.{$locale}.name"] = 'required|string|max:255';
                        $rules["translations.{$locale}.description"] = 'nullable|string';
                    }
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }
                DB::transaction(function () use ($request, $form) {
                    $tag = Tag::create([
                        'icon' => $request->input('icon', null),
                        'sort' => $request->input('sort', 0),
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);

                    foreach ($this->locales->keys() as $locale) {
                        $trans = $request->input("translations.{$locale}");
                        if(empty($trans['name'])){
                            continue;
                        }
                        TagTranslation::create([
                            'tag_id' => $tag->id,
                            'locale' => $locale,
                            'name' => $trans['name'],
                            'description' => $trans['description'] ?? null,
                            'created_by' => auth()->id(),
                            'updated_by' => auth()->id(),
                        ]);
                    }
                });
                return success(message: 'Tag created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.tags.form', compact('form', 'locales', 'translations'));
    }

    public function edit($id, Request $request)
    {
        $form = Tag::findOrFail($id);
        $locales = $this->locales;
        $translations = [];

        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'description' => $translation->description,
            ];
        }

        foreach ($this->locales->keys() as $locale) {
            if (!isset($translations[$locale])) {
                $translations[$locale] = [
                    'name' => '',
                    'description' => '',
                ];
            }
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                ];
                foreach ($this->locales->keys() as $locale) {
                    if ($locale === config('app.locale')) {
                        $rules["translations.{$locale}.name"] = 'required|string|max:255';
                        $rules["translations.{$locale}.description"] = 'nullable|string';
                    }
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                DB::transaction(function () use ($request, $form) {
                    $form->icon = $request->input('icon', $form->icon);
                    $form->sort = $request->input('sort', $form->sort);
                    $form->updated_by = auth()->id();

                    $form->save();

                    foreach ($this->locales->keys() as $locale) {
                        $trans = $request->input("translations.{$locale}");
                        if(empty($trans['name'])){
                            continue;
                        }
                        $translation = $form->translations->where('locale', $locale)->first();
                        if ($translation) {
                            $translation->update([
                                'name' => $trans['name'],
                                'description' => $trans['description'] ?? null,
                                'updated_by' => auth()->id(),
                            ]);
                        } else {
                            TagTranslation::create([
                                'tag_id' => $form->id,
                                'locale' => $locale,
                                'name' => $trans['name'],
                                'description' => $trans['description'] ?? null,
                                'created_by' => auth()->id(),
                                'updated_by' => auth()->id(),
                            ]);
                        }
                    }
                });
                return success(message: 'Tag updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.tags.form', compact('form', 'locales', 'translations'));
    }

    public function destroy($id)
    {
        try {
            $tag = Tag::findOrFail($id);
            $tag->delete();
            return success(message: 'Tag deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
