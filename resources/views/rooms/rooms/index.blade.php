<x-app-layout>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Rooms') }}</h3>
                        </div>

                        <div class="card-body">
                            <!-- Filter Component -->
                            <x-datatable-filter>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Room Number') }}</label>
                                    <input type="text" name="room_number" class="form-control form-control-sm" placeholder="{{ __('Search by room number') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Floor') }}</label>
                                    <input type="number" name="floor" class="form-control form-control-sm" placeholder="{{ __('Search by floor') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Room Type') }}</label>
                                    <select name="room_type_id" class="form-select form-select-sm">
                                        <option value="">{{ __('All') }}</option>
                                        <!-- Add options for room types -->
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Status') }}</label>
                                    <select name="status_id" class="form-select form-select-sm">
                                        <option value="">{{ __('All') }}</option>
                                        <!-- Add options for statuses -->
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Active Status') }}</label>
                                    <select name="is_active[]" class="form-select form-select-sm multiple-select" multiple>
                                        <option value="1">{{ __('Active') }}</option>
                                        <option value="0">{{ __('Inactive') }}</option>
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
                                <x-datatables title="{{ __('Rooms List') }}" :data="$dataTable"></x-datatables>
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
