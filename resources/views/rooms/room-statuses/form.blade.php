<x-page-form 
    :title="$form?->exists ? __('rooms.edit_room_status') : __('rooms.add_room_status')"
    icon="sparkles"
    :is-edit="$form?->exists ?? false"
    :id="$form?->id"
    :back-url="route('rooms.status.index')"
    max-width="col-lg-8">

    <div class="row g-3">
        <div class="col-md-6">
            <x-form.input :label="__('rooms.name_en')" name="name_en" :value="old('name_en', $form?->name_en)" required />
        </div>
        <div class="col-md-6">
            <x-form.input :label="__('rooms.name_kh')" name="name_kh" :value="old('name_kh', $form?->name_kh)" required />
        </div>
        <div class="col-md-6">
            <x-form.input :label="__('rooms.color')" name="color" type="color" :value="old('color', $form?->color ?? '#007bff')" required />
        </div>
        <div class="col-md-6 d-flex align-items-center">
            <x-form.checkbox :label="__('rooms.active_status')" name="is_active" :checked="old('is_active', $form?->is_active ?? true)" />
        </div>
        <div class="col-12">
            <x-form.textarea :label="__('form.description')" name="description" :value="old('description', $form?->description)" rows="3" />
        </div>
    </div>
</x-page-form>
