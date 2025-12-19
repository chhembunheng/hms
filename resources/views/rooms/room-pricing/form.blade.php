<x-app-layout>
    <x-form.layout :form="$form">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <x-form.select
                                        :label="__('Room Type')"
                                        name="room_type_id"
                                        :selected="old('room_type_id', $form?->room_type_id)"
                                        :options="\App\Models\RoomType::active()->pluck('name', 'id')->toArray()"
                                        required
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('rooms.price')" name="price" type="number" step="0.01" :value="old('price', $form?->price)" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form.select
                                        :label="__('rooms.pricing_type')"
                                        name="pricing_type"
                                        :selected="old('pricing_type', $form?->pricing_type ?? 'night')"
                                        :options="\App\Models\RoomPricing::getPricingTypes()"
                                        required
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-form.select
                                        :label="__('rooms.currency')"
                                        name="currency"
                                        :selected="old('currency', $form?->currency ?? 'USD')"
                                        :options="get_currencies()"
                                        required
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('rooms.effective_from')" name="effective_from" type="date" :value="old('effective_from', $form?->effective_from)" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('rooms.effective_to')" name="effective_to" type="date" :value="old('effective_to', $form?->effective_to)" />
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
