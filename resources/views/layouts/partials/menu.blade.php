<li class="nav-item {{ $menu->children->count() ? 'nav-item-submenu' . ($menu->active ? ' nav-item-open' : '') : '' }}">
    <a href="{{ $menu->route && Route::has($menu->route) ? (strpos($menu->route, 'frontend.') === 0 ? route($menu->route, ['locale' => app()->getLocale()]) : route($menu->route)) : '#' }}" class="nav-link {{ $menu->active ? 'active' : '' }}">
        @if ($menu->icon)
            <i class="fa-solid {{ $menu->icon }} fa-fw"></i>
        @endif
        <span>{{ $menu->name }}</span>
    </a>
    @if ($menu->children->count())
        <ul class="nav-group-sub collapse {{ $menu->children->pluck('active')->contains(true) ? 'show' : '' }}">
            @foreach ($menu->children as $child)
                @include('layouts.partials.menu', ['menu' => $child])
            @endforeach
        </ul>
    @endif
</li>
