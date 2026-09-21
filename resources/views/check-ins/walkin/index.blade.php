<x-page-index 
    :title="__('checkins.walk_in_check_in')" 
    icon="user-plus"
    :create-route="route('checkin.walkin.add')"
    :create-permission="'checkin.walkin.add'"
    :create-label="__('checkins.walk_in_check_in')">

    <x-slot:filters>
        <x-datatable-filter seamless="true">
            <div class="col-md-4">
                <label class="form-label">{{ __('global.search') }}</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('form.status') }}</label>
                <select name="status[]" class="form-select form-select-sm multiple-select" multiple>
                    <option value="confirmed">{{ __('rooms.confirmed') }}</option>
                    <option value="checked_in">{{ __('rooms.checked_in') }}</option>
                    <option value="checked_out">{{ __('rooms.checked_out') }}</option>
                    <option value="cancelled">{{ __('rooms.cancelled') }}</option>
                </select>
            </div>
        </x-datatable-filter>
    </x-slot:filters>

    <x-datatables :data="$dataTable" seamless="true" />
</x-page-index>
