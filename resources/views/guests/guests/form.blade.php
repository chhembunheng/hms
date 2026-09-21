<x-page-form 
    :title="$form?->exists ? __('guests.edit_guest') : __('guests.add_guest')"
    icon="users"
    :is-edit="$form?->exists ?? false"
    :id="$form?->id"
    :back-url="route('guests.list.index')"
    max-width="col-lg-10 col-xl-9">

    <div class="row g-3">
        <!-- Personal Information -->
        <x-form.section :title="__('guests.personal_information')" icon="user" />

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
            <x-form.input :label="__('guests.date_of_birth')" name="date_of_birth" type="text" class="datepicker" placeholder="dd-mm-yyyy" autocomplete="off" :value="old('date_of_birth', $form?->date_of_birth?->format('d-m-Y'))" />
        </div>

        <!-- Identification -->
        <x-form.section :title="__('guests.identification')" icon="id-card" />

        <div class="col-md-6">
            <x-form.input :label="__('guests.national_id')" name="national_id" :value="old('national_id', $form?->national_id)" />
        </div>
        <div class="col-md-6">
            <x-form.input :label="__('guests.passport')" name="passport" :value="old('passport', $form?->passport)" />
        </div>

        <!-- Guest Details -->
        <x-form.section :title="__('guests.guest_details')" icon="info" />

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
        <x-form.section :title="__('guests.address_information')" icon="map-pin" />

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
        <x-form.section :title="__('guests.emergency_contact')" icon="phone" />

        <div class="col-md-6">
            <x-form.input :label="__('guests.emergency_contact_name')" name="emergency_contact_name" :value="old('emergency_contact_name', $form?->emergency_contact_name)" />
        </div>
        <div class="col-md-6">
            <x-form.input :label="__('guests.emergency_contact_phone')" name="emergency_contact_phone" :value="old('emergency_contact_phone', $form?->emergency_contact_phone)" />
        </div>

        <!-- Additional Notes -->
        <x-form.section :title="__('guests.additional_notes')" icon="file-text" />

        <div class="col-md-12">
            <x-form.textarea :label="__('guests.notes')" name="notes" :value="old('notes', $form?->notes)" rows="3" />
        </div>
    </div>
</x-page-form>
