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
                    <input type="date" class="form-control form-control-sm" id="checkin-date" name="checkin_date">
                </div>

                {{-- Check-out Date --}}
                <div class="col-6">
                    <label class="form-label small fw-bold">Check-out</label>
                    <input type="date" class="form-control form-control-sm" id="checkout-date" name="checkout_date">
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
                        <input type="tel" class="form-control form-control-sm" id="guest-phone" placeholder="Phone (optional)">
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
                <label class="form-label small fw-bold">National ID</label>
                <input type="text" class="form-control form-control-sm" id="guest-national-id" placeholder="National ID Number">
            </div>

            <div class="mb-3 guest-documents" id="international-docs" style="display: none;">
                <div class="row g-2">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Passport Number</label>
                        <input type="text" class="form-control form-control-sm" id="guest-passport" placeholder="Passport Number">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Country</label>
                        <input type="text" class="form-control form-control-sm" id="guest-country" placeholder="Country of Origin">
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
                <span class="fw-bold">Subtotal:</span>
                <span class="fw-bold" id="subtotal-price">$0.00</span>
            </div>

            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="small text-muted">Taxes & Fees:</span>
                <span class="small text-muted" id="taxes-fees">$0.00</span>
            </div>

            <hr class="my-2">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="h6 mb-0 fw-bold">Total:</span>
                <span class="h6 mb-0 fw-bold text-success" id="total-price">$0.00</span>
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
                            Room <span class="room-number"></span>
                        </h6>
                        <button type="button" class="btn btn-sm btn-outline-danger remove-room-btn" data-room-id="">
                            <i class="fa-solid fa-times fa-fw"></i>
                        </button>
                    </div>
                    <p class="mb-1 small text-muted room-type"></p>
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="fa-solid fa-users fa-fw me-1"></i>
                            Max: <span class="max-guests"></span> guests
                        </small>
                        <span class="badge bg-primary small room-price"></span>
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

    // Date change handlers
    $('#checkin-date, #checkout-date').on('change', function() {
        calculateTotalDays();
        updatePricing();
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
        const formData = {
            room_ids: selectedRooms.map(room => room.id), // Send as array
            guest_name: $('#guest-name').val() || 'Walk-in Guest',
            guest_email: $('#guest-email').val(),
            guest_phone: $('#guest-phone').val(),
            guest_type: guestType,
            billing_type: $('#billing-type').val(),
            check_in_date: $('#checkin-date').val(),
            check_out_date: $('#checkout-date').val(),
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
                } else {
                    error(response.message || 'An error occurred while saving the booking');
                }
            },
            error: function(xhr) {
                let message = 'An error occurred while saving the booking';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
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
                    .replace('<span class="room-number"></span>', room.number)
                    .replace('room-type', `room-type">${room.type}`)
                    .replace('<span class="max-guests"></span>', room.maxGuests)
                    .replace('room-price', `room-price">$${room.price}/night`);
                html += roomHtml;
            });
            $('#selected-rooms-list').html(html);
        }

        $('#selected-rooms-count').text(selectedRooms.length);
    }

    function calculateTotalDays() {
        const checkinDate = new Date($('#checkin-date').val());
        const checkoutDate = new Date($('#checkout-date').val());

        if (checkinDate && checkoutDate && checkoutDate > checkinDate) {
            const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
            const daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
            $('#total-days').val(daysDiff);
            return daysDiff;
        }

        $('#total-days').val('0');
        return 0;
    }

    function updatePricing() {
        const totalDays = parseInt($('#total-days').val()) || 0;
        const billingType = $('#billing-type').val();

        let subtotal = 0;
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

            subtotal += roomPrice * totalDays;
        });

        const taxes = subtotal * 0.05;
        const total = subtotal + taxes;

        $('#subtotal-price').text(`$${subtotal.toFixed(2)}`);
        $('#taxes-fees').text(`$${taxes.toFixed(2)}`);
        $('#total-price').text(`$${total.toFixed(2)}`);
    }

    // Set default dates
    const today = new Date();
    const tomorrow = new Date(today);
    tomorrow.setDate(tomorrow.getDate() + 1);

    $('#checkin-date').val(today.toISOString().split('T')[0]);
    $('#checkout-date').val(tomorrow.toISOString().split('T')[0]);
    calculateTotalDays();
});
</script>
@endpush
