<x-app-layout>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('checkins.checkout_list') }}</h3>
                </div>

                        <div class="card-body">
                            <x-datatable-filter>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('global.search') }}</label>
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('checkins.check_out_date') }}</label>
                                    <input type="date" name="check_out_from" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('global.to') }}</label>
                                    <input type="date" name="check_out_to" class="form-control form-control-sm">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('checkins.payment_status') }}</label>
                                    <select name="paid_status" class="form-select form-select-sm">
                                        <option value="">{{ __('global.all') }}</option>
                                        <option value="paid">{{ __('checkins.paid') }}</option>
                                        <option value="partial">{{ __('checkins.partial') }}</option>
                                        <option value="unpaid">{{ __('checkins.unpaid') }}</option>
                                    </select>
                                </div>
                            </x-datatable-filter>

                            <!-- DataTable -->
                            <div class="table-responsive">
                                <x-datatables title="{{ __('checkins.checkout_list') }}" :data="$dataTable"></x-datatables>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
