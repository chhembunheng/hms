@props([
    'title',
    'icon' => null,
    'isEdit' => false,
    'id' => null,
    'backUrl' => null,
    'action' => null,
    'method' => 'POST',
    'enctype' => 'multipart/form-data',
    'instruction' => null,
    'submitLabel' => null,
    'maxWidth' => 'col-lg-8 col-xl-7',
])
@php
    $resolvedBackUrl = $backUrl;
    if (!$resolvedBackUrl) {
        $resolvedBackUrl = url()->previous();
    } elseif (Route::has($resolvedBackUrl)) {
        $resolvedBackUrl = route($resolvedBackUrl);
    }

    $resolvedAction = $action;
    if (!$resolvedAction) {
        $currentRoute = Route::currentRouteName();
        if ($currentRoute && Route::has($currentRoute)) {
            $resolvedAction = $id ? route($currentRoute, $id) : route($currentRoute);
        } else {
            $resolvedAction = url()->current();
        }
    }
@endphp

<x-app-layout>
    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="{{ $maxWidth }}">
                <form action="{{ $resolvedAction }}" method="POST" enctype="{{ $enctype }}" id="enterprise-form" class="enterprise-form" validate>
                    @csrf
                    @if($isEdit && !in_array(strtoupper($method), ['GET', 'POST']))
                        @method($method)
                    @endif

                    <div class="card enterprise-card enterprise-form-card border-0 shadow-sm mb-4">
                        <!-- Enterprise Form Header -->
                        <div class="card-header enterprise-card-header d-flex flex-wrap justify-content-between align-items-center py-2.5 px-3 px-md-4 border-bottom bg-white">
                            <div class="d-flex align-items-center gap-2.5">
                                @if($icon)
                                    <div class="enterprise-header-icon d-flex align-items-center justify-content-center">
                                        <i data-lucide="{{ get_lucide_icon($icon) }}" style="width: 17px; height: 17px;"></i>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-0 fw-bold fs-6 d-flex align-items-center gap-2" style="color: #0f172a !important;">
                                        {{ $title }}
                                        @if($isEdit && $id)
                                            <span class="badge bg-light text-primary border border-primary-subtle fw-medium rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">
                                                #{{ $id }}
                                            </span>
                                        @endif
                                    </h5>
                                </div>
                            </div>

                            <a href="{{ $resolvedBackUrl }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1.5 rounded-2 px-3 fw-medium">
                                <i class="fa-solid fa-arrow-left" style="font-size: 11px;"></i>
                                <span>{{ __('global.back_to_list') }}</span>
                            </a>
                        </div>

                        <!-- Form Fields Canvas -->
                        <div class="card-body p-3 p-md-4">
                            <!-- Instruction Notice -->
                            <div class="enterprise-form-hint d-flex align-items-center gap-2 mb-4 py-2 px-3 rounded-2">
                                <i class="fa-solid fa-circle-info text-primary flex-shrink-0" style="font-size: 13px;"></i>
                                <span class="text-secondary small">{!! $instruction ?? __('form.instruction') !!}</span>
                            </div>

                            {{ $slot }}
                        </div>

                        <!-- Enterprise Action Footer -->
                        <div class="card-footer enterprise-card-footer d-flex justify-content-between align-items-center py-2.5 px-3 px-md-4 border-top bg-light-subtle">
                            <a href="{{ $resolvedBackUrl }}" class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1.5 rounded-2 px-3 text-secondary fw-medium">
                                <i class="fa-solid fa-xmark" style="font-size: 12px;"></i>
                                <span>{{ __('global.cancel') }}</span>
                            </a>

                            <div class="d-flex align-items-center gap-2">
                                <button type="reset" class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1.5 rounded-2 px-3 text-secondary fw-medium">
                                    <i class="fa-solid fa-rotate-left" style="font-size: 11px;"></i>
                                    <span>{{ __('global.reset') }}</span>
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1.5 rounded-2 px-4 fw-medium shadow-sm" id="btn-submit-form" style="background: linear-gradient(135deg, #193f8f 0%, #2563eb 100%); border: none;">
                                    <i class="fa-solid fa-floppy-disk" style="font-size: 12px;"></i>
                                    <span>{{ $submitLabel ?? __('form.save') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
