<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('checkins.void_cancelled_stays') }}</h3>
                    </div>

                    <div class="card-body">
                        <!-- Filter Component -->
                        <x-datatable-filter>
                            <div class="col-md-4">
                                <label class="form-label">{{ __('global.search') }}</label>
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">{{ __('checkins.guest_type') }}</label>
                                <select name="guest_type[]" class="form-select form-select-sm multiple-select" multiple>
                                    <option value="national">{{ __('checkins.national') }}</option>
                                    <option value="international">{{ __('checkins.international') }}</option>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label">{{ __('checkins.cancelled_date_range') }}</label>
                                <input type="text" name="cancelled_date" class="form-control form-control-sm daterange" placeholder="Select date range">
                            </div>
                        </x-datatable-filter>

                        <!-- DataTable -->
                        <div class="table-responsive">
                            <x-datatables title="{{ __('checkins.void_cancelled_stays') }}" :data="$dataTable"></x-datatables>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
