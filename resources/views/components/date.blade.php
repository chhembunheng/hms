@props(['name' => 'date', 'label' => null, 'value' => null])

@if($label)
    <x-input-label :for="$name" :value="$label" />
@endif

<input type="date" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, $value) }}" {{ $attributes->merge(['class' => 'form-control']) }}>
