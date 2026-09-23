@props([
    'title',
    'icon' => null,
    'badge' => null,
    'createRoute' => null,
    'createPermission' => null,
    'createLabel' => null,
])

<x-app-layout>
    <div class="container-fluid py-3">
        <div class="card enterprise-card border-0 shadow-sm">
            <!-- Enterprise Page Header & Toolbar -->
            <div class="card-header enterprise-card-header d-flex flex-wrap justify-content-between align-items-center py-2 px-3 border-bottom bg-white">
                <div class="d-flex align-items-center gap-2">
                    @if($icon)
                        <div class="enterprise-header-icon rounded-circle d-flex align-items-center justify-content-center">
                            <i data-lucide="{{ get_lucide_icon($icon) }}" style="width: 17px; height: 17px;"></i>
                        </div>
                    @endif
                    <div>
                        <h5 class="mb-0 fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                            {{ $title }}
                            @if($badge)
                                <span class="badge bg-light text-primary border border-primary-subtle fw-semibold rounded-pill px-2 py-1" style="font-size: 0.72rem;">
                                    {{ $badge }}
                                </span>
                            @endif
                        </h5>
                    </div>
                </div>

                <!-- Action Toolbar -->
                <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                    @if(isset($actions) && $actions instanceof \Illuminate\View\ComponentSlot && $actions->isNotEmpty())
                        {{ $actions }}
                    @elseif(isset($toolbarActions) && $toolbarActions instanceof \Illuminate\View\ComponentSlot && $toolbarActions->isNotEmpty())
                        {{ $toolbarActions }}
                    @endif

                    @if(isset($filters) && $filters instanceof \Illuminate\View\ComponentSlot && $filters->isNotEmpty())
                        <button type="button" class="btn-enterprise-action active" id="toggle-filter-panel" title="{{ __('global.toggle_filters') }}">
                            <i data-lucide="filter" style="width: 14px; height: 14px;"></i>
                            <span class="d-none d-md-inline">{{ __('global.filters') }}</span>
                        </button>
                    @endif

                    <button type="button" class="btn-enterprise-action btn-enterprise-icon-btn" onclick="if(typeof $('.datatables').DataTable !== 'undefined') $('.datatables').DataTable().ajax.reload();" title="{{ __('global.refresh') }}">
                        <i data-lucide="rotate-cw" style="width: 14px; height: 14px;"></i>
                    </button>

                    @if($createRoute)
                        @if(!$createPermission || (auth()->check() && auth()->user()->can($createPermission)))
                            <a href="{{ $createRoute }}" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 rounded-2 px-3 fw-medium">
                                <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
                                <span>{{ $createLabel ?? __('global.add_new') }}</span>
                            </a>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Optional Collapsible Filter Section -->
            @if(isset($filters) && $filters instanceof \Illuminate\View\ComponentSlot && $filters->isNotEmpty())
                <div id="enterprise-filter-panel" class="is-open border-bottom">
                    <div class="enterprise-filter-inner px-3 py-3">
                        {{ $filters }}
                    </div>
                </div>
            @endif

            <!-- DataTable Body Canvas -->
            <div class="card-body p-3">
                <div class="table-responsive">
                    {{ $slot }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
