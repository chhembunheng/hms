<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\TeamDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Team;
use App\Models\Frontend\TeamTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeamController extends Controller
{
    protected $locales;
    protected $positions;

    public function __construct()
    {
        $this->locales = collect(config('app.languages'));
        $this->positions = [
            1 => 'Assistant Director',
            2 => 'Accountant',
            3 => 'Business Analyst',
            4 => 'Customer Support',
            5 => 'Designer',
            6 => 'Developer',
            7 => 'Director',
            8 => 'Finance Analyst',
            9 => 'HR Specialist',
            10 => 'Manager',
            11 => 'Marketing Specialist',
            12 => 'Product Manager',
            13 => 'Sales Representative',
            14 => 'Senior Developer',
            15 => 'UI/UX Designer',
        ];
    }

    public function index(TeamDataTable $dataTable)
    {
        return $dataTable->render('frontend.teams.index');
    }

    public function add(Request $request)
    {
        $form = new Team();
        $locales = $this->locales;
        $positions = $this->positions;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.slug"] = 'required|string|unique:team_translations,slug';
                    $rules["translations.{$locale}.position"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $team = Team::create([
                    'image' => null,
                    'sort' => $request->input('sort', 0),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                if ($request->hasFile('image')) {
                    $team->image = uploadFile($request->file('image'), 'teams');
                    $team->save();
                }

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    TeamTranslation::create([
                        'team_id' => $team->id,
                        'locale' => $locale,
                        'name' => $trans['name'],
                        'slug' => $trans['slug'],
                        'position' => $trans['position'] ?? null,
                        'description' => $trans['description'] ?? null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }

                return success(message: 'Team created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.teams.form', compact('form', 'locales', 'translations', 'positions'));
    }

    public function show($slug)
    {
        $locale = app()->getLocale();
        $team = Team::whereHas('translations', function ($query) use ($locale, $slug) {
            $query->where('locale', $locale)->where('slug', $slug);
        })->with('translations')->firstOrFail();
        return view('frontend.teams.show', compact('team', 'locale'));
    }

    public function edit(Request $request, $id)
    {
        $form = Team::with('translations')->findOrFail($id);
        $locales = $this->locales;
        $positions = $this->positions;
        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'slug' => $translation->slug,
                'position' => $translation->position,
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
                    $rules["translations.{$locale}.slug"] = 'required|string|unique:team_translations,slug,' . $form->id . ',team_id';
                    $rules["translations.{$locale}.position"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->sort = $request->input('sort', 0);
                $form->updated_by = auth()->id();

                if ($request->hasFile('image')) {
                    $form->image = uploadFile($request->file('image'), 'teams');
                }

                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    TeamTranslation::updateOrCreate(
                        ['team_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $trans['name'],
                            'slug' => $trans['slug'],
                            'position' => $trans['position'] ?? null,
                            'description' => $trans['description'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Team updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.teams.form', compact('form', 'locales', 'translations', 'positions'));
    }

    public function destroy($id)
    {
        try {
            $team = Team::findOrFail($id);
            $team->deleted_by = auth()->id();
            $team->save();
            $team->delete();
            return success(message: 'Team deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
