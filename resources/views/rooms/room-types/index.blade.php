<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('rooms.room_types') }}</h3>
                    @can('rooms.type.form')
                        <div class="card-tools">
                            <a href="{{ route('rooms.type.add') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('rooms.add_room_type') }}
                            </a>
                        </div>
                    @endcan
                </div>

                <div class="card-body">
                    <!-- Filter Component -->
                    <x-datatable-filter>
                        <div class="col-md-4">
                            <label class="form-label">{{ __('global.search') }}</label>
                            <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">{{ __('rooms.active_status') }}</label>
                            <select name="is_active[]" class="form-select form-select-sm multiple-select" multiple>
                                <option value="1">{{ __('rooms.active') }}</option>
                                <option value="0">{{ __('rooms.inactive') }}</option>
                            </select>
                        </div>
                    </x-datatable-filter>

                    <!-- DataTable -->
                    <div class="table-responsive">
                        <x-datatables title="{{ __('rooms.room_types') }}" :data="$dataTable"></x-datatables>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
