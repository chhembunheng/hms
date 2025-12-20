<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('global.activity_logs') }}</h3>
                    </div>

                    <div class="card-body">
                        <!-- Filter Component -->
                        <x-datatable-filter>
                            <div class="col-md-3">
                                <label class="form-label">{{ __('global.search') }}</label>
                                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">{{ __('global.user') }}</label>
                                <select name="user_id" class="form-select form-select-sm multiple-select"
                                        data-server="{{ route('settings.users.select2') }}"
                                        data-filters=""
                                        multiple>

                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">{{ __('global.action') }}</label>
                                <select name="action" class="form-select form-select-sm multiple-select" multiple>
                                    <option value="created">{{ __('global.created') }}</option>
                                    <option value="updated">{{ __('global.updated') }}</option>
                                    <option value="deleted">{{ __('global.deleted') }}</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label">{{ __('global.model_type') }}</label>
                                <select name="model_type" class="form-select form-select-sm multiple-select" multiple>
                                    @foreach(modelTypes() as $type)
                                        <option value="{{ $type }}">{{ class_basename($type) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </x-datatable-filter>

                        <!-- DataTable -->
                        <div class="table-responsive">
                            <x-datatables title="{{ __('global.activity_log_list') }}" :data="$dataTable"></x-datatables>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
