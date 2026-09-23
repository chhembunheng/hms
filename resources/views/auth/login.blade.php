@php
    $systemConfig = \App\Models\Settings\SystemConfiguration::first();
    $hotelName = $systemConfig ? $systemConfig->localized_hotel_name : config('app.name', 'Hotel Management System');
    $logoUrl = ($systemConfig && $systemConfig->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($systemConfig->logo_path))
        ? \Illuminate\Support\Facades\Storage::url($systemConfig->logo_path)
        : asset('assets/logo/logo-white.png');
    $currentLocale = app()->getLocale();
@endphp

<x-guest-layout>
    <div class="auth-form-layout auth-form-layout-fill-screen">
        <div class="auth-form-layout-foreground">
            <div class="auth-form-layout-content-container">
                <div class="auth-form-layout-card">
                    <!-- Clean card with NO stacked underlays / dropshadows behind it -->
                    <div class="auth-form-layout-card-content">
                        <div class="auth-form-layout-content p-4">
                            <!-- Top Actions: Flag Icon Language Switcher & Dark Mode Toggle -->
                            <div class="auth-card-topbar">
                                <div class="lang-switch-group">
                                    <a href="{{ route('admin.lang', 'en') }}" class="lang-switch-btn {{ $currentLocale === 'en' ? 'active' : '' }}" title="English">
                                        <img src="{{ asset('assets/icons/flags/en.svg') }}" class="lang-flag" alt="English">
                                    </a>
                                    <a href="{{ route('admin.lang', 'km') }}" class="lang-switch-btn {{ $currentLocale === 'km' ? 'active' : '' }}" title="ភាសាខ្មែរ">
                                        <img src="{{ asset('assets/icons/flags/km.svg') }}" class="lang-flag" alt="ភាសាខ្មែរ">
                                    </a>
                                </div>
                                <button type="button" class="theme-toggle-btn" onclick="toggleTheme()" title="Toggle Dark/Light Mode">
                                    <i id="theme-toggle-icon" class="fa-solid fa-moon"></i>
                                </button>
                            </div>

                            <!-- Old Logo -->
                            <div class="login-logo mt-1">
                                <div class="logo">
                                    <img src="{{ $logoUrl }}" alt="Logo">
                                </div>
                            </div>

                            <h4 class="text-center mb-1 text-primary">{{ $currentLocale === 'km' ? 'សូមស្វាគមន៍' : 'Welcome Back' }}</h4>
                            <p class="text-center mb-3 text-muted">{{ $currentLocale === 'km' ? 'សូមចូលប្រើប្រាស់ដើម្បីបន្តទៅកាន់ប្រព័ន្ធគ្រប់គ្រងសណ្ឋាគារ។' : 'Sign in to continue to Hotel Management System.' }}</p>

                            <!-- Alerts -->
                            @if ($errors->any())
                                <div class="alert alert-danger py-2 px-3 mb-3 small" role="alert">
                                    @foreach ($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            @endif

                            @if (session('status'))
                                <div class="alert alert-success py-2 px-3 mb-3 small" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <!-- Form -->
                            <form method="POST" action="{{ route('login') }}" id="loginForm">
                                @csrf

                                <!-- Normal Email/Username Input -->
                                <div class="mb-3">
                                    <label class="form-label">{{ $currentLocale === 'km' ? 'អ៊ីមែល ឬ ឈ្មោះអ្នកប្រើ' : 'Email' }}</label>
                                    <div class="form-control-feedback form-control-feedback-start">
                                        <input type="text" class="form-control @error('email') is-invalid @enderror" placeholder="{{ $currentLocale === 'km' ? 'បញ្ចូលអ៊ីមែលរបស់អ្នក' : 'Enter your email' }}" name="email" value="{{ old('email') }}" required autofocus>
                                        <div class="form-control-feedback-icon">
                                            <i class="fa-solid fa-user-circle text-muted"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Normal Password Input with Show Password Eye Toggle -->
                                <div class="mb-3">
                                    <label class="form-label">{{ $currentLocale === 'km' ? 'ពាក្យសម្ងាត់' : 'Password' }}</label>
                                    <div class="form-control-feedback form-control-feedback-start password-input-wrapper">
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" placeholder="{{ $currentLocale === 'km' ? 'បញ្ចូលពាក្យសម្ងាត់របស់អ្នក' : 'Enter your password' }}" name="password" id="passwordInput" required>
                                        <div class="form-control-feedback-icon">
                                            <i class="fa-solid fa-key text-muted"></i>
                                        </div>
                                        <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility()" aria-label="Toggle password visibility" title="{{ $currentLocale === 'km' ? 'បង្ហាញ/លាក់ពាក្យសម្ងាត់' : 'Show/Hide password' }}">
                                            <i class="fa-regular fa-eye" id="passwordEyeIcon"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Remember Me Checkbox -->
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <label class="form-check mb-0 d-flex align-items-center" style="cursor: pointer;">
                                        <input type="checkbox" name="remember" class="form-check-input me-2" id="rememberMe" {{ old('remember') ? 'checked' : '' }}>
                                        <span class="form-check-label" style="user-select: none;">
                                            {{ $currentLocale === 'km' ? 'ចងចាំខ្ញុំ' : 'Remember me' }}
                                        </span>
                                    </label>
                                </div>

                                <!-- Submit Button -->
                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-primary w-100" id="loginSubmitBtn">
                                        {{ $currentLocale === 'km' ? 'ចូលប្រព័ន្ធ' : 'Log in' }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="auth-page-footer">
                    &copy; {{ date('Y') }} {{ $hotelName }} • {{ __('Hotel Management System') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Password visibility toggle script -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('passwordEyeIcon');
            if (passwordInput && eyeIcon) {
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.className = 'fa-regular fa-eye-slash';
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.className = 'fa-regular fa-eye';
                }
            }
        }
    </script>
</x-guest-layout>
