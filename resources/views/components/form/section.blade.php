@props(['title', 'icon' => null, 'subtitle' => null])
<div class="col-12 mt-2 mb-2">
    <div class="d-flex align-items-center gap-2 pb-2 border-bottom">
        <span class="d-inline-block rounded-pill bg-primary" style="width: 3.5px; height: 16px;"></span>
        @if($icon)
            <i data-lucide="{{ get_lucide_icon($icon) }}" class="text-primary flex-shrink-0" style="width: 15px; height: 15px;"></i>
        @endif
        <h6 class="mb-0 fw-bold fs-6" style="font-size: 0.875rem !important; color: #0f172a !important;">
            {{ $title }}
        </h6>
        @if($subtitle)
            <small class="text-muted ms-1" style="font-size: 0.75rem;">({{ $subtitle }})</small>
        @endif
    </div>
</div>
