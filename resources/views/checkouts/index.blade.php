<x-page-index 
    :title="__('checkins.checkout_list')" 
    icon="log-out">

    <x-slot:filters>
        <x-datatable-filter seamless="true">
            <div class="col-md-3">
                <label class="form-label">{{ __('global.search') }}</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('checkins.check_out_date') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="check_out_from" class="form-control form-control-sm pickadate" placeholder="dd-mm-yyyy" autocomplete="off">
                    <span class="input-group-text"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('global.to') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="check_out_to" class="form-control form-control-sm pickadate" placeholder="dd-mm-yyyy" autocomplete="off">
                    <span class="input-group-text"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                </div>
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('checkins.payment_status') }}</label>
                <select name="paid_status" class="form-select form-select-sm">
                    <option value="">{{ __('global.all') }}</option>
                    <option value="paid">{{ __('checkins.paid') }}</option>
                    <option value="partial">{{ __('checkins.partial') }}</option>
                    <option value="unpaid">{{ __('checkins.unpaid') }}</option>
                </select>
            </div>
        </x-datatable-filter>
    </x-slot:filters>

    <x-datatables :data="$dataTable" seamless="true" />
</x-page-index>
