@props([
    'disabled' => false,
    'label' => null,
    'value' => '',
    'editor' => false,
    'cropper' => false,
    'title' => null,
    'image' => null,
    'textarea' => true,
    'id' => null,
    'required' => $attributes->has('required') && $attributes->get('required') === 'required',
])
@php
    $textareaId = $id ?? $attributes->get('name');
    $editor = $editor ? 'editor' : '';
@endphp
<div class="mb-3">
    <label class="form-label @if ($required) required @endif" @if ($textareaId) for="{{ $textareaId }}" @endif>{{ $label }}</label>
    @if ($textarea)
        <textarea @disabled($disabled) {{ $attributes->merge(['class' => "form-control {$editor}"]) }} id="{{ $textareaId }}" @if ($required) required @endif>{{ old($attributes->get('name'), $value) }}</textarea>
    @endif
</div>