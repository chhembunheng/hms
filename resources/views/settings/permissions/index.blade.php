<x-app-layout>
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('global.permissions_management') }}</h3>
                            @can('settings.permissions.form')
                                <div class="card-tools">
                                    <a href="{{ route('settings.permissions.add') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-plus"></i> {{ __('global.add_permission') }}
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
                                    <label class="form-label">{{ __('form.display_name') }}</label>
                                    <input type="text" name="display_name" class="form-control form-control-sm" placeholder="{{ __('global.search_by_display_name') }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">{{ __('form.description') }}</label>
                                    <input type="text" name="description" class="form-control form-control-sm" placeholder="{{ __('global.search_by_description') }}">
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
                                <x-datatables title="{{ __('global.permission_list') }}" :data="$dataTable"></x-datatables>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
