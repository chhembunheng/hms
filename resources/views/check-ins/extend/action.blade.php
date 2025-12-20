<div class="d-inline-flex dropdown ms-2">
    <a href="#" class="text-body" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">
        @can('checkin.extend.edit')
            <a href="{{ route('checkin.extend.edit', $row->id) }}" class="dropdown-item">
                <i class="fa-light fa-calendar-plus me-2"></i> Extend Stay
            </a>
        @endcan
    </div>
</div>
