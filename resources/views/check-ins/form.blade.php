<x-app-layout>
    <x-form.layout :form="$form">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">{{ $form->exists ? __('rooms.edit_check_in') . ' - ' . $form->booking_number : __('rooms.add_check_in') }}</h5>
                        </div>
                        <div class="card-body">
                            <!-- Guest Information Section -->
                            <div class="row g-4 mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>{{ __('rooms.guest_name') }}</h6>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input :label="__('rooms.guest_name')" name="guest_name" :value="old('guest_name', $form?->guest_name)" required />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input :label="__('rooms.guest_email')" name="guest_email" type="email" :value="old('guest_email', $form?->guest_email)" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input :label="__('rooms.guest_phone')" name="guest_phone" :value="old('guest_phone', $form?->guest_phone)" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.select
                                        :label="__('rooms.guest_type')"
                                        name="guest_type"
                                        :value="old('guest_type', $form?->guest_type)"
                                        :options="['national' => __('rooms.national'), 'international' => __('rooms.international')]"
                                        :selected="old('guest_type', $form?->guest_type)"
                                        required
                                        id="guestType"
                                    />
                                </div>

                                <!-- National Guest Fields -->
                                <div class="col-md-6 national-fields" style="{{ old('guest_type', $form?->guest_type) == 'national' ? '' : 'display: none;' }}">
                                    <x-form.input :label="__('rooms.guest_national_id')" name="guest_national_id" :value="old('guest_national_id', $form?->guest_national_id)" />
                                </div>

                                <!-- International Guest Fields -->
                                <div class="col-md-6 international-fields" style="{{ old('guest_type', $form?->guest_type) == 'international' ? '' : 'display: none;' }}">
                                    <x-form.input :label="__('rooms.guest_passport')" name="guest_passport" :value="old('guest_passport', $form?->guest_passport)" />
                                </div>

                                <div class="col-md-6 international-fields" style="{{ old('guest_type', $form?->guest_type) == 'international' ? '' : 'display: none;' }}">
                                    <x-form.input :label="__('rooms.guest_country')" name="guest_country" :value="old('guest_country', $form?->guest_country)" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input :label="__('rooms.number_of_guests')" name="number_of_guests" type="number" :value="old('number_of_guests', $form?->number_of_guests ?? 1)" min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Booking Information Section -->
                            <div class="row g-4 mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="fas fa-calendar me-2"></i>{{ __('rooms.booking_details') }}</h6>
                                </div>

                                <div class="col-md-6">
                                    <x-form.select
                                        :label="__('rooms.room_number')"
                                        name="room_id"
                                        :value="old('room_id', $form?->room_id)"
                                        :options="$rooms->pluck('room_number', 'id')->map(function($roomNumber, $id) use ($rooms) {
                                            $room = $rooms->find($id);
                                            return $roomNumber . ' - ' . ($room->roomType->name_en ?? 'N/A') . ' (' . $room->floor . 'F)';
                                        })->toArray()"
                                        :selected="old('room_id', $form?->room_id)"
                                        required
                                    />
                                </div>

                                <div class="col-md-3">
                                    <x-form.input :label="__('rooms.check_in_date')" name="check_in_date" type="date" :value="old('check_in_date', $form?->check_in_date?->format('Y-m-d') ?? date('Y-m-d'))" required />
                                </div>

                                <div class="col-md-3">
                                    <x-form.input :label="__('rooms.check_out_date')" name="check_out_date" type="date" :value="old('check_out_date', $form?->check_out_date?->format('Y-m-d') ?? date('Y-m-d', strtotime('+1 day')))" required />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input :label="__('rooms.total_amount')" name="total_amount" type="number" step="0.01" :value="old('total_amount', $form?->total_amount)" required />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input :label="__('rooms.paid_amount')" name="paid_amount" type="number" step="0.01" :value="old('paid_amount', $form?->paid_amount)" />
                                </div>

                                @if($form->exists)
                                    <div class="col-md-6">
                                        <x-form.select
                                            :label="__('global.status')"
                                            name="status"
                                            :value="old('status', $form?->status)"
                                            :options="[
                                                'confirmed' => __('rooms.confirmed'),
                                                'checked_in' => __('rooms.checked_in'),
                                                'checked_out' => __('rooms.checked_out'),
                                                'cancelled' => __('rooms.cancelled')
                                            ]"
                                            :selected="old('status', $form?->status)"
                                        />
                                    </div>
                                @endif

                                <div class="col-12">
                                    <x-form.textarea :label="__('global.notes')" name="notes" :value="old('notes', $form?->notes)" rows="3" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('check-ins.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> {{ __('global.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-save"></i> {{ $form->exists ? __('global.update') : __('global.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-form.layout>

    <script>
        document.getElementById('guestType').addEventListener('change', function() {
            const guestType = this.value;
            const nationalFields = document.querySelectorAll('.national-fields');
            const internationalFields = document.querySelectorAll('.international-fields');

            if (guestType === 'national') {
                nationalFields.forEach(field => field.style.display = 'block');
                internationalFields.forEach(field => field.style.display = 'none');
                const nationalInput = document.querySelector('[name="guest_national_id"]');
                const passportInput = document.querySelector('[name="guest_passport"]');
                const countryInput = document.querySelector('[name="guest_country"]');
                if (nationalInput) nationalInput.setAttribute('required', 'required');
                if (passportInput) passportInput.removeAttribute('required');
                if (countryInput) countryInput.removeAttribute('required');
            } else if (guestType === 'international') {
                nationalFields.forEach(field => field.style.display = 'none');
                internationalFields.forEach(field => field.style.display = 'block');
                const nationalInput = document.querySelector('[name="guest_national_id"]');
                const passportInput = document.querySelector('[name="guest_passport"]');
                const countryInput = document.querySelector('[name="guest_country"]');
                if (nationalInput) nationalInput.removeAttribute('required');
                if (passportInput) passportInput.setAttribute('required', 'required');
                if (countryInput) countryInput.setAttribute('required', 'required');
            } else {
                nationalFields.forEach(field => field.style.display = 'none');
                internationalFields.forEach(field => field.style.display = 'none');
                const nationalInput = document.querySelector('[name="guest_national_id"]');
                const passportInput = document.querySelector('[name="guest_passport"]');
                const countryInput = document.querySelector('[name="guest_country"]');
                if (nationalInput) nationalInput.removeAttribute('required');
                if (passportInput) passportInput.removeAttribute('required');
                if (countryInput) countryInput.removeAttribute('required');
            }
        });

        // Trigger change event on page load
        document.addEventListener('DOMContentLoaded', function() {
            const guestTypeSelect = document.getElementById('guestType');
            if (guestTypeSelect && guestTypeSelect.value) {
                guestTypeSelect.dispatchEvent(new Event('change'));
            }
        });
    </script>
</x-app-layout>