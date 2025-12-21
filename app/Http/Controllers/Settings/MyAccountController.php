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
        $user = Auth::user();
        return view('settings.my-account.index', compact('user'));
    }
    public function authenticator(Request $request)
    {
        $twofa = new Google2FA();
        $user = Auth::user();
        if (empty($user->two_factor_secret)) {
            $user->two_factor_secret = $twofa->generateSecretKey();
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

    public function enableTwoFactorAuthentication(Request $request)
    {
        $twofa = new Google2FA();
        $user = User::find(Auth::id());
        if ($user->two_factor_secret) {
            if ($user->two_factor_confirmed_at) {
                return redirect()->route('dashboard.index')->with('status', 'Two-factor authentication is already enabled.');
            }
            $text = $twofa->getQRCodeUrl(
                config('app.name'),
                $user->email,
                $user->two_factor_secret
            );
            return createQRCode($text);
        }
        $user->two_factor_secret = $twofa->generateSecretKey();
        $user->save();

        return redirect()->back()->with('status', 'Two-factor authentication enabled.');
    }
    public function events(Request $request)
    {
        $from_date = $request->from_date ?? now()->startOfMonth();
        $to_date = $request->to_date ?? now()->endOfYear();
        $periods = CarbonPeriod::create($from_date, CarbonInterval::days(3), $to_date);

        $lines = ['BEGIN:VCALENDAR', 'VERSION:2.0', 'CALSCALE:GREGORIAN', 'PRODID:-//Your App//EN'];
        foreach ($periods as $period) {
            $summary = '🛌👩‍⚕️ ' . $period->format('D, d M Y');
            $location = '';
            $description = '';
            $lines = array_merge($lines, [
                'BEGIN:VEVENT',
                'DTSTART:' . $period->format('Ymd\THis\Z'),
                'DTEND:' . $period->format('Ymd\THis\Z'),
                'DTSTAMP:' . now()->utc()->format('Ymd\THis\Z'),
                'UID:' . $period->format('Ymd') . '@working.com',
                'SUMMARY:' . $summary,
                'LOCATION:' . $location,
                'DESCRIPTION:' . $description,
                'STATUS:CONFIRMED',
                'TRANSP:OPAQUE',
                'END:VEVENT',
            ]);
        }
        $lines[] = 'END:VCALENDAR';
        $lines[] = '';

        return response(implode("\r\n", $lines), 200)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="db-schedule.ics"');

        return redirect()->back()->with('status', 'Two-factor authentication enabled.');
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
            ];

            $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return errors(message: $validator->errors()->first());
            }

            try {
                $user->update([
                    'username' => $request->input('username'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'address' => $request->input('address'),
                    'gender' => $request->input('gender'),
                    'updated_by' => $user->id,
                ]);

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
