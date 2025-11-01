<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\FaqDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Faq;
use App\Models\Frontend\FaqTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FaqController extends Controller
{
    protected $locales;
    protected $categories;

    public function __construct()
    {
        $this->locales = collect(config('app.languages'));
        $this->categories = Faq::whereNull('parent_id')->with('translations', function ($query) {
            $query->where('locale', app()->getLocale());
        })->get()->pluck('translations.0.question', 'id')->toArray();
    }

    public function index(FaqDataTable $dataTable)
    {
        return $dataTable->render('frontend.faqs.index');
    }

    public function add(Request $request)
    {
        $form = new Faq();
        $locales = $this->locales;
        $categories = $this->categories;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                    'parent_id' => 'nullable|exists:faqs,id'
                ];

                foreach ($this->locales as $locale) {
                    $rules["translations.{$locale}.question"] = 'required|string|max:500';
                    $rules["translations.{$locale}.answer"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $faq = Faq::create([
                    'sort' => $request->input('sort', 0),
                    'parent_id' => $request->input('parent_id', null),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    FaqTranslation::create([
                        'faq_id' => $faq->id,
                        'locale' => $locale,
                        'question' => $trans['question'],
                        'answer' => $trans['answer'] ?? null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }

                return success(message: 'FAQ created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.faqs.form', compact('form', 'locales', 'translations', 'categories'));
    }

    public function show($id)
    {
        $faq = Faq::with('translations')->findOrFail($id);
        return view('frontend.faqs.show', compact('faq'));
    }

    public function edit(Request $request, $id)
    {
        $form = Faq::with('translations')->findOrFail($id);
        $locales = $this->locales;
        $categories = $this->categories;

        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'question' => $translation->question,
                'answer' => $translation->answer,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'sort' => 'nullable|integer',
                    'parent_id' => 'nullable|exists:faqs,id'
                ];

                foreach ($this->locales->keys() as $locale) {
                    $rules["translations.{$locale}.question"] = 'required|string|max:500';
                    $rules["translations.{$locale}.answer"] = 'nullable|string';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->sort = $request->input('sort', 0);
                $form->parent_id = $request->input('parent_id', null);
                $form->updated_by = auth()->id();
                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    FaqTranslation::updateOrCreate(
                        ['faq_id' => $form->id, 'locale' => $locale],
                        [
                            'question' => $trans['question'],
                            'answer' => $trans['answer'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'FAQ updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontend.faqs.form', compact('form', 'locales', 'translations', 'categories'));
    }

    public function destroy($id)
    {
        try {
            $faq = Faq::findOrFail($id);
            $faq->deleted_by = auth()->id();
            $faq->save();
            $faq->delete();
            return success(message: 'FAQ deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
