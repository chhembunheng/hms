<div class="d-inline-flex dropdown ms-2">
    <a href="#" class="text-body" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">
        <u></u>
        @foreach ($actions as $action)
            @if (!isset($action['action_route']) || !Route::has($action['action_route']))
                @continue
            @endif
            @if ($action['action'] == 'delete')
                <a href="{{ route($action['action_route'], ['id' => $row->id]) }}" class="dropdown-item" onclick="deleteRecord(event)">
                    <i class="fa-light {{ $action['icon'] }} me-2"></i> {{ $action->translations->firstWhere('locale', app()->getLocale())->name }}
                </a>
            @endif
        @endforeach
    </div>
</div>
