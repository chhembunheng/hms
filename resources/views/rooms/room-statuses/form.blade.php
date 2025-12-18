<x-app-layout>
    <x-form.layout :form="$form">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <x-form.input :label="__('Name')" name="name" :value="old('name', $form?->name)" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('Color')" name="color" type="color" :value="old('color', $form?->color ?? '#007bff')" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form.checkbox :label="__('Is Active')" name="is_active" :checked="old('is_active', $form?->is_active ?? true)" />
                                </div>
                                <div class="col-12">
                                    <x-form.textarea :label="__('Description')" name="description" :value="old('description', $form?->description)" rows="3" />
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
