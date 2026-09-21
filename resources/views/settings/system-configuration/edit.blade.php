<x-app-layout>
<div class="container-fluid py-3">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <!-- Enterprise Form Header -->
            <div class="card enterprise-card border-0 shadow-sm mb-3">
                <div class="card-header enterprise-card-header d-flex flex-wrap justify-content-between align-items-center py-2 px-3 bg-white">
                    <div class="d-flex align-items-center gap-2">
                        <div class="enterprise-header-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i data-lucide="sliders" style="width: 17px; height: 17px;"></i>
                        </div>
                        <div>
                            <h5 class="mb-0 fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                                {{ __('global.edit_system_configuration') }}
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle fw-semibold rounded-pill px-2" style="font-size: 0.7rem;">
                                    <i data-lucide="pencil" style="width: 10px; height: 10px;" class="me-1"></i>{{ __('global.editing') }}
                                </span>
                            </h5>
                            <div class="text-muted" style="font-size: 0.78rem;">
                                {{ __('global.update_your_hotel_and_system_settings') }}
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('settings.system-configuration.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 rounded-2">
                        <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
                        <span>{{ __('global.back_to_configuration') }}</span>
                    </a>
                </div>
            </div>

            <!-- Enterprise Form Container -->
            <form action="{{ route('settings.system-configuration.edit') }}" method="POST" enctype="multipart/form-data" validate>
                @csrf
                <div class="card enterprise-card border-0 shadow-sm">
                    <!-- Clean Enterprise Navigation Tabs -->
                    <div class="card-header bg-white border-bottom px-3 py-0">
                        <ul class="nav nav-tabs card-header-tabs border-0 gap-1" id="configTabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active py-2.5 px-3 d-inline-flex align-items-center gap-1.5 fw-medium text-secondary border-0 border-bottom border-2 rounded-0" id="hotel-tab" data-bs-toggle="tab" data-bs-target="#hotel" type="button" role="tab">
                                    <i data-lucide="building-2" style="width: 15px; height: 15px;"></i>
                                    <span>{{ __('global.hotel_information') }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-2.5 px-3 d-inline-flex align-items-center gap-1.5 fw-medium text-secondary border-0 border-bottom border-2 rounded-0" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
                                    <i data-lucide="sliders" style="width: 15px; height: 15px;"></i>
                                    <span>{{ __('global.system_settings') }}</span>
                                </button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link py-2.5 px-3 d-inline-flex align-items-center gap-1.5 fw-medium text-secondary border-0 border-bottom border-2 rounded-0" id="branding-tab" data-bs-toggle="tab" data-bs-target="#branding" type="button" role="tab">
                                    <i data-lucide="palette" style="width: 15px; height: 15px;"></i>
                                    <span>{{ __('global.branding') }}</span>
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Card Body with Tab Content -->
                    <div class="card-body p-4">
                        <div class="tab-content" id="configTabContent">
                            <!-- Hotel Information Tab -->
                            <div class="tab-pane fade show active" id="hotel" role="tabpanel">
                                <div class="alert alert-light border border-info-subtle d-flex align-items-center py-2 px-3 mb-4 rounded-3 text-secondary" style="font-size: 0.8125rem;">
                                    <i data-lucide="info" style="width: 16px; height: 16px;" class="text-primary me-2 flex-shrink-0"></i>
                                    <span>{{ __('global.configure_your_hotel_name_location_and_contact_information_in_multiple_languages') }}</span>
                                </div>

                                <div class="row g-3">
                                    <!-- Hotel Name Section -->
                                    <div class="col-md-6">
                                        <x-form.input :label="__('global.hotel_name') . ' (' . __('English') . ')'" name="hotel_name_en" value="{{ old('hotel_name_en', $configuration->hotel_name_en) }}" required />
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.input :label="__('global.hotel_name') . ' (' . __('Khmer') . ')'" name="hotel_name_kh" value="{{ old('hotel_name_kh', $configuration->hotel_name_kh) }}" />
                                    </div>

                                    <!-- Location Section -->
                                    <div class="col-md-6">
                                        <x-form.textarea :label="__('form.location') . ' (' . __('English') . ')'" name="location_en" value="{{ old('location_en', $configuration->location_en) }}" rows="3" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.textarea :label="__('form.location') . ' (' . __('Khmer') . ')'" name="location_kh" value="{{ old('location_kh', $configuration->location_kh) }}" rows="3" />
                                    </div>

                                    <!-- Contact Information -->
                                    <div class="col-md-6">
                                        <x-form.input :label="__('global.phone_number')" name="phone_number" value="{{ old('phone_number', $configuration->phone_number) }}" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-form.input :label="__('global.email')" name="email" type="email" value="{{ old('email', $configuration->email) }}" />
                                    </div>
                                </div>
                            </div>

                            <!-- System Settings Tab -->
                            <div class="tab-pane fade" id="system" role="tabpanel">
                                <div class="alert alert-light border border-info-subtle d-flex align-items-center py-2 px-3 mb-4 rounded-3 text-secondary" style="font-size: 0.8125rem;">
                                    <i data-lucide="info" style="width: 16px; height: 16px;" class="text-primary me-2 flex-shrink-0"></i>
                                    <span>{{ __('global.configure_system_wide_settings_and_display_preferences') }}</span>
                                </div>

                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-light-subtle h-100">
                                            <x-form.input :label="__('global.system_title')" name="system_title" value="{{ old('system_title', $configuration->system_title) }}" />
                                            <div class="text-muted mt-2" style="font-size: 0.75rem;">
                                                <i data-lucide="help-circle" style="width: 12px; height: 12px;" class="me-1"></i>
                                                {{ __('This title appears in the browser tab and system headers.') }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-light-subtle h-100">
                                            <x-form.input :label="__('global.watermark_title')" name="watermark_title" value="{{ old('watermark_title', $configuration->watermark_title) }}" />
                                            <div class="text-muted mt-2" style="font-size: 0.75rem;">
                                                <i data-lucide="help-circle" style="width: 12px; height: 12px;" class="me-1"></i>
                                                {{ __('global.watermark_help_text') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Branding Tab -->
                            <div class="tab-pane fade" id="branding" role="tabpanel">
                                <div class="alert alert-light border border-info-subtle d-flex align-items-center py-2 px-3 mb-4 rounded-3 text-secondary" style="font-size: 0.8125rem;">
                                    <i data-lucide="info" style="width: 16px; height: 16px;" class="text-primary me-2 flex-shrink-0"></i>
                                    <span>{{ __('global.upload_your_logo_and_favicon_to_customize_the_system_appearance') }}</span>
                                </div>

                                <div class="row g-4">
                                    <!-- Logo Upload -->
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-white h-100">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <i data-lucide="image" class="text-primary" style="width: 18px; height: 18px;"></i>
                                                <h6 class="mb-0 fw-bold text-dark">{{ __('global.logo') }}</h6>
                                            </div>

                                            <div class="mb-3 text-center p-3 border rounded-2 bg-light-subtle">
                                                @if($configuration->logo_path && Storage::disk('public')->exists($configuration->logo_path))
                                                    <img id="currentLogoImg" src="{{ Storage::url($configuration->logo_path) }}" alt="{{ __('global.current_logo') }}" class="img-fluid mb-2" style="max-height: 64px; object-fit: contain;">
                                                    <div class="text-muted small">{{ __('global.current_logo') }}</div>
                                                @else
                                                    <div class="text-muted py-2">
                                                        <i data-lucide="image" style="width: 32px; height: 32px;" class="opacity-40"></i>
                                                        <div class="small mt-1">{{ __('No Logo Uploaded') }}</div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">{{ __('global.upload_new_logo') }}</label>
                                                <input type="file" class="form-control form-control-sm" name="logo" accept="image/*" id="logoInput">
                                                <div class="text-muted mt-1" style="font-size: 0.72rem;">{{ __('global.logo_help_text') }}</div>
                                            </div>

                                            <div id="logoPreview" class="d-none mt-2 p-2 border rounded-2 bg-light text-center">
                                                <div class="text-muted small mb-1 fw-medium">{{ __('global.preview') }}</div>
                                                <img id="logoPreviewImg" src="" alt="{{ __('global.logo_preview') }}" class="img-fluid" style="max-height: 50px;">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Favicon Upload -->
                                    <div class="col-md-6">
                                        <div class="p-3 border rounded-3 bg-white h-100">
                                            <div class="d-flex align-items-center gap-2 mb-3">
                                                <i data-lucide="globe" class="text-primary" style="width: 18px; height: 18px;"></i>
                                                <h6 class="mb-0 fw-bold text-dark">{{ __('global.favicon') }}</h6>
                                            </div>

                                            <div class="mb-3 text-center p-3 border rounded-2 bg-light-subtle">
                                                @if($configuration->favicon_path && Storage::disk('public')->exists($configuration->favicon_path))
                                                    <img id="currentFaviconImg" src="{{ Storage::url($configuration->favicon_path) }}" alt="{{ __('global.current_favicon') }}" class="mb-2" style="width: 32px; height: 32px; object-fit: contain;">
                                                    <div class="text-muted small">{{ __('global.current_favicon') }}</div>
                                                @else
                                                    <div class="text-muted py-2">
                                                        <i data-lucide="globe" style="width: 32px; height: 32px;" class="opacity-40"></i>
                                                        <div class="small mt-1">{{ __('No Favicon Uploaded') }}</div>
                                                    </div>
                                                @endif
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">{{ __('Upload New Favicon') }}</label>
                                                <input type="file" class="form-control form-control-sm" name="favicon" accept="image/*,.ico" id="faviconInput">
                                                <div class="text-muted mt-1" style="font-size: 0.72rem;">{{ __('global.favicon_help_text') }}</div>
                                            </div>

                                            <div id="faviconPreview" class="d-none mt-2 p-2 border rounded-2 bg-light text-center">
                                                <div class="text-muted small mb-1 fw-medium">{{ __('global.preview') }}</div>
                                                <img id="faviconPreviewImg" src="" alt="{{ __('global.favicon_preview') }}" style="width: 24px; height: 24px;">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enterprise Card Footer Actions -->
                    <div class="card-footer bg-light-subtle d-flex justify-content-between align-items-center py-2 px-3 border-top">
                        <a href="{{ route('settings.system-configuration.index') }}" class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1 rounded-2 px-3 text-secondary">
                            <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                            <span>{{ __('global.cancel') }}</span>
                        </a>

                        <button type="submit" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 rounded-2 px-4 fw-medium" id="btn-submit-form">
                            <i data-lucide="save" style="width: 14px; height: 14px;"></i>
                            <span>{{ __('global.save_configuration') }}</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.initLucideIcons === 'function') {
        window.initLucideIcons();
    }

    const logoInput = document.getElementById('logoInput');
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('logoPreviewImg');
                    if (img) img.src = e.target.result;
                    const preview = document.getElementById('logoPreview');
                    if (preview) preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    }

    const faviconInput = document.getElementById('faviconInput');
    if (faviconInput) {
        faviconInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.getElementById('faviconPreviewImg');
                    if (img) img.src = e.target.result;
                    const preview = document.getElementById('faviconPreview');
                    if (preview) preview.classList.remove('d-none');
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
</x-app-layout>
