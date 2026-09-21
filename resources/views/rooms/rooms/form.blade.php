<x-page-form 
    :title="$form?->exists ? __('rooms.edit_room') : __('rooms.add_room')"
    icon="bed-double"
    :is-edit="$form?->exists ?? false"
    :id="$form?->id"
    :back-url="route('rooms.list.index')"
    max-width="col-lg-8">

    <div class="row g-3">
        <x-form.section :title="__('rooms.room_details')" icon="info" />

        <div class="col-md-6">
            <x-form.input :label="__('rooms.room_number')" name="room_number" :value="old('room_number', $form?->room_number)" required />
        </div>
        <div class="col-md-6">
            <x-form.select
                :label="__('rooms.floor')"
                name="floor_id"
                :value="old('floor_id', $form?->floor_id)"
                :options="\App\Models\Floor::active()->get()->pluck('localized_name', 'id')->toArray()"
                :selected="old('floor_id', $form?->floor_id)"
                required
            />
        </div>
        <div class="col-md-6">
            <x-form.select
                :label="__('rooms.room_type')"
                name="room_type_id"
                :value="old('room_type_id', $form?->room_type_id)"
                :options="\App\Models\RoomType::active()->get()->pluck('localized_name', 'id')->toArray()"
                :selected="old('room_type_id', $form?->room_type_id)"
                required
            />
        </div>
        <div class="col-md-6">
            <x-form.select
                :label="__('form.status')"
                name="status_id"
                :value="old('status_id', $form?->status_id)"
                :options="\App\Models\RoomStatus::active()->get()->pluck('localized_name', 'id')->toArray()"
                :selected="old('status_id', $form?->status_id)"
                required
            />
        </div>
        <div class="col-md-6 mt-3">
            <x-form.checkbox :label="__('rooms.is_active')" name="is_active" :checked="old('is_active', $form?->is_active ?? true)" />
        </div>
    </div>
</x-page-form>
