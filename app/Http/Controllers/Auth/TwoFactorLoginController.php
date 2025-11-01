<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use PragmaRX\Google2FA\Google2FA;
use App\Http\Controllers\Controller;
use App\Models\Settings\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class TwoFactorLoginController extends Controller
{

    protected $twofa;

    public function __construct()
    {
        $this->twofa = new Google2FA();
    }

    /**
     * Handle the two-factor authentication login request.
     */
    public function create(Request $request, $token): View
    {
        return view('auth.two-factor-login', ['token' => $token]);
    }
    public function store(Request $request, $token): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'string'],
        ]);
        $claims = jwtDecode($token);
        if (!$claims) {
            return redirect()->back()->withErrors([
                'code' => trans('auth.failed'),
            ]);
        }
        $user = User::find($claims['id']);
        $code = $request->input('code');
        if (!$user || !$this->twofa->verifyKey($user->two_factor_secret, $code)) {
            return redirect()->back()->withErrors([
                'code' => trans('auth.failed'),
            ]);
        }
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended(route('dashboard.index', absolute: false));
    }
}
