@props(['start' => 'start_date', 'end' => 'end_date', 'label' => null, 'valueStart' => null, 'valueEnd' => null])

@if($label)
    <x-input-label :for="$start" :value="$label" />
@endif

<div class="d-flex align-items-center gap-2">
    <input type="date" name="{{ $start }}" id="{{ $start }}" value="{{ old($start, $valueStart) }}" {{ $attributes->merge(['class' => 'form-control']) }}>
    <span class="text-muted">—</span>
    <input type="date" name="{{ $end }}" id="{{ $end }}" value="{{ old($end, $valueEnd) }}" {{ $attributes->merge(['class' => 'form-control']) }}>
</div>
