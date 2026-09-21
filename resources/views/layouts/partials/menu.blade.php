@php
    $hasChildren = $menu->children->count() > 0;
    $isChildActive = $hasChildren && $menu->children->pluck('active')->contains(true);
    $isOpen = $menu->active || $isChildActive;
    $menuIcon = $menu->icon ? trim($menu->icon) : '';
    if ($menuIcon) {
        if (!str_contains($menuIcon, 'fa-solid') && !str_contains($menuIcon, 'fa-regular') && !str_contains($menuIcon, 'fa-brands')) {
            $menuIcon = str_starts_with($menuIcon, 'fa-') ? 'fa-solid ' . $menuIcon : 'fa-solid fa-' . $menuIcon;
        }
    }
@endphp
<li class="nav-item {{ $hasChildren ? 'nav-item-submenu' . ($isOpen ? ' nav-item-open' : '') : '' }}">
    <a href="{{ $hasChildren ? '#' : ($menu->route && Route::has($menu->route) ? (strpos($menu->route, 'frontend.') === 0 ? route($menu->route, ['locale' => app()->getLocale()]) : route($menu->route)) : '#') }}" 
       class="nav-link {{ (!$hasChildren && $menu->active) ? 'active' : '' }}">
        @if ($menuIcon)
            <i class="{{ $menuIcon }} fa-fw me-2"></i>
        @else
            <i class="fa-solid fa-minus fa-fw me-2 sub-bullet-icon" style="font-size: 0.65rem;"></i>
        @endif
        <span class="nav-link-title">{{ $menu->name }}</span>
    </a>
    @if ($hasChildren)
        <ul class="nav-group-sub collapse {{ $isOpen ? 'show' : '' }}" data-submenu-title="{{ $menu->name }}">
            @foreach ($menu->children as $child)
                @include('layouts.partials.menu', ['menu' => $child])
            @endforeach
        </ul>
    @endif
</li>
