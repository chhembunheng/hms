<x-page-form 
    :title="$form?->exists ? __('rooms.edit_room_type') : __('rooms.add_room_type')"
    icon="bed-double"
    :is-edit="$form?->exists ?? false"
    :id="$form?->id"
    :back-url="route('rooms.type.index')"
    max-width="col-lg-8">

    <div class="row g-3">
        <div class="col-md-6">
            <x-form.input :label="__('global.name_en')" name="name_en" :value="old('name_en', $form?->name_en)" required />
        </div>
        <div class="col-md-6">
            <x-form.input :label="__('global.name_kh')" name="name_kh" :value="old('name_kh', $form?->name_kh)" required />
        </div>
        <div class="col-12">
            <x-form.textarea :label="__('rooms.description')" name="description" :value="old('description', $form?->description)" rows="3" />
        </div>
        <div class="col-md-6">
            <x-form.checkbox :label="__('rooms.is_active')" name="is_active" :checked="old('is_active', $form?->is_active ?? true)" />
        </div>
    </div>
</x-page-form>
