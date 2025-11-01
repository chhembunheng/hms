<div class="d-inline-flex dropdown ms-2">
    <a href="#" class="text-body" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">
        @foreach ($actions as $action)
            @isset($action['action'])
                @if (!in_array($action['action'], ['add', 'list']) && $action['target'] == 'self' && Route::has($action['action_route']))
                    <a href="{{ route($action['action_route'], $a->id) }}" class="dropdown-item">
                        <i class="fa-solid {{ $action['icon'] }} me-2"></i>
                        {{ $action['name_' . app()->getLocale()] }}
                    </a>
                @endif
                @if ($action['action'] == 'delete' && Route::has($action['action_route']))
                    <a href="{{ route($action['action_route'], $a->id) }}" class="dropdown-item" onclick="deleteRecord(event)">
                        <i class="fa-solid {{ $action['icon'] }} me-2"></i>
                        {{ $action['name_' . app()->getLocale()] }}
                    </a>
                @endif
            @endisset
        @endforeach
    </div>
</div>
