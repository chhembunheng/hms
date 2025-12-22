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
                            <h4 class="text-center mb-1 text-primary">Welcome Back</h4>
                            <p class="text-center mb-3 text-muted">Sign in to continue to Hotel Management System.</p>
                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <div class="form-control-feedback form-control-feedback-start">
                                        <input type="text" class="form-control" placeholder="Enter your email" name="email" required autofocus>
                                        <div class="form-control-feedback-icon">
                                            <i class="fa-solid fa-user-circle text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <div class="form-control-feedback form-control-feedback-start">
                                        <input type="password" class="form-control" placeholder="Enter your password" name="password" required>
                                        <div class="form-control-feedback-icon">
                                            <i class="fa-solid fa-key text-muted"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-4">
                                    <button type="submit" class="btn btn-primary w-100">Log in</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
