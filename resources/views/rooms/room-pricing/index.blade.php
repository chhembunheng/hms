<x-page-index 
    :title="__('rooms.room_pricing')" 
    icon="receipt"
    :create-route="route('rooms.pricing.add')"
    :create-permission="'rooms.pricing.form'"
    :create-label="__('rooms.add_room_pricing')">

    <x-slot:filters>
        <x-datatable-filter seamless="true">
            <div class="col-md-3">
                <label class="form-label">{{ __('global.search') }}</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('rooms.room_type') }}</label>
                <select name="room_type_id[]" class="form-select form-select-sm multiple-select" multiple>
                    @foreach($roomTypes as $roomType)
                        <option value="{{ $roomType->id }}">{{ $roomType->localized_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">{{ __('rooms.pricing_type') }}</label>
                <select name="pricing_type[]" class="form-select form-select-sm multiple-select" multiple>
                    @foreach(\App\Models\RoomPricing::getPricingTypes() as $key => $value)
                        <option value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">{{ __('rooms.currency') }}</label>
                <select name="currency[]" class="form-select form-select-sm multiple-select" multiple>
                    @foreach(get_currencies() as $code => $name)
                        <option value="{{ $code }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
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
