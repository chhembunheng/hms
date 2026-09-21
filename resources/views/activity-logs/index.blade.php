<x-page-index 
    :title="__('global.activity_logs')" 
    icon="activity">

    <x-slot:filters>
        <x-datatable-filter seamless="true">
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
    </x-slot:filters>

    <x-datatables :data="$dataTable" seamless="true" />
</x-page-index>
