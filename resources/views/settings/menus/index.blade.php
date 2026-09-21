<x-page-index 
    :title="__('global.menu_management')" 
    icon="menu">

    <x-slot:filters>
        <x-datatable-filter seamless="true">
            <div class="col-md-4">
                <label class="form-label">{{ __('global.search') }}</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label">{{ __('global.menus') }}</label>
                <select class="form-select form-select-sm multiple-select"
                        multiple
                        name="menu_ids[]"
                        id="filter-menu"
                        data-server="{{ route('settings.menus.select2') }}"
                        data-filters="">
                </select>
            </div>
        </x-datatable-filter>
    </x-slot:filters>

    <x-datatables :data="$dataTable" seamless="true" />
</x-page-index>
