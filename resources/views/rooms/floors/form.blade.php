<x-page-form 
    :title="$form?->exists ? __('rooms.edit_floor') : __('rooms.add_floor')"
    icon="layers"
    :is-edit="$form?->exists ?? false"
    :id="$form?->id"
    :back-url="route('rooms.floor.index')"
    max-width="col-lg-8">

    <div class="row g-3">
        <div class="col-md-6">
            <x-form.input :label="__('rooms.floor_number')" name="floor_number" :value="old('floor_number', $form?->floor_number)" required />
        </div>
        <div class="col-md-6">
            <x-form.input :label="__('rooms.name_en')" name="name_en" :value="old('name_en', $form?->name_en)" required />
        </div>
        <div class="col-md-6">
            <x-form.input :label="__('rooms.name_kh')" name="name_kh" :value="old('name_kh', $form?->name_kh)" required />
        </div>
        <div class="col-md-6 d-flex align-items-center">
            <x-form.checkbox :label="__('rooms.is_active')" name="is_active" :checked="old('is_active', $form?->is_active ?? true)" />
        </div>
    </div>
</x-page-form>
