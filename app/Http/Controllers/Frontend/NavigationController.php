<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\NavigationDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Navigation;
use App\Models\Frontend\NavigationTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NavigationController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(NavigationDataTable $dataTable)
    {
        return $dataTable->render('frontends.navigations.index');
    }

    public function add(Request $request)
    {
        $form = new Navigation();
        $locales = $this->locales;
        $translations = [];
        $navigations = $this->getNavigationsForDropdown();

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'parent_id' => 'nullable|exists:navigations,id',
                    'url' => 'nullable|string|max:255',
                    'icon' => 'nullable|string|max:100',
                    'sort' => 'nullable|integer',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.label"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $navigation = Navigation::create([
                    'parent_id' => $request->input('parent_id'),
                    'url' => $request->input('url'),
                    'icon' => $request->input('icon'),
                    'sort' => $request->input('sort', 0),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    NavigationTranslation::create([
                        'navigation_id' => $navigation->id,
                        'locale' => $locale,
                        'name' => $trans['name'],
                        'label' => $trans['label'] ?? null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }
                return success(message: 'Navigation created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.navigations.form', compact('form', 'locales', 'translations', 'navigations'));
    }

    public function edit(Request $request, $id)
    {
        $form = Navigation::with('translations')->findOrFail($id);
        $locales = $this->locales;
        $navigations = $this->getNavigationsForDropdown($id);

        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'name' => $translation->name,
                'label' => $translation->label,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'parent_id' => 'nullable|exists:navigations,id',
                    'url' => 'nullable|string|max:255',
                    'icon' => 'nullable|string|max:100',
                    'sort' => 'nullable|integer',
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.name"] = 'required|string|max:255';
                    $rules["translations.{$locale}.label"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->parent_id = $request->input('parent_id');
                $form->url = $request->input('url');
                $form->icon = $request->input('icon');
                $form->sort = $request->input('sort', 0);
                $form->updated_by = auth()->id();
                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    NavigationTranslation::updateOrCreate(
                        ['navigation_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $trans['name'],
                            'label' => $trans['label'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Navigation updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.navigations.form', compact('form', 'locales', 'translations', 'navigations'));
    }

    public function destroy($id)
    {
        try {
            $navigation = Navigation::findOrFail($id);
            $navigation->deleted_by = auth()->id();
            $navigation->save();
            $navigation->delete();

            return success(message: 'Navigation deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }

    /**
     * Get navigations for dropdown selection
     * Excludes the current navigation being edited (to prevent self-referencing)
     */
    protected function getNavigationsForDropdown($excludeId = null)
    {
        $locale = app()->getLocale();
        $query = Navigation::with('translations')
            ->whereNull('deleted_at')
            ->orderBy('sort', 'asc');

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        $navigations = $query->get()->mapWithKeys(function ($nav) use ($locale) {
            return [$nav->id => $nav->getName($locale)];
        });
        dd($navigations);

        return $navigations->toArray();
    }
}
