<?php

namespace App\Http\Controllers\Frontend;

use App\DataTables\Frontend\ClientDataTable;
use App\Http\Controllers\Controller;
use App\Models\Frontend\Client;
use App\Models\Frontend\ClientTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\ImageRule;

class ClientController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(ClientDataTable $dataTable)
    {
        return $dataTable->render('frontends.clients.index');
    }

    public function add(Request $request)
    {
        $form = new Client();
        $locales = $this->locales;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'image' => ['nullable', new ImageRule()],
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

                $client = Client::create([
                    'image' => null,
                    'is_active' => $request->input('is_active', true),
                    'sort' => $request->input('sort', 0),
                    'created_by' => auth()->id(),
                    'updated_by' => auth()->id(),
                ]);

                if ($request->image) {
                    $client->image = uploadImage($request->image, 'clients');
                    $client->save();
                }

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    ClientTranslation::create([
                        'client_id' => $client->id,
                        'locale' => $locale,
                        'name' => $trans['name'],
                        'description' => $trans['description'] ?? null,
                        'created_by' => auth()->id(),
                        'updated_by' => auth()->id(),
                    ]);
                }

                return success(message: 'Client created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.clients.form', compact('form', 'locales', 'translations'));
    }

    public function edit(Request $request, $id)
    {
        $form = Client::with('translations')->findOrFail($id);
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
                    'image' => ['nullable', new ImageRule()],
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

                $form->is_active = $request->input('is_active', true);
                $form->sort = $request->input('sort', 0);
                $form->updated_by = auth()->id();

                if ($request->image) {
                    $form->image = uploadImage($request->image, 'clients');
                }

                $form->save();

                foreach ($this->locales->keys() as $locale) {
                    $trans = $request->input("translations.{$locale}");
                    ClientTranslation::updateOrCreate(
                        ['client_id' => $form->id, 'locale' => $locale],
                        [
                            'name' => $trans['name'],
                            'description' => $trans['description'] ?? null,
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'Client updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('frontends.clients.form', compact('form', 'locales', 'translations'));
    }

    public function destroy($id)
    {
        try {
            $client = Client::findOrFail($id);
            $client->deleted_by = auth()->id();
            $client->save();
            $client->delete();
            return success(message: 'Client deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
