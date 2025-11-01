<?php

namespace App\Http\Controllers\Settings;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
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
        $user = Auth::user();
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
}
