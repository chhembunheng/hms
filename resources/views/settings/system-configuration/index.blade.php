<x-app-layout>
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-cogs text-primary me-2"></i>
                        {{ __('global.system_configuration') }}
                    </h1>
                    <p class="text-muted mt-1">{{ __('global.manage_your_hotel_and_system_settings') }}</p>
                </div>
                    <a href="{{ route('settings.system-configuration.edit') }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-edit me-2"></i>
                        {{ __('global.edit_configuration') }}
                    </a>
            </div>
        </div>
    </div>

    <!-- Configuration Cards -->
    <div class="row g-4">
        <!-- Hotel Information Card -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header text-white" style="background: #034246">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                            <i class="fas fa-building fa-lg"></i>
                        </div>
                        <div>
                        <h5 class="mb-0">{{ __('global.hotel_information') }}</h5>
                        <small>{{ __('global.basic_hotel_details_and_contact_information') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- Hotel Name -->
                        <div class="col-12">
                            <div class="border-start border-primary border-4 ps-3">
                                <label class="text-muted small mb-1">{{ __('global.hotel_name') }}</label>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold text-dark">{{ $configuration->hotel_name_en ?? __('global.not_set') }}</span>
                                    @if($configuration->hotel_name_kh)
                                        <small class="text-muted">{{ $configuration->hotel_name_kh }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="col-12">
                            <div class="border-start border-success border-4 ps-3">
                                <label class="text-muted small mb-1">{{ __('form.location') }}</label>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold text-dark">{{ $configuration->location_en ?? __('global.not_set') }}</span>
                                    @if($configuration->location_kh)
                                        <small class="text-muted">{{ $configuration->location_kh }}</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="border-start border-info border-4 ps-3">
                                <label class="text-muted small mb-1">{{ __('global.phone_number') }}</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-phone text-info me-2"></i>
                                    <span class="fw-semibold">{{ $configuration->phone_number ?? __('global.not_set') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border-start border-warning border-4 ps-3">
                                <label class="text-muted small mb-1">{{ __('global.email') }}</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-envelope text-warning me-2"></i>
                                    <span class="fw-semibold">{{ $configuration->email ?? __('global.not_set') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- System Settings Card -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header text-white" style="background: #034246">
                    <div class="d-flex align-items-center">
                        <div class="bg-white bg-opacity-20 rounded-circle p-2 me-3">
                            <i class="fas fa-sliders-h fa-lg"></i>
                        </div>
                        <div>
                        <h5 class="mb-0">{{ __('global.system_settings') }}</h5>
                        <small>{{ __('global.application_branding_and_display_settings') }}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <!-- System Title -->
                        <div class="col-md-6">
                            <div class="border-start border-danger border-4 ps-3">
                                <label class="text-muted small mb-1">{{ __('global.system_title') }}</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-tag text-danger me-2"></i>
                                    <span class="fw-semibold">{{ $configuration->system_title ?? __('global.not_set') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Watermark Title -->
                        <div class="col-md-6">
                            <div class="border-start border-purple border-4 ps-3">
                                <label class="text-muted small mb-1">{{ __('global.watermark_title') }}</label>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-water text-purple me-2"></i>
                                    <span class="fw-semibold">{{ $configuration->watermark_title ?? __('global.not_set') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Logo -->
                        <div class="col-md-6">
                            <div class="border-start border-primary border-4 ps-3">
                                <label class="text-muted small mb-1">{{ __('global.logo') }}</label>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($configuration->logo_path)
                                            <img src="{{ Storage::url($configuration->logo_path) }}" alt="Logo" class="rounded shadow-sm" style="max-height: 40px; max-width: 80px; object-fit: contain;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 80px; height: 40px;">
                                                <i class="fas fa-image text-muted"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">{{ $configuration->logo_path ? __('global.logo_set') : __('global.not_set') }}</span>
                                        @if($configuration->logo_path)
                                            <small class="text-muted">{{ __('Click edit to change') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Favicon -->
                        <div class="col-md-6">
                            <div class="border-start border-secondary border-4 ps-3">
                                <label class="text-muted small mb-1">{{ __('global.favicon') }}</label>
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        @if($configuration->favicon_path)
                                            <img src="{{ Storage::url($configuration->favicon_path) }}" alt="Favicon" class="rounded shadow-sm" style="max-height: 20px; max-width: 20px; object-fit: contain;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 20px; height: 20px;">
                                                <i class="fas fa-star text-muted" style="font-size: 10px;"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-semibold d-block">{{ $configuration->favicon_path ? __('global.favicon_set') : __('global.not_set') }}</span>
                                        @if($configuration->favicon_path)
                                            <small class="text-muted">{{ __('Click edit to change') }}</small>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-3">
                        <i class="fas fa-bolt me-2"></i>
                        {{ __('Quick Actions') }}
                    </h6>
                    <div class="d-flex flex-wrap gap-2">
                        @can('settings.system-configuration.edit')
                            <a href="{{ route('settings.system-configuration.edit') }}" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i>
                                {{ __('global.edit_configuration') }}
                            </a>
                        @endcan
                        <button type="button" class="btn btn-outline-info" onclick="window.location.reload()">
                            <i class="fas fa-sync-alt me-2"></i>
                            {{ __('Refresh') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-secondary {
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
}

.border-purple {
    border-color: #6f42c1 !important;
}

.text-purple {
    color: #6f42c1 !important;
}
</style>
</x-app-layout>
