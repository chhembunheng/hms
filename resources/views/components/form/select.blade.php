@props(['label', 'options' => [], 'value' => null, 'selected' => null, 'isSelect2' => $attributes->has('multiple') ? '' : 'select2'])
<div class="mb-3">
    <label class="form-label @if ($attributes->has('required')) required @endif">{{ $label ?? '' }}</label>
    <select {{ $attributes->merge(['class' => "form-select form-select-sm $isSelect2"]) }}>
        @if($isSelect2) <option value="">{{ __('form.select_option') }}</option> @endif
        @foreach ($options as $key => $option)
            <option value="{{ $key }}" @if (in_array($key, (array) $selected)) selected @endif>{{ $option }}</option>
        @endforeach
    </select>
</div> 