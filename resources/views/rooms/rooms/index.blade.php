<x-page-index 
    :title="__('rooms.room_list')" 
    icon="bed-double"
    :create-route="route('rooms.list.add')"
    :create-permission="'rooms.list.form'"
    :create-label="__('rooms.add_room')">

    <x-slot:actions>
        @can('rooms.list.form')
            <a href="{{ route('rooms.check-in') }}" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 rounded-2 px-3 fw-medium">
                <i data-lucide="log-in" style="width: 14px; height: 14px;"></i>
                <span>{{ __('rooms.check_in') }}</span>
            </a>
        @endcan
    </x-slot:actions>

    <x-slot:filters>
        <x-datatable-filter seamless="true">
            <div class="col-md-3">
                <label class="form-label">{{ __('global.search') }}</label>
                <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">{{ __('rooms.floor') }}</label>
                <select name="floor_id[]" class="form-select form-select-sm multiple-select" multiple>
                    @foreach(\App\Models\Floor::active()->get() as $floor)
                        <option value="{{ $floor->id }}">{{ $floor->localized_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">{{ __('rooms.room_type') }}</label>
                <select name="room_type_id[]" class="form-select form-select-sm multiple-select" multiple>
                    @foreach($roomTypes as $roomType)
                        <option value="{{ $roomType->id }}">{{ $roomType->localized_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2">
                <label class="form-label">{{ __('form.status') }}</label>
                <select name="status_id[]" class="form-select form-select-sm multiple-select" multiple>
                    @foreach($roomStatuses as $status)
                        <option value="{{ $status->id }}">{{ $status->localized_name }}</option>
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
