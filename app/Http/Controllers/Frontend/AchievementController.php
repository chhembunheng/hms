<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\AchievementDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Achievement;
use App\Models\Frontend\AchievementTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AchievementController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('app.languages'));
    }

    public function index(AchievementDataTable $dataTable)
    {
        return $dataTable->render('frontend.achievements.index');
    }

    public function add(Request $request)
    {
        $form = new Achievement();
        $locales = $this->locales;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.title"] = 'required|string|max:255';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $achievement = Achievement::create([
                    'sort' => $request->input('sort', 0),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    AchievementTranslation::create([
                        'achievement_id' => $achievement->id,
                        'locale' => $locale,
                        'title' => $trans['title'],
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }

                return success(message: 'Achievement created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.achievements.form', compact('form', 'locales', 'translations'));
    }

    public function show($id)
    {
        $achievement = Achievement::with('translations')->findOrFail($id);
        return view('frontend.achievements.show', compact('achievement'));
    }

    public function edit(Request $request, $id)
    {
        $form = Achievement::with('translations')->findOrFail($id);
        $locales = $this->locales;

        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'title' => $translation->title,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.title"] = 'required|string|max:255';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->sort = $request->input('sort', 0);
                $form->updated_by = auth()->id();
                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    AchievementTranslation::updateOrCreate(
                        ['achievement_id' => $form->id, 'locale' => $locale],
                        [
                            'title' => $trans['title'],
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Achievement updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.achievements.form', compact('form', 'locales', 'translations'));
    }

    public function destroy($id)
    {
        try {
            $achievement = Achievement::findOrFail($id);
            $achievement->deleted_by = auth()->id();
            $achievement->save();
            $achievement->delete();
            return success(message: 'Achievement deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
