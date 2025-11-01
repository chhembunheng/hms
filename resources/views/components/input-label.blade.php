@props(['for' => null, 'value' => null])

<label @if($for) for="{{ $for }}" @endif {{ $attributes->merge(['class' => 'form-label']) }}>
    {{ $value ?? $slot }}
    @isset($required)
        <span class="text-red-600">*</span>
    @endisset
    @isset($hint)
        <span class="text-xs text-gray-500 ms-1">{{ $hint }}</span>
    @endisset
</label>
