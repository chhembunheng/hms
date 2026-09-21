@props(['title', 'icon' => null, 'subtitle' => null])
<div class="col-12 mt-3 mb-1">
    <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
        @if($icon)
            <i data-lucide="{{ get_lucide_icon($icon) }}" class="text-primary" style="width: 15px; height: 15px;"></i>
        @endif
        <h6 class="mb-0 fw-semibold text-primary" style="font-size: 0.875rem; letter-spacing: 0.2px;">
            {{ $title }}
        </h6>
        @if($subtitle)
            <small class="text-muted ms-1" style="font-size: 0.75rem;">({{ $subtitle }})</small>
        @endif
    </div>
</div>
