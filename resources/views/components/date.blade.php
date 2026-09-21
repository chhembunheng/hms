@props(['name' => 'date', 'label' => null, 'value' => null])

@if($label)
    <x-input-label :for="$name" :value="$label" />
@endif

<div class="input-group">
    <input type="text" name="{{ $name }}" id="{{ $name }}" value="{{ old($name, format_date($value)) }}" placeholder="dd-mm-yyyy" autocomplete="off" {{ $attributes->merge(['class' => 'form-control pickadate']) }}>
    <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
</div>
