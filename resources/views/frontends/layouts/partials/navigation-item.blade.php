<li class="nav-item">
    <a href="{{ !is_null($navigation->route) ? route($navigation->route, ['locale' => app()->getLocale()]) : '' }}" class="nav-link">
        @if ($navigation->icon)
            <i class="fa-solid {{ $navigation->icon }} fa-fw"></i>
        @endif
        <span>{{ $navigation->name }}</span>
    </a>
    @if ($navigation->children->count())
        <ul class="dropdown-menu">
            @foreach ($navigation->children as $child)
                @include('frontends.layouts.partials.navigation-item', ['navigation' => $child])
            @endforeach
        </ul>
    @endif
</li>
