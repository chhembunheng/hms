<x-page-form 
    :title="$form?->exists ? __('rooms.edit_room_pricing') : __('rooms.add_room_pricing')"
    icon="badge-dollar-sign"
    :is-edit="$form?->exists ?? false"
    :id="$form?->id"
    :back-url="route('rooms.pricing.index')"
    max-width="col-lg-8">

    <div class="row g-3">
        <div class="col-md-6">
            <x-form.select
                :label="__('rooms.room_type')"
                name="room_type_id"
                :selected="old('room_type_id', $form?->room_type_id)"
                :options="\App\Models\RoomType::active()->get()->pluck('localized_name', 'id')->toArray()"
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
            <x-form.input :label="__('rooms.effective_from')" name="effective_from" type="text" class="datepicker" placeholder="dd-mm-yyyy" autocomplete="off" :value="old('effective_from', format_date($form?->effective_from))" required />
        </div>
        <div class="col-md-6">
            <x-form.input :label="__('rooms.effective_to')" name="effective_to" type="text" class="datepicker" placeholder="dd-mm-yyyy" autocomplete="off" :value="old('effective_to', format_date($form?->effective_to))" />
        </div>
        <div class="col-md-6 d-flex align-items-center">
            <x-form.checkbox :label="__('rooms.is_active')" name="is_active" :checked="old('is_active', $form?->is_active ?? true)" />
        </div>
    </div>
</x-page-form>
