{{-- Check-in POS Panel - Shows selected rooms and billing details --}}
<div class="card h-100">
    <div class="card-header bg-success text-white">
        <h5 class="card-title mb-0">
            <i class="fa-solid fa-shopping-cart fa-fw me-2"></i>
            Booking Summary
        </h5>
    </div>
    <div class="card-body d-flex flex-column">
        {{-- Selected Rooms List --}}
        <div class="flex-grow-1" style="min-height: 300px;">
            <h6 class="mb-3">
                <i class="fa-solid fa-bed fa-fw me-2"></i>
                Selected Rooms
                <span class="badge bg-primary ms-2" id="selected-rooms-count">0</span>
            </h6>

            <div id="selected-rooms-list" class="mb-3">
                <div class="text-center text-muted py-4">
                    <i class="fa-solid fa-bed fa-2x mb-2"></i>
                    <p class="mb-0">No rooms selected</p>
                    <small>Select rooms from the grid</small>
                </div>
            </div>
        </div>

        {{-- Booking Details Form --}}
        <div class="border-top pt-3">
            <h6 class="mb-3">
                <i class="fa-solid fa-calendar-check fa-fw me-2"></i>
                Booking Details
            </h6>

            <div class="row g-2 mb-3">
                {{-- Check-in Date --}}
                <div class="col-6">
                    <label class="form-label small fw-bold">Check-in</label>
                    <input type="text" class="form-control form-control-sm" id="checkin-date" name="checkin_date" placeholder="dd-mm-yyyy" autocomplete="off">
                </div>

                {{-- Check-out Date --}}
                <div class="col-6">
                    <label class="form-label small fw-bold">Check-out</label>
                    <input type="text" class="form-control form-control-sm" id="checkout-date" name="checkout_date" placeholder="dd-mm-yyyy" autocomplete="off">
                </div>

                {{-- Total Days --}}
                <div class="col-6">
                    <label class="form-label small fw-bold">Total Days</label>
                    <input type="text" class="form-control form-control-sm" id="total-days" readonly value="0">
                </div>

                {{-- Total Guests --}}
                <div class="col-6">
                    <label class="form-label small fw-bold">Total Guests</label>
                    <input type="number" class="form-control form-control-sm" id="total-guests" name="total_guests" min="1" value="1">
                </div>
            </div>

            {{-- Guest Information --}}
            <div class="mb-3">
                <label class="form-label small fw-bold">Guest Information</label>
                <div class="row g-2">
                    <div class="col-12">
                        <input type="text" class="form-control form-control-sm" id="guest-name" placeholder="Guest Name" value="Walk-in Guest">
                    </div>
                    <div class="col-6">
                        <input type="email" class="form-control form-control-sm" id="guest-email" placeholder="Email (optional)">
                    </div>
                    <div class="col-6">
                        <input type="tel" class="form-control form-control-sm" id="guest-phone" placeholder="Phone (required)">
                    </div>
                </div>
            </div>

            {{-- Guest Type --}}
            <div class="mb-3">
                <label class="form-label small fw-bold">Guest Type</label>
                <div class="d-flex gap-2">
                    @if(isset($guestTypes) && $guestTypes->count() > 0)
                        @foreach($guestTypes as $type)
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="guest_type"
                                       id="guest-type-{{ $type->value }}" value="{{ $type->value }}"
                                       {{ $loop->first ? 'checked' : '' }}>
                                <label class="form-check-label small" for="guest-type-{{ $type->value }}">
                                    <i class="fa-solid {{ $type->icon ?? 'fa-user' }} fa-fw me-1"></i>
                                    {{ $type->label }}
                                </label>
                            </div>
                        @endforeach
                    @else
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="guest_type" id="guest-type-national" value="national" checked>
                            <label class="form-check-label small" for="guest-type-national">
                                <i class="fa-solid fa-house fa-fw me-1"></i>
                                National
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="guest_type" id="guest-type-international" value="international">
                            <label class="form-check-label small" for="guest-type-international">
                                <i class="fa-solid fa-plane fa-fw me-1"></i>
                                International
                            </label>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Guest Documents --}}
            <div class="mb-3 guest-documents" id="national-docs" style="display: block;">
                <label class="form-label small fw-bold">National ID
                    <span class="text-danger">*</span>
                </label>
                <input type="text" class="form-control form-control-sm" id="guest-national-id" placeholder="National ID Number" required>
            </div>

            <div class="mb-3 guest-documents" id="international-docs" style="display: none;">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Passport Number
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-sm" id="guest-passport" placeholder="Passport Number" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Country
                            <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control form-control-sm" id="guest-country" placeholder="Country of Origin" required>
                    </div>
                </div>
            </div>

            {{-- Billing Type --}}
            <div class="mb-3">
                <x-form.select
                    label="Billing Type"
                    name="billing_type"
                    :value="old('billing_type', 'night')"
                    :options="isset($billingTypes) && $billingTypes->count() > 0 ? $billingTypes->pluck('label', 'value')->toArray() : ['night' => 'Nightly Rate', '3_hours' => '3 Hours Rate']"
                    :selected="old('billing_type', 'night')"
                    required
                    id="billing-type"
                    class="form-select-sm"
                />
            </div>
        </div>

        {{-- Pricing Summary --}}
        <div class="border-top pt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-bold">Total:</span>
                <span class="fw-bold text-success" id="total-price">$0.00</span>
            </div>

            {{-- Action Buttons --}}
            <div class="d-grid gap-2">
                <button type="button" class="btn btn-success" id="confirm-booking-btn" disabled>
                    <i class="fa-solid fa-check fa-fw me-2"></i>
                    Confirm Booking
                </button>

                <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-selection-btn">
                    <i class="fa-solid fa-trash fa-fw me-2"></i>
                    Clear All
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Room Item Template (Hidden) --}}
<template id="selected-room-template">
    <div class="selected-room-item card mb-2" data-room-id="">
        <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-start">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="mb-0 small fw-bold">
                            <i class="fa-solid fa-bed fa-fw me-1"></i>
                            Room <span class="room-number">%%ROOM_NUMBER%%</span>
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-room-btn" data-room-id="">
                            <i class="fa-solid fa-times fa-fw"></i>
                        </button>
                    </div>
                    <p class="mb-1 small text-muted room-type">%%ROOM_TYPE%%</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fa-solid fa-users fa-fw me-1"></i>
                            Max: <span class="max-guests">%%MAX_GUESTS%%</span> guests
                        </small>
                        <span class="badge bg-primary small room-price">%%ROOM_PRICE%%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.selected-room-item {
    transition: all 0.2s ease;
}

