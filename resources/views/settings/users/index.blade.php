<x-app-layout>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('global.user_management') }}</h3>
                            @can('settings.users.form')
                                <div class="card-tools">
                                    <a href="{{ route('settings.users.add') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> {{ __('global.add_user') }}
                                    </a>
                                </div>
                            @endcan
                        </div>

                        <div class="card-body">
                            <!-- Filter Component -->
                            <x-datatable-filter>
                                <div class="col-md-4">
                                    <label class="form-label">{{ __('form.name') }}</label>
                                    <input type="text" name="name" class="form-control form-control-sm" placeholder="{{ __('global.search_by_name') }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">{{ __('form.email') }}</label>
                                    <input type="email" name="email" class="form-control form-control-sm" placeholder="{{ __('global.search_by_email') }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">{{ __('global.roles') }}</label>
                                    <select class="form-select form-select-sm multiple-select"
                                            multiple
                                            name="role_ids[]"
                                            id="filter-role"
                                            data-server="{{ route('settings.roles.select2') }}"
                                            data-filters="">
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
                                <x-datatables title="{{ __('global.user_list') }}" :data="$dataTable"></x-datatables>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
