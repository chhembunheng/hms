@props(['checked' => false, 'label' => '', 'required' => false, 'id' => null, 'value' => null, 'name' => null])
@php
    $inputId = $id ?? 'chk_' . ($name ? str_replace(['[', ']', '.'], '_', $name) : uniqid());
@endphp
<div class="form-check form-check-inline mt-2 d-inline-flex align-items-center">
    <input type="hidden" name="{{ $name ?? '' }}" value="0">
    <input type="checkbox" class="form-check-input mt-0" {{ $checked ? 'checked' : '' }} {{ $required ? 'required' : '' }} id="{{ $inputId }}" name="{{ $name ?? '' }}" value="1" style="cursor: pointer;">
    <label class="form-check-label ms-2 user-select-none fw-medium text-dark" for="{{ $inputId }}" style="font-size: 0.85rem; cursor: pointer;">{{ $label }}</label>
</div>