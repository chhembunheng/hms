<x-app-layout>
    <x-form.layout :form="$form">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">{{ $form->exists ? __('rooms.edit_check_in') . ' - ' . $form->booking_number : __('checkins.add_walk_in') }}</h5>
                        </div>
                        <div class="card-body">
                            <!-- Guest Information Section -->
                            <div class="row g-4 mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>{{ __('rooms.guest_name') }}</h6>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input label="{{ __('rooms.guest_name') }}" name="guest_name" :value="old('guest_name', $form?->guest_name)" required />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input label="{{ __('rooms.guest_email') }}" name="guest_email" type="email" :value="old('guest_email', $form?->guest_email)" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input label="{{ __('rooms.guest_phone') }}" name="guest_phone" :value="old('guest_phone', $form?->guest_phone)" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.select
                                        label="{{ __('rooms.guest_type') }}"
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
                                    <x-form.input label="{{ __('rooms.guest_national_id') }}" name="guest_national_id" :value="old('guest_national_id', $form?->guest_national_id)" />
                                </div>

                                <!-- International Guest Fields -->
                                <div class="col-md-6 international-fields" style="{{ old('guest_type', $form?->guest_type) == 'international' ? '' : 'display: none;' }}">
                                    <x-form.input label="{{ __('rooms.guest_passport') }}" name="guest_passport" :value="old('guest_passport', $form?->guest_passport)" />
                                </div>

                                <div class="col-md-6 international-fields" style="{{ old('guest_type', $form?->guest_type) == 'international' ? '' : 'display: none;' }}">
                                    <x-form.input label="{{ __('rooms.guest_country') }}" name="guest_country" :value="old('guest_country', $form?->guest_country)" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input label="{{ __('rooms.number_of_guests') }}" name="number_of_guests" type="number" :value="old('number_of_guests', $form?->number_of_guests ?? 1)" min="1" max="10" required />
                                </div>
                            </div>

                            <!-- Booking Information Section -->
                            <div class="row g-4 mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="fas fa-calendar me-2"></i>{{ __('rooms.booking_details') }}</h6>
                                </div>

                                <div class="col-md-6">
                                    <label for="room_select" class="form-label">{{ __('rooms.room_number') }} <span class="text-danger">*</span></label>
                                    <select id="room_select" class="form-select form-select-sm" required>
                                        @if($form->exists && $form->room)
                                            <option value="{{ $form->room_id }}" selected>
                                                {{ $form->room->room_number }} - {{ $form->room->roomType->name_en ?? '' }} ({{ $form->room->floor }}F)
                                            </option>
                                        @elseif(old('room_id') && $rooms->find(old('room_id')))
                                            <option value="{{ old('room_id') }}" selected>
                                                {{ $rooms->find(old('room_id'))->room_number }} - {{ $rooms->find(old('room_id'))->roomType->name_en ?? '' }} ({{ $rooms->find(old('room_id'))->floor }}F)
                                            </option>
                                        @endif
                                    </select>
                                    <input type="hidden" name="room_id" id="room_id" value="{{ old('room_id', $form?->room_id) }}">
                                </div>

                                <div class="col-md-3">
                                    <x-form.input label="{{ __('rooms.check_in_date') }}" name="check_in_date" type="text" :value="old('check_in_date', $form?->check_in_date?->format('Y-m-d') ?? date('Y-m-d'))" required class="datepicker" />
                                </div>

                                <div class="col-md-3">
                                    <x-form.input label="{{ __('rooms.check_out_date') }}" name="check_out_date" type="text" :value="old('check_out_date', $form?->check_out_date?->format('Y-m-d') ?? date('Y-m-d', strtotime('+1 day')))" required id="check_out_date" class="datepicker" />
                                </div>

                                <div class="col-md-3">
                                    <x-form.input label="{{ __('rooms.total_days') }}" name="total_days" type="number" :value="old('total_days')" readonly id="total_days" />
                                </div>

                                <div class="col-md-3">
                                    <x-form.input label="{{ __('rooms.price_per_night') }}" name="price_per_night" type="number" step="0.01" :value="old('price_per_night')" readonly id="price_per_night" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input label="{{ __('rooms.total_amount') }}" name="total_amount" type="number" step="0.01" :value="old('total_amount', $form?->total_amount)" required readonly id="total_amount" />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input label="{{ __('rooms.paid_amount') }}" name="paid_amount" type="number" step="0.01" :value="old('paid_amount', $form?->paid_amount)" />
                                </div>

                                @if($form->exists)
                                    <div class="col-md-6">
                                        <x-form.select
                                            label="{{ __('form.status') }}"
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
                                @else
                                    <input type="hidden" name="status" value="checked_in">
                                @endif

                                <div class="col-12">
                                    <x-form.textarea label="{{ __('global.notes') }}" name="notes" :value="old('notes', $form?->notes)" rows="3" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('checkin.walkin.index') }}" class="btn btn-secondary px-4 py-2">
                            <i class="fas fa-times"></i> {{ __('rooms.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-save"></i> {{ $form->exists ? __('rooms.update') : __('rooms.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-form.layout>

    @push('scripts')
    <script>
        $(document).ready(function() {

            $('#room_select').select2({
                placeholder: '{{ __("rooms.select_room") }}',
                ajax: {
                    url: '{{ route("api.rooms") }}',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: data.results,
                            pagination: data.pagination
                        };
                    },
                    cache: true
                },
                minimumInputLength: 0,
                allowClear: true
            });

            $('#room_select').on('select2:select', function (e) {
                var roomId = e.params.data.id;
                $('#room_id').val(roomId);
                fetchRoomDetails(roomId);
            });

            $('#room_select').on('select2:clear', function () {
                $('#room_id').val('');
                $('#price_per_night').val('');
                calculateTotal();
            });

            // Initialize datepickers for this form
            $('.datepicker').each(function() {
                if (!$(this).data('datepicker')) {
                    new Datepicker(this, {
                        format: 'yyyy-mm-dd',
                        autohide: true,
                        todayBtn: true,
                        clearBtn: true,
                        todayBtnMode: 1,
                        todayHighlight: true
                    });
                }
            });

            $('#check_in_date, #check_out_date').on('change', function() {
                calculateDays();
                calculateTotal();
            });

            calculateDays();
            calculateTotal();
            if ($('#room_id').val()) {
                fetchRoomDetails($('#room_id').val());
            }
        });

        function fetchRoomDetails(roomId) {
            $.ajax({
                url: '{{ route("api.room-details") }}',
                type: 'GET',
                data: { room_id: roomId },
                success: function(data) {
                    $('#price_per_night').val(data.price);
                    calculateTotal();
                },
                error: function() {
                    alert('Error fetching room details');
                }
            });
        }

        function calculateDays() {
            var checkIn = $('#check_in_date').val();
            var checkOut = $('#check_out_date').val();
            if (checkIn && checkOut) {
                var start = new Date(checkIn);
                var end = new Date(checkOut);
                var diffTime = Math.abs(end - start);
                var diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                $('#total_days').val(diffDays);
            } else {
                $('#total_days').val('');
            }
        }

        function calculateTotal() {
            var days = parseFloat($('#total_days').val()) || 0;
            var price = parseFloat($('#price_per_night').val()) || 0;
            var total = days * price;
            $('#total_amount').val(total.toFixed(2));
        }

        // Existing guest type logic
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
    @endpush
</x-app-layout>
