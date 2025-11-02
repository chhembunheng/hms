<div class="d-inline-flex dropdown ms-2">
    <a href="#" class="text-body" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">
        @foreach ($actions as $action)
            @if ($action['action'] === 'delete')
                <a href="{{ route($action['action_route'], ['id' => $row->id]) }}" class="dropdown-item" onclick="deleteRecord(event)">
                    <i class="fa-light {{ $action['icon'] }} me-2"></i> {{ $action->translations->firstWhere('locale', app()->getLocale())->name }}
                </a>
            @endif
            @if ($action['target'] === 'self')
                <a href="{{ route($action['action_route'], ['id' => $row->id]) }}" class="dropdown-item">
                    <i class="fa-light {{ $action['icon'] }} me-2"></i> {{ $action->translations->firstWhere('locale', app()->getLocale())->name }}
                </a>
            @endif
        @endforeach
    </div>
</div>
