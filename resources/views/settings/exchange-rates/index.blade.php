<x-page-index 
    :title="__('global.exchange_rate_management')" 
    icon="arrow-left-right"
    :create-route="route('settings.exchange-rate.add')"
    :create-permission="'settings.exchange-rate.form'"
    :create-label="__('global.add_exchange_rate')">

    <x-slot:filters>
        <x-datatable-filter seamless="true">
            <div class="col-md-4">
                <label class="form-label">{{ __('global.search') }}</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('rooms.active_status') }}</label>
                <select name="is_active[]" class="form-select form-select-sm multiple-select" multiple>
                    <option value="1">{{ __('rooms.active') }}</option>
                    <option value="0">{{ __('rooms.inactive') }}</option>
                </select>
            </div>
        </x-datatable-filter>
    </x-slot:filters>

    <x-datatables :data="$dataTable" seamless="true" />
</x-page-index>
