<x-app-layout>
<div class="container-fluid py-3">
    <!-- Enterprise Page Header -->
    <div class="card enterprise-card border-0 shadow-sm mb-3">
        <div class="card-header enterprise-card-header d-flex flex-wrap justify-content-between align-items-center py-2 px-3 bg-white">
            <div class="d-flex align-items-center gap-2">
                <div class="enterprise-header-icon rounded-circle d-flex align-items-center justify-content-center">
                    <i data-lucide="sliders" style="width: 17px; height: 17px;"></i>
                </div>
                <div>
                    <h5 class="mb-0 fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                        {{ __('global.system_configuration') }}
                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle fw-medium rounded-pill px-2" style="font-size: 0.72rem;">
                            <span class="d-inline-block rounded-circle bg-success me-1" style="width: 6px; height: 6px;"></span>
                            {{ __('Active') }}
                        </span>
                    </h5>
                    <div class="text-muted" style="font-size: 0.78rem;">
                        {{ __('global.manage_your_hotel_and_system_settings') }}
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button type="button" class="btn-enterprise-action btn-enterprise-icon-btn" onclick="window.location.reload()" title="{{ __('Refresh') }}">
                    <i data-lucide="rotate-cw" style="width: 14px; height: 14px;"></i>
                </button>
                @can('settings.system-configuration.edit')
                    <a href="{{ route('settings.system-configuration.edit') }}" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5 px-3 py-1.5 rounded-2 shadow-sm fw-medium">
                        <i data-lucide="pencil" style="width: 14px; height: 14px;"></i>
                        <span>{{ __('global.edit_configuration') }}</span>
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Enterprise Hero Identity Card -->
    <div class="card enterprise-card border-0 shadow-sm mb-3">
        <div class="card-body p-3 p-md-4">
            <div class="row align-items-center g-3">
                <!-- Logo Preview Box -->
                <div class="col-auto">
                    <div class="d-flex align-items-center justify-content-center border rounded-3 p-2 bg-light-subtle shadow-2xs position-relative" style="width: 110px; height: 80px; background-color: #fafbfd;">
                        @if($configuration->logo_path && Storage::disk('public')->exists($configuration->logo_path))
                            <img src="{{ Storage::url($configuration->logo_path) }}" alt="{{ $configuration->hotel_name_en }}" class="img-fluid" style="max-height: 64px; max-width: 94px; object-fit: contain;">
                        @else
                            <div class="text-center text-muted">
                                <i data-lucide="hotel" class="text-secondary opacity-50" style="width: 28px; height: 28px;"></i>
                                <div class="text-uppercase fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">{{ __('No Logo') }}</div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Hotel Primary Identity & Pills -->
                <div class="col">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                        <h4 class="mb-0 fw-bold text-dark fs-5">
                            {{ $configuration->hotel_name_en ?? __('global.not_set') }}
                        </h4>
                        @if($configuration->hotel_name_kh)
                            <span class="text-muted fs-6 fw-normal">({{ $configuration->hotel_name_kh }})</span>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap align-items-center gap-2 mt-2" style="font-size: 0.8125rem;">
                        @if($configuration->phone_number)
                            <span class="badge bg-light text-secondary border fw-normal d-inline-flex align-items-center gap-1.5 py-1 px-2.5 rounded-2">
                                <i data-lucide="phone" class="text-primary" style="width: 13px; height: 13px;"></i>
                                <a href="tel:{{ $configuration->phone_number }}" class="text-secondary text-decoration-none">{{ $configuration->phone_number }}</a>
                            </span>
                        @endif

                        @if($configuration->email)
                            <span class="badge bg-light text-secondary border fw-normal d-inline-flex align-items-center gap-1.5 py-1 px-2.5 rounded-2">
                                <i data-lucide="mail" class="text-primary" style="width: 13px; height: 13px;"></i>
                                <a href="mailto:{{ $configuration->email }}" class="text-secondary text-decoration-none">{{ $configuration->email }}</a>
                            </span>
                        @endif

                        @if($configuration->system_title)
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle fw-medium d-inline-flex align-items-center gap-1.5 py-1 px-2.5 rounded-2">
                                <i data-lucide="app-window" style="width: 13px; height: 13px;"></i>
                                <span>{{ $configuration->system_title }}</span>
                            </span>
                        @endif

                        @if($configuration->watermark_title)
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle fw-medium d-inline-flex align-items-center gap-1.5 py-1 px-2.5 rounded-2">
                                <i data-lucide="stamp" style="width: 13px; height: 13px;"></i>
                                <span>{{ $configuration->watermark_title }}</span>
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Last Updated Badge -->
                <div class="col-12 col-md-auto text-md-end pt-2 pt-md-0 border-top border-md-top-0">
                    <div class="text-muted" style="font-size: 0.75rem;">
                        <i data-lucide="clock" style="width: 12px; height: 12px;" class="me-1"></i>
                        {{ __('Last Updated') }}:
                        <span class="fw-semibold text-dark">
                            {{ $configuration->updated_at ? $configuration->updated_at->format('d M Y, H:i') : __('global.not_set') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Configuration Details Grid -->
    <div class="row g-3">
        <!-- Hotel Information Card -->
        <div class="col-lg-6">
            <div class="card enterprise-card border-0 shadow-sm h-100">
                <div class="card-header enterprise-card-header d-flex align-items-center justify-content-between py-2 px-3 bg-white">
                    <div class="d-flex align-items-center gap-2">
                        <div class="enterprise-header-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i data-lucide="building-2" style="width: 16px; height: 16px;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('global.hotel_information') }}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ __('global.basic_hotel_details_and_contact_information') }}</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-0">
                    <div class="list-group list-group-flush border-top-0">
                        <!-- Hotel Name (EN) -->
                        <div class="list-group-item d-flex align-items-start gap-3 py-2.5 px-3">
                            <div class="text-muted pt-0.5" style="width: 20px;">
                                <i data-lucide="building" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted" style="font-size: 0.75rem; font-weight: 500;">{{ __('global.hotel_name') }} ({{ __('English') }})</div>
                                <div class="fw-semibold text-dark fs-6">{{ $configuration->hotel_name_en ?? __('global.not_set') }}</div>
                            </div>
                        </div>

                        <!-- Hotel Name (KH) -->
                        <div class="list-group-item d-flex align-items-start gap-3 py-2.5 px-3 bg-light-subtle">
                            <div class="text-muted pt-0.5" style="width: 20px;">
                                <i data-lucide="languages" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted" style="font-size: 0.75rem; font-weight: 500;">{{ __('global.hotel_name') }} ({{ __('Khmer') }})</div>
                                <div class="fw-semibold text-dark">{{ $configuration->hotel_name_kh ?? __('global.not_set') }}</div>
                            </div>
                        </div>

                        <!-- Location (EN) -->
                        <div class="list-group-item d-flex align-items-start gap-3 py-2.5 px-3">
                            <div class="text-muted pt-0.5" style="width: 20px;">
                                <i data-lucide="map-pin" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted" style="font-size: 0.75rem; font-weight: 500;">{{ __('form.location') }} ({{ __('English') }})</div>
                                <div class="text-dark">{{ $configuration->location_en ?? __('global.not_set') }}</div>
                            </div>
                        </div>

                        <!-- Location (KH) -->
                        <div class="list-group-item d-flex align-items-start gap-3 py-2.5 px-3 bg-light-subtle">
                            <div class="text-muted pt-0.5" style="width: 20px;">
                                <i data-lucide="map-pin" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted" style="font-size: 0.75rem; font-weight: 500;">{{ __('form.location') }} ({{ __('Khmer') }})</div>
                                <div class="text-dark">{{ $configuration->location_kh ?? __('global.not_set') }}</div>
                            </div>
                        </div>

                        <!-- Phone Number -->
                        <div class="list-group-item d-flex align-items-start gap-3 py-2.5 px-3">
                            <div class="text-muted pt-0.5" style="width: 20px;">
                                <i data-lucide="phone" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted" style="font-size: 0.75rem; font-weight: 500;">{{ __('global.phone_number') }}</div>
                                @if($configuration->phone_number)
                                    <a href="tel:{{ $configuration->phone_number }}" class="fw-semibold text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                                        {{ $configuration->phone_number }}
                                        <i data-lucide="external-link" style="width: 12px; height: 12px;"></i>
                                    </a>
                                @else
                                    <span class="text-muted">{{ __('global.not_set') }}</span>
                                @endif
                            </div>
                        </div>

                        <!-- Email Address -->
                        <div class="list-group-item d-flex align-items-start gap-3 py-2.5 px-3 bg-light-subtle">
                            <div class="text-muted pt-0.5" style="width: 20px;">
                                <i data-lucide="mail" style="width: 16px; height: 16px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="text-muted" style="font-size: 0.75rem; font-weight: 500;">{{ __('global.email') }}</div>
                                @if($configuration->email)
                                    <a href="mailto:{{ $configuration->email }}" class="fw-semibold text-primary text-decoration-none d-inline-flex align-items-center gap-1">
                                        {{ $configuration->email }}
                                        <i data-lucide="external-link" style="width: 12px; height: 12px;"></i>
                                    </a>
                                @else
                                    <span class="text-muted">{{ __('global.not_set') }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Branding & Display Settings Card -->
        <div class="col-lg-6">
            <div class="card enterprise-card border-0 shadow-sm h-100">
                <div class="card-header enterprise-card-header d-flex align-items-center justify-content-between py-2 px-3 bg-white">
                    <div class="d-flex align-items-center gap-2">
                        <div class="enterprise-header-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i data-lucide="palette" style="width: 16px; height: 16px;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('global.system_settings') }}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ __('global.application_branding_and_display_settings') }}</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3">
                    <div class="row g-3">
                        <!-- System Title -->
                        <div class="col-sm-6">
                            <div class="p-3 border rounded-3 bg-light-subtle h-100">
                                <div class="d-flex align-items-center gap-2 text-muted mb-1" style="font-size: 0.75rem; font-weight: 500;">
                                    <i data-lucide="app-window" class="text-primary" style="width: 15px; height: 15px;"></i>
                                    <span>{{ __('global.system_title') }}</span>
                                </div>
                                <div class="fw-bold text-dark fs-6 mb-1">
                                    {{ $configuration->system_title ?? __('global.not_set') }}
                                </div>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">
                                    {{ __('Displayed in browser window title & navigation') }}
                                </small>
                            </div>
                        </div>

                        <!-- Watermark Title -->
                        <div class="col-sm-6">
                            <div class="p-3 border rounded-3 bg-light-subtle h-100">
                                <div class="d-flex align-items-center gap-2 text-muted mb-1" style="font-size: 0.75rem; font-weight: 500;">
                                    <i data-lucide="stamp" class="text-primary" style="width: 15px; height: 15px;"></i>
                                    <span>{{ __('global.watermark_title') }}</span>
                                </div>
                                <div class="fw-bold text-dark fs-6 mb-1">
                                    {{ $configuration->watermark_title ?? __('global.not_set') }}
                                </div>
                                <small class="text-muted d-block" style="font-size: 0.72rem;">
                                    {{ __('Printed in background of official documents') }}
                                </small>
                            </div>
                        </div>

                        <!-- Hotel Logo Asset Preview -->
                        <div class="col-12">
                            <div class="p-3 border rounded-3 bg-white">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i data-lucide="image" class="text-primary" style="width: 16px; height: 16px;"></i>
                                        <span class="fw-bold text-dark" style="font-size: 0.84rem;">{{ __('global.logo') }}</span>
                                    </div>
                                    @if($configuration->logo_path)
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill px-2" style="font-size: 0.7rem;">
                                            <i data-lucide="check" style="width: 10px; height: 10px;" class="me-1"></i>{{ __('global.logo_set') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2" style="font-size: 0.7rem;">
                                            {{ __('global.not_set') }}
                                        </span>
                                    @endif
                                </div>

                                <div class="d-flex align-items-center gap-3 p-2 border rounded-2 bg-light-subtle">
                                    <div class="d-flex align-items-center justify-content-center border rounded bg-white p-2" style="width: 90px; height: 60px;">
                                        @if($configuration->logo_path && Storage::disk('public')->exists($configuration->logo_path))
                                            <img src="{{ Storage::url($configuration->logo_path) }}" alt="Logo" style="max-height: 48px; max-width: 76px; object-fit: contain;">
                                        @else
                                            <div class="text-muted text-center">
                                                <i data-lucide="image" style="width: 22px; height: 22px;" class="opacity-40"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1" style="font-size: 0.78rem;">
                                        <div class="fw-semibold text-dark mb-0.5">
                                            {{ $configuration->logo_path ? basename($configuration->logo_path) : __('No image uploaded') }}
                                        </div>
                                        <div class="text-muted" style="font-size: 0.72rem;">
                                            {{ __('Recommended size: 240x80px (PNG or SVG with transparent background)') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Favicon Asset Preview with Mini Browser Mockup -->
                        <div class="col-12">
                            <div class="p-3 border rounded-3 bg-white">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i data-lucide="globe" class="text-primary" style="width: 16px; height: 16px;"></i>
                                        <span class="fw-bold text-dark" style="font-size: 0.84rem;">{{ __('global.favicon') }}</span>
                                    </div>
                                    @if($configuration->favicon_path)
                                        <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle rounded-pill px-2" style="font-size: 0.7rem;">
                                            <i data-lucide="check" style="width: 10px; height: 10px;" class="me-1"></i>{{ __('global.favicon_set') }}
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2" style="font-size: 0.7rem;">
                                            {{ __('global.not_set') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Browser Tab Simulation -->
                                <div class="p-2 border rounded-2 bg-light-subtle d-flex flex-column gap-2">
                                    <div class="d-flex align-items-center gap-2 bg-white px-3 py-1.5 rounded-2 border shadow-2xs" style="max-width: 280px; font-size: 0.75rem;">
                                        @if($configuration->favicon_path && Storage::disk('public')->exists($configuration->favicon_path))
                                            <img src="{{ Storage::url($configuration->favicon_path) }}" alt="Favicon" style="width: 16px; height: 16px; object-fit: contain;">
                                        @else
                                            <i data-lucide="globe" style="width: 14px; height: 14px;" class="text-muted"></i>
                                        @endif
                                        <span class="fw-medium text-dark text-truncate">{{ $configuration->system_title ?? 'HMS' }} - Hotel Management System</span>
                                        <i data-lucide="x" style="width: 11px; height: 11px;" class="text-muted ms-auto"></i>
                                    </div>
                                    <div class="text-muted px-1" style="font-size: 0.72rem;">
                                        {{ __('Recommended format: 32x32px or 64x64px (ICO or PNG)') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
