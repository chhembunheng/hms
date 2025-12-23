<x-app-layout>
    @php
        $hideBreadcrumb = true;
    @endphp
    <div class="container">
        <div class="card card-body shadow-none pt-5">
            <div class="d-flex justify-content-center">
                <div class="card-img-actions d-inline-block mb-3">
                    @if ($user->avatar)
                        <img src="{{ asset($user->avatar) }}" class="rounded-pill" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <img src="{{ asset('assets/images/default/male-avatar.jpg') }}" class="rounded-pill" style="width: 100px; height: 100px; object-fit: cover;">
                    @endif
                    <div class="card-img-actions-overlay card-img rounded-circle">
                        <a href="{{ route('settings.my-account.update-profile') }}#avatar-edit" class="btn btn-outline-primary btn-icon rounded-pill modal-remote">
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
                            <span class="badge bg-success me-3">On</span>
                            <button type="button" class="btn btn-outline-danger btn-sm" id="disable-2fa-btn">
                                <i class="fa-solid fa-ban fa-fw"></i> Disable 2FA
                            </button>
                        @else
                            <span class="badge bg-secondary me-3">Off</span>
                            <a href="{{ route('settings.security.authenticator') }}" class="btn btn-primary btn-sm modal-remote">
                                <i class="fa-solid fa-shield fa-fw"></i> Enable 2FA
                            </a>
                        @endif
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

<script>
$(document).ready(function() {
    $('#disable-2fa-btn').on('click', function() {
        swalInit.fire({
            title: 'Disable Two-Factor Authentication',
            text: 'Are you sure you want to disable two-factor authentication? This will make your account less secure.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: '<i class="fa-solid fa-ban fa-fw"></i> &nbsp;Disable 2FA',
            cancelButtonText: '<i class="fa-solid fa-times fa-fw"></i> &nbsp;Cancel',
            buttonsStyling: false,
            customClass: {
                confirmButton: 'btn btn-flat-danger',
                cancelButton: 'btn btn-light'
            }
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: "{{ route('settings.security.disable-2fa') }}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            success(response.message);
                            setTimeout(() => {
                                window.location.reload();
                            }, 1000);
                        } else {
                            error(response.message || 'Failed to disable 2FA');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', xhr, status, error);
                        error('An error occurred while disabling 2FA');
                    }
                });
            }
        });
    });
});
</script>
