<x-app-layout>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('global.menu_management') }}</h3>
                        </div>

                        <div class="card-body">
                            <!-- Filter Component -->
                            <x-datatable-filter>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('global.search') }}</label>
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
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
                            </x-datatable-filter>

                            <!-- DataTable -->
                            <div class="table-responsive">
                                <x-datatables title="{{ __('global.menu_list') }}" :data="$dataTable"></x-datatables>
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
