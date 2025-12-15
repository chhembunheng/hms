@props(['data' => [], 'menus' => []])
<div class="page-header page-header-light shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="page-header-content d-lg-flex border-top dark:border-gray-700" style="height: 50px !important;">
        <div class="d-flex">
            <div class="breadcrumb py-2 dark:bg-gray-800">
                <a href="{{ route('dashboard.index') }}" class="breadcrumb-item dark:text-gray-300 dark:hover:text-white">
                    <i class="fa-solid fa-chart-simple fa-fw"></i>
                </a>
                @php
                    $routeName = request()->route()->getName();
                    $levels = collect($menus ?? [])
                        ->where('active', true)
                        ->first();
                    $breadcrumbs = [];
                @endphp
                @if ($levels)
                    @php $breadcrumbs[] = $levels; @endphp
                    @if ($levels->children->where('active', true)->first())
                        @php $breadcrumbs[] = $levels->children->where('active', true)->first(); @endphp
                        @if ($breadcrumbs[1]->children->where('active', true)->first())
                            @php $breadcrumbs[] = $breadcrumbs[1]->children->where('active', true)->first(); @endphp
                        @endif
                    @endif
                @endif
                @foreach ($breadcrumbs as $item)
                    @php $route = $item->route && $item->route !== $routeName ? route($item->route) : ''; @endphp
                    @if ($route)
                        <a href="{{ $route }}" class="breadcrumb-item dark:text-gray-300 dark:hover:text-white">{{ $item->name }}</a>
                    @else
                        <span class="breadcrumb-item dark:text-gray-100">{{ $item->name }}</span>
                    @endif
                @endforeach
            </div>
        </div>
        <div class="collapse d-lg-block ms-lg-auto" id="bc_icon_button_group">
            <div class="d-lg-flex align-items-center justify-content-center h-100">
                @foreach ($data as $row)
                    <a href="{{ route($row['action_route']) }}" class="btn btn-link dark:text-gray-300 dark:hover:text-white">
                        <i class="fa-solid {{ $row['icon'] }} fa-xl fa-fw"></i> &nbsp;<b>{{ $row['name_' . app()->getLocale()] }}</b>
                    </a>
                @endforeach
            </div>
        </div>
        <div class="d-lg-flex mb-2 mb-lg-0">
            @foreach ($navbars as $navbar)
                @if (Route::currentRouteName() != $navbar['action_route'])
                    <a href="{{ route($navbar['action_route']) }}" class="d-flex align-items-center text-body dark:text-gray-300 dark:hover:text-white py-2">
                        <i class="fa-solid {{ $navbar['icon'] }} me-1 fa-fw fa-xl"></i>
                        {{ $navbar['name_' . app()->getLocale()] }}
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</div>
