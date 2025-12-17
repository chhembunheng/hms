<div class="sidebar sidebar-main sidebar-expand-lg">
    <div class="sidebar-content">
        <ul class="nav nav-sidebar" data-nav-type="accordion">
            @foreach ($menus as $menu)
                @include('layouts.partials.menu', ['menu' => $menu])
            @endforeach
        </ul>
    </div>
</div>
