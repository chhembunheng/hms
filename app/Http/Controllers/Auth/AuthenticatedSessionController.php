<?php

namespace App\Http\Controllers\Auth;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Auth\LoginRequest;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        if ($request->session()->has('auth.2fa:id')) {
            $token = jwtEncode(['id' => $request->session()->get('auth.2fa:id')], 300);
            return redirect()->route('two-factor.login', ['token' => $token]);
        }
        $request->session()->regenerate();

        // Set access session for non-admin users
        $user = auth()->user();
        if ($user && (!isset($user->administrator) || $user->administrator != 1)) {
            $access = [];
            foreach ($user->roles as $role) {
                foreach ($role->permissions as $permission) {
                    if ($permission->action_route) {
                        $access[$permission->action_route] = true;
                    }
                }
            }
            session(['access' => $access]);
        } else {
            session()->forget('access');
        }

        return redirect()->intended(route('dashboard.index', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
