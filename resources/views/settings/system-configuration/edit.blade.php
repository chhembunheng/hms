<x-app-layout>
<div class="container-fluid py-4">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-edit text-primary me-2"></i>
                        {{ __('global.edit_system_configuration') }}
                    </h1>
                    <p class="text-muted mt-1">{{ __('global.update_your_hotel_and_system_settings') }}</p>
                </div>
                <a href="{{ route('settings.system-configuration.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    {{ __('global.back_to_configuration') }}
                </a>
            </div>
        </div>
    </div>

    <x-form.layout :form="$configuration">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card shadow-sm border-0">
                    <!-- Card Header with Tabs -->
                    <div class="card-header bg-white border-bottom-0">
                        <ul class="nav nav-tabs card-header-tabs" id="configTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="hotel-tab" data-bs-toggle="tab" data-bs-target="#hotel" type="button" role="tab">
                                    <i class="fas fa-building me-2"></i>{{ __('global.hotel_information') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
                                    <i class="fas fa-sliders-h me-2"></i>{{ __('global.system_settings') }}
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="branding-tab" data-bs-toggle="tab" data-bs-target="#branding" type="button" role="tab">
                                    <i class="fas fa-palette me-2"></i>{{ __('global.branding') }}
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Card Body with Tab Content -->
                    <div class="card-body">
                        <div class="tab-content" id="configTabContent">
                            <!-- Hotel Information Tab -->
                            <div class="tab-pane fade show active" id="hotel" role="tabpanel">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="alert alert-info border-0 bg-light">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-info-circle text-info fa-lg"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="alert-heading mb-1">{{ __('global.hotel_details') }}</h6>
                                                    <p class="mb-0 small">{{ __('global.configure_your_hotel_name_location_and_contact_information_in_multiple_languages') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hotel Name Section -->
                                    <div class="col-12">
                                        <div class="card border-light bg-light">
                                            <div class="card-header bg-white">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-tag text-primary me-2"></i>
                                                    {{ __('global.hotel_name') }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <x-form.input :label="__('English')" name="hotel_name_en" value="{{ old('hotel_name_en', $configuration->hotel_name_en) }}" required />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <x-form.input :label="__('Khmer')" name="hotel_name_kh" value="{{ old('hotel_name_kh', $configuration->hotel_name_kh) }}" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Location Section -->
                                    <div class="col-12">
                                        <div class="card border-light bg-light">
                                            <div class="card-header bg-white">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-map-marker-alt text-success me-2"></i>
                                                    {{ __('Location') }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <x-form.textarea :label="__('English')" name="location_en" value="{{ old('location_en', $configuration->location_en) }}" rows="3" />
                                                    </div>
                                                    <div class="col-md-6">
                                                        <x-form.textarea :label="__('Khmer')" name="location_kh" value="{{ old('location_kh', $configuration->location_kh) }}" rows="3" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contact Information Section -->
                                    <div class="col-12">
                                        <div class="card border-light bg-light">
                                            <div class="card-header bg-white">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-address-book text-info me-2"></i>
                                                    {{ __('global.contact_information') }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                                            <x-form.input :label="__('Phone Number')" name="phone_number" value="{{ old('phone_number', $configuration->phone_number) }}" />
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                                            <x-form.input :label="__('Email')" name="email" type="email" value="{{ old('email', $configuration->email) }}" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- System Settings Tab -->
                            <div class="tab-pane fade" id="system" role="tabpanel">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="alert alert-warning border-0 bg-light">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-cog text-warning fa-lg"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="alert-heading mb-1">{{ __('global.system_configuration_settings') }}</h6>
                                                    <p class="mb-0 small">{{ __('global.configure_system_wide_settings_and_display_preferences') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card border-light bg-light h-100">
                                            <div class="card-header bg-white">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-desktop text-danger me-2"></i>
                                                    {{ __('global.display_settings') }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <x-form.input :label="__('System Title')" name="system_title" value="{{ old('system_title', $configuration->system_title) }}" />
                                                <div class="mt-3">
                                                    <small class="text-muted">{{ __('This title appears in the browser tab and system headers.') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card border-light bg-light h-100">
                                            <div class="card-header bg-white">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-water text-purple me-2"></i>
                                                    {{ __('global.watermark_settings') }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                <x-form.input :label="__('Watermark Title')" name="watermark_title" value="{{ old('watermark_title', $configuration->watermark_title) }}" />
                                                <div class="mt-3">
                                                    <small class="text-muted">{{ __('global.watermark_help_text') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Branding Tab -->
                            <div class="tab-pane fade" id="branding" role="tabpanel">
                                <div class="row g-4">
                                    <div class="col-12">
                                        <div class="alert alert-success border-0 bg-light">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0">
                                                    <i class="fas fa-palette text-success fa-lg"></i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="alert-heading mb-1">{{ __('global.branding_assets') }}</h6>
                                                    <p class="mb-0 small">{{ __('global.upload_your_logo_and_favicon_to_customize_the_system_appearance') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card border-light bg-light">
                                            <div class="card-header bg-white">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-image text-primary me-2"></i>
                                                    {{ __('global.logo') }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                @if($configuration->logo_path)
                                                    <div class="mb-3 text-center">
                                                        <img src="{{ Storage::url($configuration->logo_path) }}" alt="{{ __('global.current_logo') }}" class="img-thumbnail mb-2" style="max-height: 80px;">
                                                        <p class="text-muted small">{{ __('global.current_logo') }}</p>
                                                    </div>
                                                @endif

                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('global.upload_new_logo') }}</label>
                                                    <input type="file" class="form-control" name="logo" accept="image/*" id="logoInput">
                                                    <div class="form-text">
                                                        <small class="text-muted">{{ __('global.logo_help_text') }}</small>
                                                    </div>
                                                </div>

                                                <div id="logoPreview" class="d-none">
                                                    <h6>{{ __('global.preview') }}</h6>
                                                    <img id="logoPreviewImg" src="" alt="{{ __('global.logo_preview') }}" class="img-thumbnail" style="max-height: 60px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="card border-light bg-light">
                                            <div class="card-header bg-white">
                                                <h6 class="mb-0">
                                                    <i class="fas fa-star text-secondary me-2"></i>
                                                    {{ __('global.favicon') }}
                                                </h6>
                                            </div>
                                            <div class="card-body">
                                                @if($configuration->favicon_path)
                                                    <div class="mb-3 text-center">
                                                        <img src="{{ Storage::url($configuration->favicon_path) }}" alt="{{ __('global.current_favicon') }}" class="img-thumbnail mb-2" style="max-height: 32px;">
                                                        <p class="text-muted small">{{ __('global.current_favicon') }}</p>
                                                    </div>
                                                @endif

                                                <div class="mb-3">
                                                    <label class="form-label">{{ __('Upload New Favicon') }}</label>
                                                    <input type="file" class="form-control" name="favicon" accept="image/*" id="faviconInput">
                                                    <div class="form-text">
                                                        <small class="text-muted">{{ __('global.favicon_help_text') }}</small>
                                                    </div>
                                                </div>

                                                <div id="faviconPreview" class="d-none">
                                                    <h6>{{ __('global.preview') }}</h6>
                                                    <img id="faviconPreviewImg" src="" alt="{{ __('global.favicon_preview') }}" class="img-thumbnail" style="max-height: 32px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer with Actions -->
                    <div class="card-footer bg-light border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <i class="fas fa-info-circle me-1"></i>
                                {{ __('global.changes_will_take_effect_immediately_after_saving') }}
                            </div>
                            <div>
                                <a href="{{ route('settings.system-configuration.index') }}" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times me-2"></i>
                                    {{ __('global.cancel') }}
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    {{ __('global.save_configuration') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-form.layout>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Logo preview
    document.getElementById('logoInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('logoPreviewImg').src = e.target.result;
                document.getElementById('logoPreview').classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // Favicon preview
    document.getElementById('faviconInput').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('faviconPreviewImg').src = e.target.result;
                document.getElementById('faviconPreview').classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>

<style>
.nav-tabs .nav-link {
    border: none;
    border-bottom: 2px solid transparent;
    color: #6c757d;
    font-weight: 500;
}

.nav-tabs .nav-link.active {
    border-bottom-color: #007bff;
    color: #007bff;
    background-color: transparent;
}

.nav-tabs .nav-link:hover {
    border-bottom-color: #007bff;
    color: #007bff;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1) !important;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.border-purple {
    border-color: #6f42c1 !important;
}

.text-purple {
    color: #6f42c1 !important;
}
</style>
</x-app-layout>