.selected-room-item:hover {
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.remove-room-btn:hover {
    transform: scale(1.1);
}
</style>

@push('scripts')
<script>

function getRoomPrice(roomElement) {
    const billingType = $('#billing-type').val();
    if (billingType === '3_hours') {
        return roomElement.data('price-3hours') || 0;
    } else {
        return roomElement.data('price-night') || 0;
    }
}

$(document).ready(function() {

    let selectedRooms = [];
    const roomTemplate = $('#selected-room-template').html();


    $(document).on('room-selected', function(event, roomData) {
        addRoomToSelection(roomData);
        updatePricing();
    });

    $(document).on('room-deselected', function(event, roomId) {
        removeRoomFromSelection(roomId);
        updatePricing();
    });

    // Guest type change handler
    $('input[name="guest_type"]').on('change', function() {
        const guestType = $(this).val();
        if (guestType === 'national') {
            $('#national-docs').show();
            $('#international-docs').hide();
        } else {
            $('#national-docs').hide();
            $('#international-docs').show();
        }
    });

    // Guest count change
    $('#total-guests').on('change', function() {
        updatePricing();
    });

    // Billing type change
    $('#billing-type').on('change', function() {
        // Update prices for all selected rooms
        selectedRooms.forEach(room => {
            const roomElement = $(`.room-item[data-room-id="${room.id}"]`);
            if (roomElement.length > 0) {
                room.price = getRoomPrice(roomElement);
            }
        });
        updatePricing();
    });

    // Remove room button
    $(document).on('click', '.remove-room-btn', function() {
        const roomId = $(this).data('room-id');
        removeRoomFromSelection(roomId);
        updatePricing();

        // Also update the room grid to show as unselected
        $(`.room-item[data-room-id="${roomId}"]`).removeClass('selected');
        $(`.room-item[data-room-id="${roomId}"] .room-selection-indicator`).addClass('d-none');
        $(`.room-item[data-room-id="${roomId}"] .card-body .room-icon i`).removeClass('fa-check-circle text-primary').addClass('fa-bed text-success');
    });

    // Clear all selection
    $('#clear-selection-btn').on('click', function() {
        selectedRooms = [];
        $('#selected-rooms-list').html(`
            <div class="text-center text-muted py-4">
                <i class="fa-solid fa-bed fa-2x mb-2"></i>
                <p class="mb-0">No rooms selected</p>
                <small>Select rooms from the grid</small>
            </div>
        `);
        $('#selected-rooms-count').text('0');
        $('#confirm-booking-btn').prop('disabled', true);

        // Clear all room selections in grid
        $('.room-item').removeClass('selected');
        $('.room-selection-indicator').addClass('d-none');
        $('.room-item .card-body .room-icon i').removeClass('fa-check-circle text-primary').addClass('fa-bed text-success');

        updatePricing();
    });

    // Confirm booking
    $('#confirm-booking-btn').on('click', function() {
        if (selectedRooms.length === 0) {
            error('Please select at least one room');
            return;
        }

        // Collect form data
        const guestType = $('input[name="guest_type"]:checked').val();
        const checkinPicker = $('#checkin-date').pickadate('picker');
        const checkoutPicker = $('#checkout-date').pickadate('picker');

        const formData = {
            room_ids: selectedRooms.map(room => room.id), // Send as array
            guest_name: $('#guest-name').val() || 'Walk-in Guest',
            guest_email: $('#guest-email').val(),
            guest_phone: $('#guest-phone').val(),
            guest_type: guestType,
            billing_type: $('#billing-type').val(),
            check_in_date: checkinPicker ? checkinPicker.get('select', 'yyyy-mm-dd') : '',
            check_out_date: checkoutPicker ? checkoutPicker.get('select', 'yyyy-mm-dd') : '',
            total_days: $('#total-days').val(),
            total_guests: $('#total-guests').val(),
            total_amount: parseFloat($('#total-price').text().replace('$', '').replace(',', '')),
            paid_amount: 0, // Can be made editable
            notes: 'Walk-in check-in'
        };

        // Add guest document fields based on type
        if (guestType === 'national') {
            formData.guest_national_id = $('#guest-national-id').val();
        } else {
            formData.guest_passport = $('#guest-passport').val();
            formData.guest_country = $('#guest-country').val();
        }

        // Validate required fields
        if (!formData.check_in_date || !formData.check_out_date) {
            error('Please select check-in and check-out dates');
            return;
        }

        if (!formData.guest_name.trim()) {
            error('Please enter guest name');
            return;
        }

        if (!formData.guest_phone.trim()) {
            error('Please enter guest phone number');
            return;
        }

        // Validate guest document fields
        if (guestType === 'national' && !formData.guest_national_id.trim()) {
            error('Please enter guest national ID');
            return;
        }

        if (guestType === 'international') {
            if (!formData.guest_passport.trim()) {
                error('Please enter guest passport number');
                return;
            }
            if (!formData.guest_country.trim()) {
                error('Please enter guest country');
                return;
            }
        }

        // Submit the booking
        $.ajax({
            url: window.location.href,
            method: 'POST',
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.status === 'success') {
                    success(response.message);
                    // Redirect after success
                    setTimeout(function() {
                        window.location.href = response.redirect;
                    }, 1500);
                } else if (response.status === 'error') {
                    error(response.message || 'An error occurred while saving the booking');
                    return;
                } else {
                    error('Unexpected response from server');
                }
            },
            error: function(xhr, status, errorThrown) {
                let message = 'An error occurred while saving the booking';

                if (xhr.status === 422 && xhr.responseJSON) {
                    // Handle validation errors
                    if (xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    } else if (xhr.responseJSON.errors) {
                        // Handle Laravel validation errors
                        const errors = Object.values(xhr.responseJSON.errors).flat();
                        message = errors.join(', ');
                    }
                } else if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                } else if (xhr.responseJSON && xhr.responseJSON.status === 'error') {
                    message = xhr.responseJSON.message || 'An error occurred';
                }

                error(message);
            }
        });
    });

    function addRoomToSelection(roomData) {
        // Check if room is already selected
        if (selectedRooms.find(room => room.id === roomData.id)) {
            return;
        }

        selectedRooms.push(roomData);
        updateSelectedRoomsDisplay();
        $('#confirm-booking-btn').prop('disabled', false);
    }

    function removeRoomFromSelection(roomId) {
        selectedRooms = selectedRooms.filter(room => room.id !== roomId);
        updateSelectedRoomsDisplay();

        if (selectedRooms.length === 0) {
            $('#confirm-booking-btn').prop('disabled', true);
        }
    }

    function updateSelectedRoomsDisplay() {
        if (selectedRooms.length === 0) {
            $('#selected-rooms-list').html(`
                <div class="text-center text-muted py-4">
                    <i class="fa-solid fa-bed fa-2x mb-2"></i>
                    <p class="mb-0">No rooms selected</p>
                    <small>Select rooms from the grid</small>
                </div>
            `);
        } else {
            let html = '';
            selectedRooms.forEach(room => {
                const roomHtml = roomTemplate
                    .replace(/data-room-id=""/g, `data-room-id="${room.id}"`)
                    .replace('%%ROOM_NUMBER%%', room.number)
                    .replace('%%ROOM_TYPE%%', room.type)
                    .replace('%%MAX_GUESTS%%', room.maxGuests)
                    .replace('%%ROOM_PRICE%%', `$${room.price}/night`);
                html += roomHtml;
            });
            $('#selected-rooms-list').html(html);
        }

        $('#selected-rooms-count').text(selectedRooms.length);
    }

    function calculateTotalDays() {
        var checkinValue = $('#checkin-date').val();
        var checkoutValue = $('#checkout-date').val();

        if (checkinValue && checkoutValue) {
            // Parse d-m-yyyy format
            var checkinParts = checkinValue.split('-');
            var checkoutParts = checkoutValue.split('-');

            if (checkinParts.length === 3 && checkoutParts.length === 3) {
                var checkinDate = new Date(checkinParts[2], checkinParts[1] - 1, checkinParts[0]);
                var checkoutDate = new Date(checkoutParts[2], checkoutParts[1] - 1, checkoutParts[0]);

                if (checkoutDate > checkinDate) {
                    var timeDiff = checkoutDate.getTime() - checkinDate.getTime();
                    var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
                    $('#total-days').val(daysDiff);
                    return daysDiff;
                }
            }
        }

        $('#total-days').val('0');
        return 0;
    }

    function updatePricing() {
        const totalDays = parseInt($('#total-days').val()) || 0;
        const billingType = $('#billing-type').val();

        let total = 0;
        selectedRooms.forEach(room => {
            let roomPrice = parseFloat(room.price);

            switch (billingType) {
                case '3_hours':
                    roomPrice = roomPrice;
                    break;
                case 'night':
                default:
                    roomPrice = roomPrice;
            }

            total += roomPrice * totalDays;
        });

        $('#total-price').text(`$${total.toFixed(2)}`);
    }

    // Initialize pickadate
    $('#checkin-date').pickadate({
        format: 'd-m-yyyy',
        formatSubmit: 'yyyy-mm-dd',
        min: new Date(),
        onSet: function(event) {
            if (event.select) {
                // Update checkout minimum date
                var checkinDate = new Date(event.select);
                var minCheckout = new Date(checkinDate);
                minCheckout.setDate(checkinDate.getDate() + 1);

                var checkoutPicker = $('#checkout-date').pickadate('picker');
                checkoutPicker.set('min', minCheckout);

                // If checkout is before new checkin, update it
                var currentCheckout = checkoutPicker.get('select');
                if (currentCheckout && currentCheckout < minCheckout) {
                    checkoutPicker.set('select', minCheckout);
                }
            }
            calculateTotalDays();
            updatePricing();
        }
    });

    $('#checkout-date').pickadate({
        format: 'd-m-yyyy',
        formatSubmit: 'yyyy-mm-dd',
        min: new Date(Date.now() + 24 * 60 * 60 * 1000), // Tomorrow
        onSet: function(event) {
            calculateTotalDays();
            updatePricing();
        }
    });

    // Set default dates
    var today = new Date();
    var tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    var checkinPicker = $('#checkin-date').pickadate('picker');
    var checkoutPicker = $('#checkout-date').pickadate('picker');

    if (checkinPicker) {
        checkinPicker.set('select', today);
    }
    if (checkoutPicker) {
        checkoutPicker.set('select', tomorrow);
    }

    // Calculate total days after setting dates
    setTimeout(function() {
        calculateTotalDays();
    }, 100);
});
</script>
@endpush
