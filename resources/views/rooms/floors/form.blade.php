<x-app-layout>
    <x-form.layout :form="$form">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <x-form.input :label="__('rooms.floor_number')" name="floor_number" :value="old('floor_number', $form?->floor_number)" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('rooms.name_en')" name="name_en" :value="old('name_en', $form?->name_en)" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('rooms.name_kh')" name="name_kh" :value="old('name_kh', $form?->name_kh)" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form.checkbox :label="__('rooms.is_active')" name="is_active" :checked="old('is_active', $form?->is_active ?? true)" />
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
