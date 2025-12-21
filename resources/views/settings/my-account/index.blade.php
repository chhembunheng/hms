<x-app-layout>
    @php
        $hideBreadcrumb = true;
    @endphp
    <div class="container">
        <div class="card card-body shadow-none pt-5">
            <div class="d-flex justify-content-center">
                <div class="card-img-actions d-inline-block mb-3">
                    <img src="{{ asset('assets/images/default/male-avatar.jpg') }}" class="rounded-pill" style="width: 100px; height: 100px;">
                    <div class="card-img-actions-overlay card-img rounded-circle">
                        <a href="#" class="btn btn-outline-primary btn-icon rounded-pill">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </a>
                    </div>
                </div>
            </div>
            <h6 class="fw-semibold text-center mb-5">Welcome {{ $user->name }}</h6>
            <div class="list-group list-group-flush">
                <div class="list-group-item d-flex flex-column flex-sm-row align-items-start py-3">
                    <a href="#" class="d-block me-sm-3 mb-3 mb-sm-0">
                        <i class="fa-solid fa-user fa-xl fa-fw"></i>
                    </a>
                    <div class="flex-fill">
                        <h6 class="mb-0">
                            <a href="#">Personal Information</a>
                        </h6>
                        <ul class="list-inline list-inline-bullet text-muted mb-2">
                            <li class="list-inline-item">Manage your personal information and contact details.</li>
                        </ul>
                        <div class="row text-muted small">
                            <div class="col-sm-6">
                                <strong>Name:</strong> {{ $user->name }}<br>
                                <strong>Email:</strong> {{ $user->email }}<br>
                            </div>
                            <div class="col-sm-6">
                                <strong>Phone:</strong> {{ $user->phone ?: 'Not set' }}<br>
                                <strong>Gender:</strong> {{ ucfirst($user->gender ?: 'Not set') }}<br>
                            </div>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ms-sm-3 mt-2 mt-sm-0">
                        <a href="{{ route('settings.my-account.update-profile') }}" class="btn btn-primary btn-sm modal-remote">Manage</a>
                    </div>
                </div>
                <div class="list-group-item d-flex flex-column flex-sm-row align-items-start py-3">
                    <a href="#" class="d-block me-sm-3 mb-3 mb-sm-0">
                        <i class="fa-solid fa-shield-keyhole fa-xl fa-fw"></i>
                    </a>
                    <div class="flex-fill">
                        <h6 class="mb-0">
                            <a href="#">Authenticator App</a>
                        </h6>
                        <ul class="list-inline list-inline-bullet text-muted mb-2">
                            <li class="list-inline-item">Use Binance/Google Authenticator to protect your account and transactions.</li>
                        </ul>
                    </div>
                    <div class="flex-shrink-0 ms-sm-3 mt-2 mt-sm-0 d-flex justify-content-end align-items-center">
                        @if ($user->two_factor_secret)
                            <label class="form-check form-switch me-3">
                                <input type="checkbox" class="form-check-input form-check-input-secondary" checked>
                                <span class="form-check-label">On</span>
                            </label>
                        @else
                            <label class="form-check form-switch me-3">
                                <input type="checkbox" class="form-check-input form-check-input-secondary">
                                <span class="form-check-label">Off</span>
                            </label>
                        @endif
                        <a href="{{ route('settings.security.authenticator') }}" class="btn btn-primary btn-sm modal-remote">Manage</a>
                    </div>
                </div>
                <div class="list-group-item d-flex flex-column flex-sm-row align-items-start py-3">
                    <a href="#" class="d-block me-sm-3 mb-3 mb-sm-0">
                        <i class="fa-solid fa-envelope fa-xl fa-fw"></i>
                    </a>
                    <div class="flex-fill">
                        <h6 class="mb-0">
                            <a href="#">Email</a>
                        </h6>
                        <ul class="list-inline list-inline-bullet text-muted mb-2">
                            <li class="list-inline-item">Use your email to protect your account and transactions.</li>
                        </ul>
                    </div>
                    <div class="flex-shrink-0 ms-sm-3 mt-2 mt-sm-0">
                        <a href="#" class="btn btn-primary btn-sm">Manage</a>
                    </div>
                </div>
                <div class="list-group-item d-flex flex-column flex-sm-row align-items-start py-3">
                    <a href="#" class="d-block me-sm-3 mb-3 mb-sm-0">
                        <i class="fa-solid fa-phone-volume fa-xl fa-fw"></i>
                    </a>
                    <div class="flex-fill">
                        <h6 class="mb-0">
                            <a href="#">Phone Number</a>
                        </h6>
                        <ul class="list-inline list-inline-bullet text-muted mb-2">
                            <li class="list-inline-item">Use your phone number to protect your account and transactions.</li>
                        </ul>
                    </div>
                    <div class="flex-shrink-0 ms-sm-3 mt-2 mt-sm-0">
                        <a href="#" class="btn btn-primary btn-sm">Manage</a>
                    </div>
                </div>
                <div class="list-group-item d-flex flex-column flex-sm-row align-items-start py-3">
                    <a href="#" class="d-block me-sm-3 mb-3 mb-sm-0">
                        <i class="fa-solid fa-shield-user fa-xl fa-fw"></i>
                    </a>
                    <div class="flex-fill">
                        <h6 class="mb-0">
                            <a href="#">Login Password</a>
                        </h6>
                        <ul class="list-inline list-inline-bullet text-muted mb-2">
                            <li class="list-inline-item">Login password is used to log in to your account.</li>
                        </ul>
                    </div>
                    <div class="flex-shrink-0 ms-sm-3 mt-2 mt-sm-0">
                        <span class="text-muted me-2">Last updated: {{ $user->updated_at->diffForHumans() }}</span>
                        <a href="{{ route('settings.security.change-password') }}" class="btn btn-primary btn-sm modal-remote">Manage</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
