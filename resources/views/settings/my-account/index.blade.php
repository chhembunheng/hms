<x-app-layout>
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
            <h6 class="fw-semibold text-center mb-5">Welcome {{ $user->fullname }}</h6>
            <ul class="nav nav-tabs nav-tabs-underline" role="tablist">
                <li class="nav-item me-2" role="presentation">
                    <a href="#my-account-overview" class="nav-link ps-2" data-bs-toggle="tab" role="tab" aria-controls="my-account-overview" aria-selected="true">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-house fa-lg fa-fw me-2"></i>
                            <div>
                                <div class="fw-semibold">Home</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="nav-item me-2" role="presentation">
                    <a href="#my-account-personal-info" class="nav-link ps-2" data-bs-toggle="tab" role="tab" aria-controls="my-account-personal-info" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-user fa-lg fa-fw me-2"></i>
                            <div>
                                <div class="fw-semibold">Personal info</div>
                            </div>
                        </div>
                    </a>
                </li>
                <li class="nav-item me-2" role="presentation">
                    <a href="#my-account-security" class="nav-link ps-2 active" data-bs-toggle="tab" role="tab" aria-controls="my-account-security" aria-selected="false">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-key fa-lg fa-fw me-2"></i>
                            <div>
                                <div class="fw-semibold">Security</div>
                            </div>
                        </div>
                    </a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane fade" id="my-account-overview" role="tabpanel">
                    <div class="card-body">
                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="bg-success bg-opacity-10 text-success rounded-pill p-2">
                                    <i class="fa-solid fa-arrow-rotate-right fa-lg fa-fw"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <a href="#">David Linner</a> requested refund for a double card charge
                                <div class="text-muted fs-sm">12 minutes ago</div>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="bg-secondary bg-opacity-10 text-secondary rounded-pill p-2">
                                    <i class="fa-solid fa-infinity fa-lg fa-fw"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                User <a href="#">Christopher Wallace</a> is awaiting for staff reply
                                <div class="text-muted fs-sm">16 minutes ago</div>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="bg-success bg-opacity-10 text-success rounded-pill p-2">
                                    <i class="fa-solid fa-money-bill-1 fa-lg fa-fw"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                All sellers have received monthly payouts
                                <div class="text-muted fs-sm">4 hours ago</div>
                            </div>
                        </div>

                        <div class="d-flex mb-3">
                            <div class="me-3">
                                <div class="bg-danger bg-opacity-10 text-danger rounded-pill p-2">
                                    <i class="fa-solid fa-check fa-lg fa-fw"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                Ticket #43683 has been closed by <a href="#">Victoria Wilson</a>
                                <div class="text-muted fs-sm">Apr 28, 21:39</div>
                            </div>
                        </div>

                        <div class="d-flex">
                            <div class="me-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-pill p-2">
                                    <i class="fa-solid fa-comments fa-lg fa-fw"></i>
                                </div>
                            </div>
                            <div class="flex-fill">
                                <a href="#">Beatrix Diaz</a> left a new feedback for Camo backpack
                                <div class="text-muted fs-sm">Mar 30, 05:46</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="my-account-personal-info" role="tabpanel">
                    <p>This is some placeholder content the <strong>second</strong> tab's associated content</p>
                </div>
                <div class="tab-pane fade active show" id="my-account-security" role="tabpanel">
                    <div class="list-group list-group-flush">
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
        </div>
    </div>
</x-app-layout>
