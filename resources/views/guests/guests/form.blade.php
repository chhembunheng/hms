<x-app-layout>
    <x-form.layout :form="$form">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">{{ $form->exists ? __('guests.edit_guest') : __('guests.add_guest') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Personal Information -->
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>{{ __('guests.personal_information') }}</h6>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input :label="__('guests.first_name')" name="first_name" :value="old('first_name', $form?->first_name)" required />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('guests.last_name')" name="last_name" :value="old('last_name', $form?->last_name)" required />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input :label="__('guests.email')" name="email" type="email" :value="old('email', $form?->email)" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('guests.phone')" name="phone" :value="old('phone', $form?->phone)" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.select
                                        :label="__('guests.gender')"
                                        name="gender"
                                        :value="old('gender', $form?->gender)"
                                        :options="['male' => __('guests.male'), 'female' => __('guests.female'), 'other' => __('guests.other')]"
                                        :selected="old('gender', $form?->gender)"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('guests.date_of_birth')" name="date_of_birth" type="date" :value="old('date_of_birth', $form?->date_of_birth?->format('Y-m-d'))" />
                                </div>

                                <!-- Identification -->
                                <div class="col-12 mt-4">
                                    <h6 class="text-primary mb-3"><i class="fas fa-id-card me-2"></i>{{ __('guests.identification') }}</h6>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input :label="__('guests.national_id')" name="national_id" :value="old('national_id', $form?->national_id)" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('guests.passport')" name="passport" :value="old('passport', $form?->passport)" />
                                </div>

                                <!-- Guest Details -->
                                <div class="col-12 mt-4">
                                    <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>{{ __('guests.guest_details') }}</h6>
                                </div>

                                <div class="col-md-6">
                                    <x-form.select
                                        :label="__('guests.guest_type')"
                                        name="guest_type"
                                        :value="old('guest_type', $form?->guest_type)"
                                        :options="['national' => __('guests.national'), 'international' => __('guests.international')]"
                                        :selected="old('guest_type', $form?->guest_type)"
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('guests.country')" name="country" :value="old('country', $form?->country)" />
                                </div>

                                <!-- Address Information -->
                                <div class="col-12 mt-4">
                                    <h6 class="text-primary mb-3"><i class="fas fa-map-marker-alt me-2"></i>{{ __('guests.address_information') }}</h6>
                                </div>

                                <div class="col-md-12">
                                    <x-form.textarea :label="__('guests.address')" name="address" :value="old('address', $form?->address)" rows="2" />
                                </div>

                                <div class="col-md-4">
                                    <x-form.input :label="__('guests.city')" name="city" :value="old('city', $form?->city)" />
                                </div>
                                <div class="col-md-4">
                                    <x-form.input :label="__('guests.state')" name="state" :value="old('state', $form?->state)" />
                                </div>
                                <div class="col-md-4">
                                    <x-form.input :label="__('guests.postal_code')" name="postal_code" :value="old('postal_code', $form?->postal_code)" />
                                </div>

                                <!-- Emergency Contact -->
                                <div class="col-12 mt-4">
                                    <h6 class="text-primary mb-3"><i class="fas fa-phone me-2"></i>{{ __('guests.emergency_contact') }}</h6>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input :label="__('guests.emergency_contact_name')" name="emergency_contact_name" :value="old('emergency_contact_name', $form?->emergency_contact_name)" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('guests.emergency_contact_phone')" name="emergency_contact_phone" :value="old('emergency_contact_phone', $form?->emergency_contact_phone)" />
                                </div>

                                <!-- Additional Notes -->
                                <div class="col-12 mt-4">
                                    <h6 class="text-primary mb-3"><i class="fas fa-sticky-note me-2"></i>{{ __('guests.additional_notes') }}</h6>
                                </div>

                                <div class="col-md-12">
                                    <x-form.textarea :label="__('guests.notes')" name="notes" :value="old('notes', $form?->notes)" rows="3" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-save me-2"></i>{{ __('form.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-form.layout>
</x-app-layout>
