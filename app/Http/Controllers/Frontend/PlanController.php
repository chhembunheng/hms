<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\PlanDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Plan;
use App\Models\Frontend\PlanTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(PlanDataTable $dataTable)
    {
        return $dataTable->render('frontend.plans.index');
    }

    public function add(Request $request)
    {
        $form = new Plan();
        $locales = $this->locales;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $plan = Plan::create([
                    'sort' => $request->input('sort', 0),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    PlanTranslation::create([
                        'plan_id' => $plan->id,
                        'locale' => $locale,
                        'name' => $trans['name'],
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }

                return success(message: 'Plan created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.plans.form', compact('form', 'locales', 'translations'));
    }

    public function show($id)
    {
        $plan = Plan::with('translations')->findOrFail($id);
        return view('frontend.plans.show', compact('plan'));
    }

    public function edit(Request $request, $id)
    {
        $form = Plan::with('translations')->findOrFail($id);
        $locales = $this->locales;

        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
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
                    PlanTranslation::updateOrCreate(
                        ['plan_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $trans['name'],
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Plan updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.plans.form', compact('form', 'locales', 'translations'));
    }

    public function destroy($id)
    {
        try {
            $plan = Plan::findOrFail($id);
            $plan->deleted_by = auth()->id();
            $plan->save();
            $plan->delete();
            return success(message: 'Plan deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
