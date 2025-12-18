<x-app-layout>
    <x-form.layout :form="$form">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <x-form.input :label="__('Room Number')" name="room_number" :value="old('room_number', $form?->room_number)" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('Floor')" name="floor" type="number" :value="old('floor', $form?->floor)" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.select
                                        :label="__('Room Type')"
                                        name="room_type_id"
                                        :value="old('room_type_id', $form?->room_type_id)"
                                        :options="\App\Models\RoomType::active()->pluck('name', 'id')->toArray()"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-form.select
                                        :label="__('Status')"
                                        name="status_id"
                                        :value="old('status_id', $form?->status_id)"
                                        :options="\App\Models\RoomStatus::active()->pluck('name', 'id')->toArray()"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-form.checkbox :label="__('Is Active')" name="is_active" :checked="old('is_active', $form?->is_active ?? true)" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4 py-2">{{ __('form.save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </x-form.layout>
</x-app-layout>
