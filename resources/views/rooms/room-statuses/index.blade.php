<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('rooms.room_statuses') }}</h3>
                    @can('rooms.status.form')
                        <div class="card-tools">
                            <a href="{{ route('rooms.status.add') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('rooms.add_room_status') }}
                            </a>
                        </div>
                    @endcan
                </div>

                <div class="card-body">
                    <!-- Filter Component -->
                    <x-datatable-filter>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('root.common.name') }}</label>
                            <input type="text" name="name" class="form-control form-control-sm" placeholder="{{ __('global.search_by_name') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('rooms.name_en') }}</label>
                            <input type="text" name="name_en" class="form-control form-control-sm" placeholder="{{ __('Search by English name') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('rooms.name_kh') }}</label>
                            <input type="text" name="name_kh" class="form-control form-control-sm" placeholder="{{ __('Search by Khmer name') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('form.description') }}</label>
                            <input type="text" name="description" class="form-control form-control-sm" placeholder="{{ __('global.search_by_description') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('rooms.color') }}</label>
                            <input type="text" name="color" class="form-control form-control-sm" placeholder="{{ __('Search by color') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('rooms.active_status') }}</label>
                            <select name="is_active[]" class="form-select form-select-sm multiple-select" multiple>
                                <option value="1">{{ __('rooms.active') }}</option>
                                <option value="0">{{ __('rooms.inactive') }}</option>
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
                        <x-datatables title="{{ __('rooms.room_statuses') }}" :data="$dataTable"></x-datatables>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
