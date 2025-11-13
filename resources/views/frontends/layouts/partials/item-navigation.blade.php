<li class="nav-item @if($navigation->children->count()) dropdown @endif">
    <a href="{{ !is_null($navigation->route) ? route($navigation->route, ['locale' => app()->getLocale()]) : '#' }}" class="nav-link @if($navigation->children->count()) dropdown-toggle @endif" @if($navigation->children->count()) id="nav-{{ $navigation->id }}" role="button" data-bs-toggle="dropdown" aria-expanded="false" @endif>
        @if (!empty($navigation->icon) && !empty($navigation->parent_id))
            <i class="fa-solid {{ $navigation->icon }} fa-fw"></i>
        @endif
        <span>{{ $navigation->name }}</span>
        @if($navigation->children->count())
            <i class="fa-solid fa-chevron-down fa-fw ms-1" style="font-size: 0.75rem;"></i>
        @endif
    </a>
    @if ($navigation->children->count())
        <ul class="dropdown-menu dropdown-sub-menu" aria-labelledby="nav-{{ $navigation->id }}">
            @foreach ($navigation->children as $child)
                @include('frontends.layouts.partials.item-navigation', ['navigation' => $child])
            @endforeach
        </ul>
    @endif
</li>
