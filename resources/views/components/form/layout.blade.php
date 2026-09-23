@props(['form', 'action' => null, 'method' => 'POST'])
@php
    $resolvedAction = $action ?? route(Route::currentRouteName(), $form?->id);
@endphp
<form action="{{ $resolvedAction }}" method="POST" enctype="multipart/form-data" class="enterprise-form" validate>
    @csrf
    @if(!in_array(strtoupper($method), ['GET', 'POST']))
        @method($method)
    @endif
    <div class="enterprise-form-hint d-flex align-items-center gap-2 mb-4 py-2 px-3 rounded-2">
        <i class="fa-solid fa-circle-info text-primary flex-shrink-0" style="font-size: 13px;"></i>
        <span class="text-secondary small">{!! __('form.instruction') !!}</span>
    </div>
    {{ $slot }}
</form>
