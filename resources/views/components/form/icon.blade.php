@props([
    'iconset' => 'fontawesome5',
    'label',
    'required' => $attributes->has('required') && $attributes->get('required') === 'required',
    'icon' => $attributes->get('value') ?? '',
    'iconPickerWrap' => 'icon-picker-wrap-' . Str::uuid(),
    'iconPickerInput' => 'icon-picker-input-' . Str::uuid(),
])
<div class="mb-3">
    <label class="form-label @if ($required) required @endif">{{ $label ?? '' }}</label>
    <div class="position-relative icon-picker-box" id="{{ $iconPickerWrap }}">
        <input type="text" id="{{ $iconPickerInput }}" {{ $attributes->merge(['class' => 'form-control form-control-sm open-icon-picker']) }} value="{{ $icon }}" @if ($required) required @endif>
        <div class="position-absolute end-0 top-50 translate-middle-y me-2">
            <button type="button" class="btn btn-light btn-sm btn-icon rounded-pill">
                <i class="fa-light fa-icons fa-fw"></i>
            </button>
        </div>
    </div>
</div>
