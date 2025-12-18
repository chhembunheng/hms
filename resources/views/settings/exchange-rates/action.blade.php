<div class="d-inline-flex dropdown ms-2">
    <a href="#" class="text-body" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">
        <a href="{{ route('settings.exchange-rate.edit', $row->id) }}" class="dropdown-item">
            <i class="fa-light fa-pen-to-square me-2"></i> Edit
        </a>
        <a href="{{ route('settings.exchange-rate.delete', $row->id) }}" class="dropdown-item" onclick="deleteRecord(event)">
            <i class="fa-light fa-trash me-2"></i> Delete
        </a>
    </div>
</div>
