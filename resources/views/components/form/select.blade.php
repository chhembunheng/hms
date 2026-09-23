@props(['label', 'options' => [], 'value' => null, 'selected' => null])
@php
    $isMulti = $attributes->has('multiple');
@endphp
<div class="mb-3">
    <label class="form-label @if ($attributes->has('required')) required @endif">{{ $label ?? '' }}</label>
    <select {{ $attributes->merge([
        'class' => 'form-select form-select-sm ' . ($isMulti ? 'multiple-select' : 'select2'),
        'data-placeholder' => $attributes->get('placeholder', __('form.select_option')),
        'data-allow-clear' => 'true'
    ]) }}>
        @if(!$isMulti) <option value=""></option> @endif
        @foreach ($options as $key => $option)
            <option value="{{ $key }}" @if (in_array((string)$key, (array)$selected, true) || (string)$selected === (string)$key) selected @endif>{{ $option }}</option>
        @endforeach
    </select>
</div>