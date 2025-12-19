<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('global.exchange_rate_management') }}</h3>
                    @can('settings.exchange-rate.form')
                        <div class="card-tools">
                            <a href="{{ route('settings.exchange-rate.add') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('global.add_exchange_rate') }}
                            </a>
                        </div>
                    @endcan
                </div>

                <div class="card-body">
                    <!-- Filter Component -->
                    <x-datatable-filter>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('global.from_currency') }}</label>
                            <input type="text" name="from_currency" class="form-control form-control-sm" placeholder="{{ __('global.search_by_from_currency') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('global.to_currency') }}</label>
                            <input type="text" name="to_currency" class="form-control form-control-sm" placeholder="{{ __('global.search_by_to_currency') }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('global.rate_range') }}</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="number" step="0.01" name="rate_from" class="form-control form-control-sm" placeholder="{{ __('global.min') }}">
                                <span class="text-muted">—</span>
                                <input type="number" step="0.01" name="rate_to" class="form-control form-control-sm" placeholder="{{ __('global.max') }}">
                            </div>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">{{ __('rooms.active_status') }}</label>
                            <select name="is_active[]" class="form-select form-select-sm multiple-select" multiple>
                                <option value="1">{{ __('rooms.active') }}</option>
                                <option value="0">{{ __('rooms.inactive') }}</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">{{ __('global.effective_date_range') }}</label>
                            <div class="d-flex gap-2 align-items-center">
                                <input type="date" name="effective_from" class="form-control form-control-sm">
                                <span class="text-muted">—</span>
                                <input type="date" name="effective_to" class="form-control form-control-sm">
                            </div>
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
                        <x-datatables title="{{ __('global.exchange_rates') }}" :data="$dataTable"> </x-datatables>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
