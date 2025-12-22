<x-app-layout>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('guests.guest_list') }}</h3>
                    @can('guests.list.form')
                        <div class="card-tools">
                            <a href="{{ route('guests.list.add') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('guests.add_guest') }}
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
                                    <label class="form-label">{{ __('guests.guest_type') }}</label>
                                    <select name="guest_type" class="form-select form-select-sm">
                                        <option value="">{{ __('global.all') }}</option>
                                        <option value="national">{{ __('guests.national') }}</option>
                                        <option value="international">{{ __('guests.international') }}</option>
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('guests.country') }}</label>
                                    <input type="text" name="country" class="form-control form-control-sm" placeholder="{{ __('guests.country') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('global.created_at') }}</label>
                                    <div class="input-group input-group-sm">
                                        <input type="date" name="created_from" class="form-control form-control-sm">
                                        <span class="input-group-text">-</span>
                                        <input type="date" name="created_to" class="form-control form-control-sm">
                                    </div>
                                </div>
                            </x-datatable-filter>

                            <!-- DataTable -->
                            <div class="table-responsive">
                                <x-datatables title="{{ __('guests.guest_list') }}" :data="$dataTable"></x-datatables>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
