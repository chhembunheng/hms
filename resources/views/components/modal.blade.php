@props(['name', 'title' => '', 'show' => false, 'maxWidth' => 'md', 'remoteUrl' => ''])

@php
    $maxWidth = [
        'xs' => 'modal-xs',
        'sm' => 'modal-sm',
        'md' => '',
        'lg' => 'modal-lg',
        'xl' => 'modal-xl',
        'full' => 'modal-full',
    ][$maxWidth];
@endphp
<div {{ $attributes->merge(['class' => 'modal fade', 'id' => $name, 'tabindex' => '-1', 'aria-labelledby' => $name . '-label', 'aria-hidden' => 'true']) }}>
    <div class="modal-dialog  {{ $maxWidth }}">
        <div class="modal-content">
            <div class="d-flex justify-content-between p-3">
                <h5>{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
