<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Settings\UserTranslation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\DataTables\Settings\UserDataTable;

class UserController extends Controller
{
    protected $locales;

    public function __construct()
    {
        $this->locales = collect(config('init.languages'));
    }

    public function index(UserDataTable $dataTable)
    {
        return $dataTable->render('settings.users.index');
    }

    public function add(Request $request)
    {
        $form = new User();
        $locales = $this->locales;
        $translations = [];

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'username' => 'required|string|max:255|unique:users',
                    'email' => 'required|string|email|max:255|unique:users',
                    'password' => 'required|string|min:8|confirmed',
                    'gender' => 'nullable|string',
                    'phone' => 'nullable|string',
                    'address' => 'nullable|string',
                    'first_name.en' => 'required|string|max:255',
                    'first_name.km' => 'required|string|max:255',
                    'last_name.en' => 'required|string|max:255',
                    'last_name.km' => 'required|string|max:255',
                ];

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $user = User::create([
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                    'password' => bcrypt($request->input('password')),
                    'gender' => $request->input('gender'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                    'created_by' => auth()->id(),
                ]);

                // Create translations
                $firstNames = $request->input('first_name', []);
                $lastNames = $request->input('last_name', []);

                foreach ($this->locales->keys() as $locale) {
                    UserTranslation::create([
                        'user_id' => $user->id,
                        'locale' => $locale,
                        'first_name' => $firstNames[$locale] ?? $firstNames['en'] ?? '',
                        'last_name' => $lastNames[$locale] ?? $lastNames['en'] ?? '',
                        'created_by' => auth()->id(),
                    ]);
                }

                return success(message: 'User created successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('settings.users.form', compact('form', 'locales', 'translations'));
    }

    public function edit(Request $request, $id)
    {
        $form = User::with('translations')->findOrFail($id);
        $locales = $this->locales;

        $translations = [];
        foreach ($form->translations as $translation) {
            $translations[$translation->locale] = [
                'first_name' => $translation->first_name,
                'last_name' => $translation->last_name,
            ];
        }

        if ($request->isMethod('post')) {
            try {
                $rules = [
                    'username' => 'required|string|max:255|unique:users,username,' . $form->id,
                    'email' => 'required|string|email|max:255|unique:users,email,' . $form->id,
                    'gender' => 'nullable|string',
                    'phone' => 'nullable|string',
                    'address' => 'nullable|string',
                    'first_name.en' => 'required|string|max:255',
                    'first_name.km' => 'required|string|max:255',
                    'last_name.en' => 'required|string|max:255',
                    'last_name.km' => 'required|string|max:255',
                ];

                if ($request->filled('password')) {
                    $rules['password'] = 'required|string|min:8|confirmed';
                }

                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return errors(message: $validator->errors()->first());
                }

                $form->update([
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                    'gender' => $request->input('gender'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                    'updated_by' => auth()->id(),
                ]);

                if ($request->filled('password')) {
                    $form->password = bcrypt($request->input('password'));
                    $form->save();
                }

                // Update translations
                $firstNames = $request->input('first_name', []);
                $lastNames = $request->input('last_name', []);

                foreach ($this->locales->keys() as $locale) {
                    UserTranslation::updateOrCreate(
                        ['user_id' => $form->id, 'locale' => $locale],
                        [
                            'first_name' => $firstNames[$locale] ?? $firstNames['en'] ?? '',
                            'last_name' => $lastNames[$locale] ?? $lastNames['en'] ?? '',
                            'updated_by' => auth()->id(),
                        ]
                    );
                }

                return success(message: 'User updated successfully');
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return view('settings.users.form', compact('form', 'locales', 'translations'));
    }

    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);
            if (Auth::id() == $user->id) {
                return errors('You cannot delete your own account');
            }
            $user->delete();
            return success(message: 'User deleted successfully');
        } catch (\Exception $e) {
            return errors(message: $e->getMessage());
        }
    }
}
