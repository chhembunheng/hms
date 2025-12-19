<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('rooms.check_ins') }}</h3>
                        @can('check-ins.create')
                            <div class="card-tools">
                                <a href="{{ route('check-ins.create') }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-plus"></i> {{ __('rooms.add_check_in') }}
                                </a>
                            </div>
                        @endcan
                    </div>

                    <div class="card-body">
                        <x-datatable-filter>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('global.search') }}</label>
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">{{ __('rooms.guest_type') }}</label>
                                <select name="guest_type" class="form-select form-select-sm">
                                    <option value="">{{ __('global.all') }}</option>
                                    <option value="national">{{ __('rooms.national') }}</option>
                                    <option value="international">{{ __('rooms.international') }}</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">{{ __('global.status') }}</label>
                                <select name="status" class="form-select form-select-sm">
                                    <option value="">{{ __('global.all') }}</option>
                                    <option value="confirmed">{{ __('rooms.confirmed') }}</option>
                                    <option value="checked_in">{{ __('rooms.checked_in') }}</option>
                                    <option value="checked_out">{{ __('rooms.checked_out') }}</option>
                                    <option value="cancelled">{{ __('rooms.cancelled') }}</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">{{ __('rooms.check_in_date') }}</label>
                                <input type="date" name="check_in_date" class="form-control form-control-sm">
                            </div>
                        </x-datatable-filter>

                        <!-- DataTable -->
                        <div class="table-responsive">
                            <x-datatables title="{{ __('rooms.check_ins') }}" :data="$dataTable"></x-datatables>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>