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
    'maxWidth' => 'col-lg-10 col-xl-9',
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
    <div class="container-fluid py-3">
        <div class="row justify-content-center">
            <div class="{{ $maxWidth }}">
                <form action="{{ $resolvedAction }}" method="POST" enctype="{{ $enctype }}" id="enterprise-form">
                    @csrf
                    @if($isEdit && !in_array(strtoupper($method), ['GET', 'POST']))
                        @method($method)
                    @endif

                    <div class="card enterprise-card border-0 shadow-sm mb-4">
                        <!-- Enterprise Form Header -->
                        <div class="card-header enterprise-card-header d-flex flex-wrap justify-content-between align-items-center py-2 px-3 border-bottom bg-white">
                            <div class="d-flex align-items-center gap-2">
                                @if($icon)
                                    <div class="enterprise-header-icon rounded-circle d-flex align-items-center justify-content-center">
                                        <i data-lucide="{{ get_lucide_icon($icon) }}" style="width: 17px; height: 17px;"></i>
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-0 fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                                        {{ $title }}
                                        @if($isEdit)
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle fw-semibold rounded-pill px-2" style="font-size: 0.7rem;">
                                                <i data-lucide="pencil" style="width: 10px; height: 10px;" class="me-1"></i>{{ __('global.editing') }} {{ $id ? '#'.$id : '' }}
                                            </span>
                                        @else
                                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle fw-semibold rounded-pill px-2" style="font-size: 0.7rem;">
                                                <i data-lucide="plus" style="width: 10px; height: 10px;" class="me-1"></i>{{ __('global.new_record') }}
                                            </span>
                                        @endif
                                    </h5>
                                </div>
                            </div>

                            <a href="{{ $resolvedBackUrl }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 rounded-2">
                                <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
                                <span>{{ __('global.back_to_list') }}</span>
                            </a>
                        </div>

                        <!-- Form Fields Canvas -->
                        <div class="card-body p-4">
                            <!-- Instruction Notice -->
                            <div class="alert alert-light border border-info-subtle d-flex align-items-center py-2 px-3 mb-4 rounded-3 text-secondary" style="font-size: 0.8125rem;">
                                <i data-lucide="info" style="width: 15px; height: 15px;" class="text-primary me-2 flex-shrink-0"></i>
                                <span>{{ $instruction ?? __('form.instruction') }}</span>
                            </div>

                            {{ $slot }}
                        </div>

                        <!-- Enterprise Action Footer -->
                        <div class="card-footer bg-light-subtle d-flex justify-content-between align-items-center py-2 px-3 border-top">
                            <a href="{{ $resolvedBackUrl }}" class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1 rounded-2 px-3 text-secondary">
                                <i data-lucide="x" style="width: 14px; height: 14px;"></i>
                                <span>{{ __('global.cancel') }}</span>
                            </a>

                            <div class="d-flex align-items-center gap-2">
                                <button type="reset" class="btn btn-light btn-sm border d-inline-flex align-items-center gap-1 rounded-2 text-secondary">
                                    <i data-lucide="rotate-ccw" style="width: 14px; height: 14px;"></i>
                                    <span>{{ __('global.reset') }}</span>
                                </button>
                                <button type="submit" class="btn btn-primary btn-sm d-inline-flex align-items-center gap-1 rounded-2 px-4 fw-medium" id="btn-submit-form">
                                    <i data-lucide="save" style="width: 14px; height: 14px;"></i>
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
