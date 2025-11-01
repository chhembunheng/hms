<button {{ $attributes->merge([
    'class' => 'btn btn-light'
]) }}>
    {{ $slot }}
</button>
