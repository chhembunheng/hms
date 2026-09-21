<x-page-index 
    :title="__('global.user_management')" 
    icon="users"
    :create-route="route('settings.users.add')"
    :create-permission="'settings.users.form'"
    :create-label="__('global.add_user')">

    <x-slot:filters>
        <x-datatable-filter seamless="true">
            <div class="col-md-4">
                <label class="form-label">{{ __('global.search') }}</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
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
        </x-datatable-filter>
    </x-slot:filters>

    <x-datatables :data="$dataTable" seamless="true" />
</x-page-index>
