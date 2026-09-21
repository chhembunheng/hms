@php
    $hasChildren = $menu->children->count() > 0;
    $isChildActive = $hasChildren && $menu->children->pluck('active')->contains(true);
    $isOpen = $menu->active || $isChildActive;
@endphp
<li class="nav-item {{ $hasChildren ? 'nav-item-submenu' . ($isOpen ? ' nav-item-open' : '') : '' }}">
    <a href="{{ $hasChildren ? '#' : ($menu->route && Route::has($menu->route) ? (strpos($menu->route, 'frontend.') === 0 ? route($menu->route, ['locale' => app()->getLocale()]) : route($menu->route)) : '#') }}" 
       class="nav-link {{ (!$hasChildren && $menu->active) ? 'active' : '' }}">
        @if ($menu->icon)
            <i data-lucide="{{ get_lucide_icon($menu->icon) }}" class="nav-link-icon"></i>
        @else
            <i data-lucide="minus" class="nav-link-icon sub-bullet-icon"></i>
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
