<x-page-index 
    :title="__('checkins.void_cancelled_stays')" 
    icon="ban">

    <x-slot:filters>
        <x-datatable-filter seamless="true">
            <div class="col-md-4">
                <label class="form-label">{{ __('global.search') }}</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('checkins.guest_type') }}</label>
                <select name="guest_type[]" class="form-select form-select-sm multiple-select" multiple>
                    <option value="national">{{ __('checkins.national') }}</option>
                    <option value="international">{{ __('checkins.international') }}</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('checkins.cancelled_date_range') }}</label>
                <input type="text" name="cancelled_date" class="form-control form-control-sm daterange" placeholder="dd-mm-yyyy - dd-mm-yyyy" autocomplete="off">
            </div>
        </x-datatable-filter>
    </x-slot:filters>

    <x-datatables :data="$dataTable" seamless="true" />
</x-page-index>
