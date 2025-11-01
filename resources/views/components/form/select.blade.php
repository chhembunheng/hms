@props(['label', 'options' => [], 'value' => null, 'multiple' => false])
<div class="mb-3">
    <label class="form-label @if ($attributes->has('required')) required @endif">{{ $label ?? '' }}</label>
    <select name="{{ $attributes->get('name') }}" {{ $attributes->has('required') ? 'required' : '' }} class="form-select form-select-sm" @if ($multiple) multiple @endif>
        @foreach ($options as $key => $option)
            <option value="{{ $key }}" @if (in_array($key, (array) $value)) selected @endif>{{ $option }}</option>
        @endforeach
    </select>
</div>