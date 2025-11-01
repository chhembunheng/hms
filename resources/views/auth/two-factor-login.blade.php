<x-guest-layout>
    <div class="auth-form-layout auth-form-layout-fill-screen">
        <div class="auth-form-layout-foreground">
            <div class="auth-form-layout-content-container">
                <div class="auth-form-layout-card">
                    <div class="auth-form-layout-card-underlay auth-form-layout-card-underlay-z-1"></div>
                    <div class="auth-form-layout-card-underlay auth-form-layout-card-underlay-z-2">
                        <div class="auth-form-layout-card-underlay auth-form-layout-card-underlay-z-1"></div>
                    </div>
                    <div class="auth-form-layout-card-content">
                        <div class="auth-form-layout-content p-4">
                            <div class="login-logo mt-3">
                                <div class="logo">
                                    <img src="{{ asset('assets/logo/logo-white.png') }}" alt="Logo">
                                </div>
                            </div>
                            <h4 class="text-center mb-1 text-primary">Two-Factor Authentication</h4>
                            <p class="text-center mb-3 text-muted">Please enter the authentication code provided by your authenticator app.</p>
                            <form method="POST" action="{{ route('two-factor.login', [$token]) }}">
                                @csrf
                                <div class="mb-3">
                                    <label for="code" class="form-label">Authentication Code</label>
                                    <input type="text" name="code" id="code" class="form-control" required autofocus>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">Verify</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
