<x-app-layout>
    <div class="content">
        <!-- Filter Component -->
        <x-datatable-filter>
            <div class="col-md-3">
                <label class="form-label">{{ __('From Currency') }}</label>
                <input type="text" name="from_currency" class="form-control form-control-sm" placeholder="{{ __('Search by from currency') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('To Currency') }}</label>
                <input type="text" name="to_currency" class="form-control form-control-sm" placeholder="{{ __('Search by to currency') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('Rate Range') }}</label>
                <div class="d-flex gap-2 align-items-center">
                    <input type="number" step="0.01" name="rate_from" class="form-control form-control-sm" placeholder="Min">
                    <span class="text-muted">—</span>
                    <input type="number" step="0.01" name="rate_to" class="form-control form-control-sm" placeholder="Max">
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('Status') }}</label>
                <select name="is_active[]" class="form-select form-select-sm multiple-select" multiple>
                    <option value="1">{{ __('Active') }}</option>
                    <option value="0">{{ __('Inactive') }}</option>
                </select>
            </div>

            <div class="col-md-6">
                <label class="form-label">{{ __('Effective Date Range') }}</label>
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
        <x-datatables title="{{ __('Exchange Rates') }}" :data="$dataTable">
        </x-datatables>
    </div>
</x-app-layout>
