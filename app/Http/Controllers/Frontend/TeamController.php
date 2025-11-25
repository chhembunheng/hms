<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Models\Frontend\Team;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;
use App\Models\Frontend\TeamTranslation;
use Illuminate\Support\Facades\Validator;
use App\DataTables\Frontend\TeamDataTable;

class TeamController extends Controller
{
    protected $locales;
    protected $positions;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
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
        return $dataTable->render('frontends.teams.index');
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
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'slug' => 'nullable|string|unique:teams,slug',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.position"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $team = Team::create([
                    'image' => null,
                    'slug' => slug($request->input('slug', null)),
                    'position_id' => $request->input('position_id', null),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                if ($request->photo) {
                    $team->photo = uploadBase64($request->photo, 'uploads/teams');
                    $team->save();
                }

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    TeamTranslation::create([
                        'team_id' => $team->id,
                        'locale' => $locale,
                        'name' => $trans['name'],
                        'position_name' => $trans['position_name'] ?? null,
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

        return view('frontends.teams.form', compact('form', 'locales', 'translations', 'positions'));
    }

    public function show($slug)
    {
        $locale = app()->getLocale();
        $team = Team::whereHas('translations', function ($query) use ($locale, $slug) {
            $query->where('locale', $locale)->where('slug', $slug);
        })->with('translations')->firstOrFail();
        return view('frontends.teams.show', compact('team', 'locale'));
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
                'description' => $translation->description,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                    'slug' => 'nullable|string|unique:teams,slug,' . $form->id,
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.position"] = 'nullable|string';
                    $rules["translations.{$locale}.description"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->slug = slug($request->input('slug', null));
                $form->updated_by = auth()->id();
                $form->position_id = $request->input('position_id', null);

                if ($request->photo) {
                    $form->photo = uploadBase64($request->photo, 'uploads/teams');
                }
                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    TeamTranslation::updateOrCreate(
                        ['team_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $trans['name'],
                            'position_name' => $trans['position_name'] ?? null,
                            'description' => $trans['description'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }
                Artisan::call('cache:clear');
                return success(message: 'Team updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.teams.form', compact('form', 'locales', 'translations', 'positions'));
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
