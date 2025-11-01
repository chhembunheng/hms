@props(['checked' => false, 'label' => '', 'required' => false, 'id' => null, 'value' => null, 'name' => null])
<label class="form-check form-check-inline">
    <input type="checkbox" class="form-check-input" {{ $checked ? 'checked' : '' }} {{ $required ? 'required' : '' }} id="{{ $id ?? '' }}" name="{{ $name ?? '' }}" value="{{ $value ?? '' }}">
    <span class="form-check-label" for="{{ $id ?? '' }}">{{ $label }}</span>
</label>