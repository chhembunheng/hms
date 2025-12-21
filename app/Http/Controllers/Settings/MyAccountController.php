<?php

namespace App\Http\Controllers\Settings;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use App\Models\Settings\User;
use PragmaRX\Google2FA\Google2FA;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MyAccountController extends Controller
{
    public function index(Request $request)
    {
        $user = User::find(Auth::id());
        return view('settings.my-account.index', compact('user'));
    }
    public function authenticator(Request $request)
    {
        $twofa = new Google2FA();
        $user = User::find(Auth::id());

        if ($request->isMethod('post')) {
            $request->validate([
                'code' => 'required|string|size:6'
            ]);

            $valid = $twofa->verifyKey($user->two_factor_secret, $request->input('code'));

            if ($valid) {
                $user->two_factor_confirmed_at = now();
                $user->save();

                return response()->json([
                    'status' => 'success',
                    'message' => 'Two-factor authentication has been enabled successfully.',
                    'delay' => 2000
                ]);
            } else {
                return errors('Invalid verification code. Please try again.');
            }
        }

        if (empty($user->two_factor_secret)) {
            $user->two_factor_secret = $twofa->generateSecretKey();
            $user->save();
        }

        $text = $twofa->getQRCodeUrl(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );
        $qrcode = createQRCode($text);

        return success([
            'body' => view('settings.my-account.security.authenticator', [
                'title' => 'Link an Authenticator',
                'user' => $user,
                'qrcode' => $qrcode
            ])->render()
        ]);
    }

    public function disableTwoFactorAuthentication(Request $request)
    {
        $user = User::find(Auth::id());

        $user->update([
            'two_factor_secret' => null,
            'two_factor_confirmed_at' => null,
        ]);

        return success(['message' => 'Two-factor authentication has been disabled successfully.']);
    }

    public function changePassword(Request $request)
    {
        $user = Auth::user();
        return success([
            'body' => view('settings.my-account.security.change-password', [
                'title' => 'Change Password',
                'user' => $user
            ])->render()
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::find(Auth::id());
        $user->load('translations');

        if ($request->isMethod('post')) {
            $rules = [
                'username' => 'required|string|max:255|unique:users,username,' . $user->id,
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'first_name.en' => 'required|string|max:255',
                'first_name.km' => 'required|string|max:255',
                'last_name.en' => 'required|string|max:255',
                'last_name.km' => 'required|string|max:255',
                'phone' => 'nullable|string|max:255',
                'address' => 'nullable|string',
                'gender' => 'nullable|string|in:male,female,other',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:51200',
            ];

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return errors(message: $validator->errors()->first());
            }

            try {
                $updateData = [
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                    'gender' => $request->input('gender'),
                    'updated_by' => $user->id,
                ];

                // Handle avatar upload
                if ($request->hasFile('avatar')) {
                    $avatar = $request->file('avatar');
                    $avatarName = time() . '_' . $user->id . '.' . $avatar->getClientOriginalExtension();
                    $avatar->move(public_path('storage/avatars'), $avatarName);
                    $updateData['avatar'] = 'storage/avatars/' . $avatarName;

                    // Delete old avatar if exists
                    if ($user->avatar && file_exists(public_path($user->avatar))) {
                        unlink(public_path($user->avatar));
                    }
                }

                $user->update($updateData);

                // Update or create translations
                $firstNames = $request->input('first_name', []);
                $lastNames = $request->input('last_name', []);

                foreach (['en', 'km'] as $locale) {
                    \App\Models\Settings\UserTranslation::updateOrCreate(
                        ['user_id' => $user->id, 'locale' => $locale],
                        [
                            'first_name' => $firstNames[$locale] ?? '',
                            'last_name' => $lastNames[$locale] ?? '',
                            'updated_by' => $user->id,
                        ]
                    );
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Profile updated successfully.',
                    'redirect' => route('settings.my-account.index'),
                    'delay' => 2000
                ]);
            } catch (\Exception $e) {
                return errors(message: $e->getMessage());
            }
        }

        return success([
            'body' => view('settings.my-account.update-profile', [
                'title' => 'Update Profile',
                'user' => $user
            ])->render()
        ]);
    }
}
