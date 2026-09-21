@props(['start' => 'start_date', 'end' => 'end_date', 'label' => null, 'valueStart' => null, 'valueEnd' => null])

@if($label)
    <x-input-label :for="$start" :value="$label" />
@endif

<div class="d-flex align-items-center gap-2">
    <div class="input-group input-group-sm">
        <input type="text" name="{{ $start }}" id="{{ $start }}" value="{{ old($start, format_date($valueStart)) }}" placeholder="dd-mm-yyyy" autocomplete="off" {{ $attributes->merge(['class' => 'form-control form-control-sm pickadate']) }}>
        <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
    </div>
    <span class="text-muted">—</span>
    <div class="input-group input-group-sm">
        <input type="text" name="{{ $end }}" id="{{ $end }}" value="{{ old($end, format_date($valueEnd)) }}" placeholder="dd-mm-yyyy" autocomplete="off" {{ $attributes->merge(['class' => 'form-control form-control-sm pickadate']) }}>
        <span class="input-group-text"><i class="fa-solid fa-calendar-days"></i></span>
    </div>
</div>
