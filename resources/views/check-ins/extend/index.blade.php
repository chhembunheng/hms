<x-page-index 
    :title="__('checkins.extend_stay')" 
    icon="calendar-plus">

    <x-slot:filters>
        <x-datatable-filter seamless="true">
            <div class="col-md-4">
                <label class="form-label">{{ __('global.search') }}</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('rooms.guest_type') }}</label>
                <select name="guest_type[]" class="form-select form-select-sm multiple-select" multiple>
                    <option value="national">{{ __('rooms.national') }}</option>
                    <option value="international">{{ __('rooms.international') }}</option>
                </select>
            </div>
        </x-datatable-filter>
    </x-slot:filters>

    <x-datatables :data="$dataTable" seamless="true" />
</x-page-index>
