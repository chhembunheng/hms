@props(['label', 'required' => $attributes->has('required') && $attributes->get('required') === 'required'])
<div class="mb-3">
    <label class="form-label @if ($required) required @endif">{{ $label ?? '' }}</label>
    <input @if ($attributes->has('type')) type="{{ $attributes->get('type') }}" @else type="text" @endif {{ $attributes->merge(['class' => 'form-control form-control-sm']) }} @if ($required) required @endif>
</div>