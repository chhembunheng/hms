@props(['type' => 'submit', 'variant' => 'primary', 'text' => null])

<button type="{{ $type }}" class="btn btn-{{ $variant }}">
    <i class="fas fa-save me-1"></i>{{ $text ?: __('global.save') }}
</button>
