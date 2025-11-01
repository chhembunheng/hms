@props([
    'name' => null,
    'data' => [],
    'placeholder' => null,
    'optionValue' => null,
    'optionLabel' => null,
    'multiple' => false,
])

<select name="{{ $name }}@if($multiple)[]@endif" @if($multiple) multiple @endif {{ $attributes->merge(['class' => 'form-select']) }}>
    @if($placeholder)
        <option value="">{{ $placeholder }}</option>
    @endif

    @foreach($data as $key => $item)
        @php
            $value = $optionValue ? data_get($item, $optionValue) : (is_array($data) && !is_array($item) ? $key : (is_array($item) ? ($item['value'] ?? $key) : $item));
            $label = $optionLabel ? data_get($item, $optionLabel) : (is_array($data) && !is_array($item) ? $item : (is_array($item) ? ($item['label'] ?? $item['name'] ?? $value) : $item));
        @endphp
        <option value="{{ $value }}" @selected(collect(old($name, $attributes->get('value')))->contains($value))>{{ $label }}</option>
    @endforeach
</select>
