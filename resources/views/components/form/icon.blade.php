@props([
    'label',
    'required' => $attributes->has('required') && $attributes->get('required') === 'required',
    'value' => null,
    'iconPickerWrap' => 'icon-picker-wrap-' . Str::uuid(),
    'iconPickerInput' => 'icon-picker-input-' . Str::uuid(),
])
<div class="mb-3">
    <label class="form-label @if ($required) required @endif">{{ $label ?? '' }}</label>
    <div class="input-group icon-picker-box" id="{{ $iconPickerWrap }}">
        <span class="input-group-text"><i class="{{ $value }} fa-fw"></i></span>
        <input readonly type="text" id="{{ $iconPickerInput }}" {{ $attributes->merge(['class' => 'form-control form-control-sm open-icon-picker']) }} value="{{ $value }}" @if ($required) required @endif>
    </div>
</div>
