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
                            <h4 class="text-center mb-4 text-primary">Reset your password</h4>
                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf
                                <div class="mb-3">
                                    <div class="form-control-feedback form-control-feedback-start">
                                        <input type="text" class="form-control" placeholder="Enter your email address" name="email" required autofocus>
                                        <div class="form-control-feedback-icon">
                                            <i class="fa-solid fa-envelope text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
                                </div>
                            </form>
                            <p class="text-center mb-0 text-muted"><a href="/login">Back to Login</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
