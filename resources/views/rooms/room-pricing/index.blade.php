<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Room Pricing') }}</h3>
                    @can('rooms.pricing.form')
                        <div class="card-tools">
                            <a href="{{ route('rooms.pricing.add') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('Add Room Pricing') }}
                            </a>
                        </div>
                    @endcan
                </div>

                        <div class="card-body">
                            <!-- Filter Component -->
                            <x-datatable-filter>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Room Type') }}</label>
                                    <select name="room_type_id" class="form-select form-select-sm">
                                        <option value="">{{ __('All') }}</option>
                                        <!-- Add room type options -->
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Price') }}</label>
                                    <input type="number" name="price" class="form-control form-control-sm" placeholder="{{ __('Search by price') }}" step="0.01">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Currency') }}</label>
                                    <input type="text" name="currency" class="form-control form-control-sm" placeholder="{{ __('Search by currency') }}" maxlength="3">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('Active Status') }}</label>
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
                            </x-datatable-filter>

                            <!-- DataTable -->
                            <div class="table-responsive">
                                <x-datatables title="{{ __('Room Pricing') }}" :data="$dataTable"> </x-datatables>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</x-app-layout>
