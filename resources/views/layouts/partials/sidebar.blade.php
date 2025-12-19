<div class="sidebar sidebar-main sidebar-expand-lg">
    <div class="sidebar-content">
        <ul class="nav nav-sidebar" data-nav-type="accordion">
            @foreach ($menus as $menu)
                @include('layouts.partials.menu', ['menu' => $menu])
            @endforeach
        </ul>
    </div>
        <div class="sidebar-footer text-center py-2 border-top mt-auto" style="background: #f8f9fa;">
            <small class="text-muted">
            &copy; {{ date('Y') }} <a href="https://github.com/chhembunheng" target="_blank" class="text-decoration-none">@hengdev_04</a><br>
            All rights reserved.
            </small>
        </div>
</div>
