<x-app-layout>
    <x-form.layout :form="$form">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header" style="background: #034246">
                            <h5 class="mb-0" style="color: #ffffff;">{{ $form->exists ? __('rooms.edit_check_in') . ' - ' . $form->booking_number : __('checkins.add_walk_in') }}</h5>
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

                                <!-- Billing Type Selection (First) -->
                                <div class="col-md-6">
                                    <x-form.select
                                        label="{{ __('rooms.billing_type') }}"
                                        name="billing_type"
                                        id="billing_type"
                                        :value="old('billing_type', $form?->billing_type ?? 'night')"
                                        :options="['night' => __('rooms.per_night'), '3_hours' => __('rooms.per_3_hours')]"
                                        :selected="old('billing_type', $form?->billing_type ?? 'night')"
                                        required
                                    />
                                </div>

                                <!-- Check-in Date -->
                                <div class="col-md-6">
                                    <x-form.input label="{{ __('rooms.check_in_date') }}" name="check_in_date" type="date" :value="old('check_in_date', $form?->check_in_date?->format('Y-m-d') ?? date('Y-m-d'))" required />
                                </div>

                                <!-- Time Selection (only for 3-hour billing) -->
                                <div class="col-md-6 time-selection-fields" style="display: none;">
                                    <x-form.input
                                        label="{{ __('rooms.check_in_time') }}"
                                        name="check_in_time"
                                        type="time"
                                        :value="old('check_in_time', $form?->check_in_time ?? '09:00')"
                                        id="check_in_time"
                                    />
                                </div>

                                <!-- Total Periods/Days -->
                                <div class="col-md-6">
                                    <x-form.input label="{{ __('rooms.total_days') }}" name="total_days" type="number" :value="old('total_days', $form->exists && $form->check_in_date && $form->check_out_date ? \Carbon\Carbon::parse($form->check_in_date)->diffInDays(\Carbon\Carbon::parse($form->check_out_date)) : 1)" min="1" required id="total_days_input" />
                                    <input type="hidden" name="check_out_date" id="check_out_date" value="{{ old('check_out_date', $form?->check_out_date?->format('Y-m-d') ?? date('Y-m-d', strtotime('+1 day'))) }}">
                                    <input type="hidden" name="check_out_time" id="check_out_time" value="{{ old('check_out_time', $form?->check_out_time ?? '12:00') }}">
                                </div>

                                <!-- Room Selection (After billing type is selected) -->
                                <div class="col-md-12">
                                    <label for="room_select" class="form-label">{{ __('rooms.room_number') }} <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm multiple-select"
                                            multiple
                                            name="room_ids[]"
                                            id="room_select"
                                            data-server="{{ route('api.rooms') }}"
                                            data-filters="">
                                        <option value="">{{ __('rooms.select_rooms') }}</option>
                                    </select>
                                    <input type="hidden" name="room_ids" id="room_ids" value="{{ old('room_ids', $form->exists ? $form->checkInRooms->pluck('room_id')->implode(',') : '') }}">
                                    <input type="hidden" name="room_guests" id="room_guests" value="{{ old('room_guests', $form->exists ? $form->checkInRooms->mapWithKeys(function($item) { return [$item->room_id => $item->number_of_guests]; })->toJson() : '') }}">
                                    <div class="selected-rooms mt-2" id="selected-rooms"></div>
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
            // Initialize room selection
            initializeRoomSelect();

            // Initialize UI based on current billing type
            updatePeriodLabel();
            toggleTimeSelection();

            // Event handlers are now handled in the multiselect onChange callback above

            $('#billing_type').on('change', function() {
                var $select = $(this);

                // Process the change
                updatePeriodLabel();
                toggleTimeSelection();
                refreshRoomOptions();
                if ($('#room_select').val() && $('#room_select').val().length > 0) {
                    fetchRoomDetails();
                }
                calculateTotal();

                // Ensure the select dropdown closes after a short delay
                setTimeout(function() {
                    $select.blur();
                    // Also try to close any open dropdowns
                    $('.dropdown-menu').removeClass('show');
                    $('[data-bs-toggle="dropdown"]').removeClass('show');
                }, 100);
            });

            $('#check_in_date, #total_days_input, #check_in_time').on('change', function() {
                calculateCheckOutDate();
                calculateTotal();
                refreshRoomOptions();
            });

            calculateCheckOutDate();
            calculateTotal();
            updateSelectedRooms();

            // Load room details for selected rooms (will be called after multiselect initializes)
            setTimeout(function() {
                if ($('#room_select').val() && $('#room_select').val().length > 0) {
                    fetchRoomDetails();
                }

                // Load existing guest data for editing
                @if($form->exists && $form->checkInRooms && $form->checkInRooms->count() > 0)
                    var existingGuests = @json($form->checkInRooms->mapWithKeys(function($item) { return [$item->room_id => $item->number_of_guests]; })->toArray());
                    for (var roomId in existingGuests) {
                        $('.room-guests[data-room-id="' + roomId + '"]').val(existingGuests[roomId]);
                    }
                    updateSelectedRooms();
                    calculateTotal();
                @endif
            }, 2000); // Give more time for multiselect to initialize

            // Initialize total days for existing records
            @if($form->exists && $form->check_in_date && $form->check_out_date)
                var existingCheckIn = new Date('{{ $form->check_in_date->format('Y-m-d') }}');
                var existingCheckOut = new Date('{{ $form->check_out_date->format('Y-m-d') }}');
                var existingDiffTime = Math.abs(existingCheckOut - existingCheckIn);
                var existingDiffDays = Math.ceil(existingDiffTime / (1000 * 60 * 60 * 24));
                $('#total_days_input').val(existingDiffDays);
                calculateCheckOutDate();
            @endif

            // Initialize for existing 3-hour bookings
            @if($form->exists && $form->billing_type === '3_hours' && $form->check_in_time)
                $('#check_in_time').val('{{ $form->check_in_time->format('H:i') }}');
                calculateCheckOutDate();
            @endif
        });

        function updateSelectedRooms() {
            var selectedRooms = $('#room_select').val() || [];
            $('#room_ids').val(selectedRooms.join(','));

            // Update room guests data
            var roomGuests = {};
            $('.room-guests').each(function() {
                var roomId = $(this).data('room-id');
                var guestCount = parseInt($(this).val()) || 1;
                roomGuests[roomId] = guestCount;
            });
            $('#room_guests').val(JSON.stringify(roomGuests));
        }

        function fetchRoomDetails() {
            var selectedRooms = $('#room_select').val() || [];
            if (selectedRooms.length === 0) {
                $('#price_per_night').val('');
                $('#selected-rooms').empty();
                calculateTotal();
                return;
            }

            // Fetch details for all selected rooms
            $.ajax({
                url: '{{ route("api.room-details") }}',
                type: 'GET',
                data: {
                    room_ids: selectedRooms.join(','),
                    billing_type: $('#billing_type').val()
                },
                success: function(data) {
                    if (data.rooms) {
                        // Multiple rooms response
                        displaySelectedRooms(data.rooms);
                    } else {
                        // Single room response (backward compatibility)
                        displaySelectedRooms([{
                            id: selectedRooms[0],
                            room_number: data.room_number,
                            room_type: data.room_type,
                            price: data.price
                        }]);
                    }
                    calculateTotal();
                },
                error: function() {
                    alert('Error fetching room details');
                }
            });
        }

        let selectedRoomsData = [];

        function displaySelectedRooms(rooms) {
            var container = $('#selected-rooms');
            container.empty();
            selectedRoomsData = rooms; // Store for calculation
            var billingType = $('#billing_type').val();

            if (rooms.length === 0) {
                return;
            }

            var html = '<div class="row g-2">';

            rooms.forEach(function(room, index) {
                html += `
                    <div class="col-md-6">
                        <div class="card border">
                            <div class="card-body p-2">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <small class="text-muted">
                                            <strong>${room.room_number}</strong> - ${room.room_type}
                                        </small>
                                        <div class="mt-1">
                                            <small class="text-primary fw-bold">$${billingType === '3_hours' ? (room.price_3_hours || (room.price / 8).toFixed(2)) : room.price}/${billingType === '3_hours' ? '3hrs' : 'night'}</small>
                                        </div>
                                    </div>
                                    <div class="text-end">
                                        <label class="form-label small mb-1">Guests</label>
                                        <input type="number" class="form-control form-control-sm room-guests"
                                               data-room-id="${room.id}"
                                               value="1" min="1" max="10"
                                               style="width: 60px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            html += '</div>';
            container.html(html);

            // Bind change event to guest inputs
            $('.room-guests').on('change', function() {
                updateSelectedRooms();
                calculateTotal();
            });

            calculateTotal();
        }

        function calculateCheckOutDate() {
            var billingType = $('#billing_type').val();
            var checkInDate = $('#check_in_date').val();
            var checkInTime = $('#check_in_time').val();

            if (!checkInDate) return;

            if (billingType === '3_hours' && checkInTime) {
                // For 3-hour billing, calculate end time (fixed 3 hours)
                var startDateTime = new Date(checkInDate + 'T' + checkInTime);
                var endDateTime = new Date(startDateTime);
                endDateTime.setHours(startDateTime.getHours() + 3); // Fixed 3 hours

                var checkOutDate = endDateTime.toISOString().split('T')[0];
                var checkOutTime = endDateTime.toTimeString().substring(0, 5);

                $('#check_out_date').val(checkOutDate);
                $('#check_out_time').val(checkOutTime);
                // For 3-hour billing, total periods is always 1 (one booking session)
                $('#total_days_input').val(1);
            } else {
                // For nightly billing, calculate end date based on total days
                var totalDays = parseInt($('#total_days_input').val()) || 1;
                var start = new Date(checkInDate);
                var end = new Date(start);
                end.setDate(start.getDate() + totalDays);
                var checkOutDate = end.toISOString().split('T')[0];
                $('#check_out_date').val(checkOutDate);
                $('#check_out_time').val('12:00'); // Default checkout time for nightly
            }
        }

        function calculateTotal() {
            var billingType = $('#billing_type').val();
            var periods = 1; // Default for 3-hour billing
            var totalPricePerPeriod = 0;

            if (billingType === 'night') {
                periods = parseInt($('#total_days_input').val()) || 0;
            }

            // Sum up prices of all selected rooms
            selectedRoomsData.forEach(function(room) {
                if (billingType === '3_hours') {
                    // For 3-hour billing, use the 3-hour price (fixed 3 hours)
                    totalPricePerPeriod += parseFloat(room.price_3_hours || (room.price / 8)); // 24 hours / 3 hours = 8 periods per day
                } else {
                    // For nightly billing
                    totalPricePerPeriod += parseFloat(room.price);
                }
            });

            var total = periods * totalPricePerPeriod;
            $('#total_amount').val(total.toFixed(2));

            // Update the label for price per period
            var periodLabel = billingType === '3_hours' ? 'price_per_3_hours' : 'price_per_night';
            $('#price_per_night').attr('name', periodLabel).val(totalPricePerPeriod.toFixed(2));
        }

        $('#guestType').on('change', function() {
            var selectedType = $(this).val();
            if (selectedType === 'national') {
                $('.national-fields').show();
                $('.international-fields').hide();
            } else if (selectedType === 'international') {
                $('.national-fields').hide();
                $('.international-fields').show();
            } else {
                $('.national-fields').hide();
                $('.international-fields').hide();
            }
        });

        function updatePeriodLabel() {
            var billingType = $('#billing_type').val();
            var label = billingType === '3_hours' ? '{{ __("rooms.total_3_hour_periods") }}' : '{{ __("rooms.total_days") }}';
            $('label[for="total_days_input"]').text(label);
        }

        // Initialize period label on page load
        updatePeriodLabel();
        toggleTimeSelection();

        function refreshRoomOptions() {
            var billingType = $('#billing_type').val();
            var checkInDate = $('#check_in_date').val();
            var checkInTime = $('#check_in_time').val();
            var checkOutDate = $('#check_out_date').val();
            var checkOutTime = $('#check_out_time').val();

            if (!billingType || !checkInDate || !checkOutDate) {
                return;
            }

            var $roomSelect = $('#room_select');
            if ($roomSelect.data('multiselect')) {
                // Update the server URL with current parameters
                var serverUrl = '{{ route("api.rooms") }}';
                serverUrl += '?billing_type=' + encodeURIComponent(billingType);
                serverUrl += '&check_in_date=' + encodeURIComponent(checkInDate);
                serverUrl += '&check_out_date=' + encodeURIComponent(checkOutDate);
                if (billingType === '3_hours' && checkInTime && checkOutTime) {
                    serverUrl += '&check_in_time=' + encodeURIComponent(checkInTime);
                    serverUrl += '&check_out_time=' + encodeURIComponent(checkOutTime);
                }

                // Reinitialize multiselect with new server URL
                $roomSelect.multiselect('destroy');
                $roomSelect.attr('data-server', serverUrl);
                initializeRoomSelect();
            }
        }

        function initializeRoomSelect() {
            $('.multiple-select').each(function() {
                const $select = $(this);
                const serverUrl = $select.data('server');

                // Load all options from server initially
                $.ajax({
                    url: serverUrl,
                    type: 'GET',
                    success: function(data) {
                        $select.empty();
                        data.results.forEach(function(option) {
                            $select.append(new Option(option.text, option.id, false, false));
                        });

                        // Initialize multiselect
                        $select.multiselect({
                            enableFiltering: true,
                            enableCaseInsensitiveFiltering: true,
                            filterPlaceholder: 'Search rooms...',
                            nonSelectedText: 'Select rooms',
                            nSelectedText: 'rooms selected',
                            allSelectedText: 'All rooms selected',
                            selectAllText: 'Select all',
                            maxHeight: 200,
                            buttonWidth: '100%',
                            onChange: function(option, checked) {
                                updateSelectedRooms();
                                fetchRoomDetails();
                            },
                            onSelectAll: function() {
                                updateSelectedRooms();
                                fetchRoomDetails();
                            },
                            onDeselectAll: function() {
                                $('#selected-rooms').empty();
                                selectedRoomsData = [];
                                calculateTotal();
                            }
                        });

                        // Set selected values after multiselect is initialized
                        @if($form->exists && $form->checkInRooms)
                            var selectedValues = @json($form->checkInRooms->pluck('room_id')->toArray());
                            $select.val(selectedValues);
                            $select.multiselect('refresh');
                        @elseif(old('room_ids'))
                            @php
                                $selectedRoomIds = is_array(old('room_ids')) ? old('room_ids') : explode(',', old('room_ids'));
                            @endphp
                            var selectedValues = @json($selectedRoomIds);
                            $select.val(selectedValues);
                            $select.multiselect('refresh');
                        @endif
                    }
                });
            });
        }


    </script>
    @endpush
</x-app-layout>
