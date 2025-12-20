<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('checkins.walk_in_check_in') }}</h3>
                    </div>

                    <div class="card-body">
                        <!-- Filter Component -->
                        <x-datatable-filter>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('global.search') }}</label>
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">{{ __('form.status') }}</label>
                                <select name="status[]" class="form-select form-select-sm multiple-select" multiple>
                                    <option value="confirmed">{{ __('rooms.confirmed') }}</option>
                                    <option value="checked_in">{{ __('rooms.checked_in') }}</option>
                                    <option value="checked_out">{{ __('rooms.checked_out') }}</option>
                                    <option value="cancelled">{{ __('rooms.cancelled') }}</option>
                                </select>
                            </div>
                        </x-datatable-filter>

                        <!-- DataTable -->
                        <div class="table-responsive">
                            <x-datatables title="{{ __('checkins.walk_in_check_in') }}" :data="$dataTable"></x-datatables>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
