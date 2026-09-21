<x-page-index 
    :title="__('guests.guest_list')" 
    icon="users"
    :create-route="route('guests.list.add')"
    :create-permission="'guests.list.form'"
    :create-label="__('guests.add_guest')">

    <x-slot:filters>
        <x-datatable-filter seamless="true">
            <div class="col-md-3">
                <label class="form-label">{{ __('global.search') }}</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('guests.guest_type') }}</label>
                <select name="guest_type[]" class="form-select form-select-sm multiple-select" multiple>
                    <option value="national">{{ __('guests.national') }}</option>
                    <option value="international">{{ __('guests.international') }}</option>
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('guests.country') }}</label>
                <input type="text" name="country" class="form-control form-control-sm" placeholder="{{ __('guests.country') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('global.created_at') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="created_from" class="form-control form-control-sm pickadate" placeholder="dd-mm-yyyy" autocomplete="off">
                    <span class="input-group-text"><i data-lucide="calendar" style="width: 13px; height: 13px;"></i></span>
                    <input type="text" name="created_to" class="form-control form-control-sm pickadate" placeholder="dd-mm-yyyy" autocomplete="off">
                </div>
            </div>
        </x-datatable-filter>
    </x-slot:filters>

    <x-datatables :data="$dataTable" seamless="true" />
</x-page-index>
