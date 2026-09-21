<x-page-index 
    :title="__('global.role_management')" 
    icon="shield"
    :create-route="route('settings.roles.add')"
    :create-permission="'settings.roles.form'"
    :create-label="__('global.add_role')">

    <x-slot:filters>
        <x-datatable-filter seamless="true">
            <div class="col-md-4">
                <label class="form-label">{{ __('global.search') }}</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
            </div>
        </x-datatable-filter>
    </x-slot:filters>

    <x-datatables :data="$dataTable" seamless="true" />
</x-page-index>
