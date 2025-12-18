<x-app-layout>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Menu Management') }}</h3>
                        </div>

                        <div class="card-body">
                            <!-- Filter Component -->
                            <x-datatable-filter>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('form.name') }}</label>
                                    <input type="text" name="name" class="form-control form-control-sm" placeholder="{{ __('global.search_by_name') }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">{{ __('form.route') }}</label>
                                    <input type="text" name="route" class="form-control form-control-sm" placeholder="{{ __('global.search_by_route') }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">{{ __('global.menus') }}</label>
                                    <select class="form-select form-select-sm multiple-select"
                                            multiple
                                            name="menu_ids[]"
                                            id="filter-menu"
                                            data-server="{{ route('settings.menus.select2') }}"
                                            data-filters="">
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">{{ __('global.created_date_range') }}</label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <input type="date" name="created_from" class="form-control form-control-sm">
                                        <span class="text-muted">—</span>
                                        <input type="date" name="created_to" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </x-datatable-filter>

                            <!-- DataTable -->
                            <div class="table-responsive">
                                <x-datatables title="{{ __('Menu List') }}" :data="$dataTable"></x-datatables>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@push('scripts')
{{ $dataTable->scripts() }}
@endpush
