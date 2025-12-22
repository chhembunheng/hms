@push('styles')
<style>
.room-disabled {
    cursor: not-allowed !important;
    opacity: 0.6 !important;
    filter: grayscale(50%);
}

.room-disabled:hover {
    transform: none !important;
    box-shadow: none !important;
}
</style>
@endpush

<div class="card h-100">
    <div class="card-header bg-primary text-white">
        <h5 class="card-title mb-0">
            <i class="fa-solid fa-building fa-fw me-2"></i>
            Room Selection
        </h5>
    </div>
    <div class="card-body p-0">
        {{-- Date Selection Notice --}}
        <div class="alert alert-warning border-0 rounded-0 mb-0" id="date-selection-notice">
            <i class="fa-solid fa-calendar-days fa-fw me-2"></i>
            Please select check-in and check-out dates to view available rooms.
        </div>

        {{-- Loading Indicator --}}
        <div class="alert alert-info border-0 rounded-0 mb-0 d-none" id="loading-rooms">
            <i class="fa-solid fa-spinner fa-spin fa-fw me-2"></i>
            Loading available rooms...
        </div>

        {{-- No Rooms Available --}}
        <div class="alert alert-warning border-0 rounded-0 mb-0 d-none" id="no-rooms-notice">
            <i class="fa-solid fa-exclamation-triangle fa-fw me-2"></i>
            No rooms available for the selected dates.
        </div>

        {{-- Floor Navigation --}}
        <div class="border-bottom p-3 d-none" id="floor-navigation">
            <div class="d-flex flex-wrap gap-2" id="floor-tabs">
                {{-- Floor tabs will be populated by JavaScript --}}
            </div>
        </div>

        {{-- Room Grid --}}
        <div class="p-3 d-none" id="room-grid" style="max-height: 600px; overflow-y: auto;">
            {{-- Room content will be populated by JavaScript --}}
        </div>
    </div>
</div>

<style>
.room-card {
    transition: all 0.2s ease;
}

.room-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.room-available {
    border-color: #198754 !important;
}

.room-available:hover {
    border-color: #157347 !important;
    background-color: rgba(25, 135, 84, 0.05);
}

.room-occupied {
    border-color: #dc3545 !important;
    opacity: 0.6;
}

.room-occupied:hover {
    opacity: 0.8;
}

.floor-tab.active {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    color: white !important;
}

.room-selection-indicator {
    border-top: 2px solid #198754;
}
</style>

@push('scripts')
<script>
// Helper function to get room price based on billing type
function getRoomPrice(roomElement) {
    const billingType = $('#billing-type').val();
    if (billingType === '3_hours') {
        return roomElement.data('price-3hours') || 0;
    } else {
        return roomElement.data('price-night') || 0;
    }
}

// Function to fetch available rooms
function fetchAvailableRooms() {
    const checkinDate = $('#checkin-date').val();
    const checkoutDate = $('#checkout-date').val();
    const billingType = $('#billing-type').val();

    if (!checkinDate || !checkoutDate) {
        $('#date-selection-notice').removeClass('d-none');
        $('#loading-rooms').addClass('d-none');
        $('#no-rooms-notice').addClass('d-none');
        $('#floor-navigation').addClass('d-none');
        $('#room-grid').addClass('d-none');
        return;
    }

    // Show loading
    $('#date-selection-notice').addClass('d-none');
    $('#loading-rooms').removeClass('d-none');
    $('#no-rooms-notice').addClass('d-none');
    $('#floor-navigation').addClass('d-none');
    $('#room-grid').addClass('d-none');

    // Fetch available rooms
    $.ajax({
        url: '{{ route("checkin.walkin.available-rooms") }}',
        method: 'GET',
        data: {
            check_in_date: checkinDate,
            check_out_date: checkoutDate,
            billing_type: billingType
        },
        success: function(response) {
            $('#loading-rooms').addClass('d-none');

            if (response.status === 'success') {
                if (response.total_rooms > 0) {
                    renderAvailableRooms(response.floors);
                    $('#floor-navigation').removeClass('d-none');
                    $('#room-grid').removeClass('d-none');
                } else {
                    $('#no-rooms-notice').removeClass('d-none');
                }
            } else {
                $('#no-rooms-notice').removeClass('d-none');
                if (typeof error === 'function') {
                    error('Failed to load available rooms');
                }
            }
        },
        error: function(xhr) {
            $('#loading-rooms').addClass('d-none');
            $('#no-rooms-notice').removeClass('d-none');
            if (typeof error === 'function') {
                error('Failed to load available rooms');
            }
        }
    });
}

// Function to render available rooms
function renderAvailableRooms(floors) {
    // Clear existing content
    $('#floor-tabs').empty();
    $('#room-grid').empty();

    if (floors.length === 0) {
        $('#no-rooms-notice').removeClass('d-none');
        return;
    }

    // Create floor tabs
    floors.forEach(function(floor, index) {
        const isActive = index === 0;
        const floorTab = `
            <button type="button" class="btn btn-outline-primary btn-sm floor-tab ${isActive ? 'active' : ''}"
                    data-floor-id="${floor.id}"
                    data-floor-name="${floor.name}">
                <i class="fa-solid fa-layer-group fa-fw me-1"></i>
                ${floor.name}
            </button>
        `;
        $('#floor-tabs').append(floorTab);
    });

    // Create floor rooms
    floors.forEach(function(floor, index) {
        const isActive = index === 0;
        let floorHtml = `
            <div class="floor-rooms ${isActive ? '' : 'd-none'}" data-floor-id="${floor.id}">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="mb-0">
                        <i class="fa-solid fa-layer-group fa-fw me-2 text-primary"></i>
                        ${floor.name} - Rooms
                    </h6>
                    <small class="text-muted">${floor.rooms.length} rooms</small>
                </div>
                <div class="row g-3">
        `;

        if (floor.rooms.length > 0) {
            floor.rooms.forEach(function(room) {
                const nightlyPrice = parseFloat(room.price_night) > 0 ? `<span class="badge bg-primary d-block mb-1">$${parseFloat(room.price_night).toFixed(2)}/night</span>` : '';
                const hourlyPrice = parseFloat(room.price_3hours) > 0 ? `<span class="badge bg-info d-block">$${parseFloat(room.price_3hours).toFixed(2)}/3hrs</span>` : '';

                floorHtml += `
                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6">
                        <div class="room-card card h-100 border room-item room-available"
                             data-room-id="${room.id}"
                             data-room-number="${room.number}"
                             data-room-type="${room.type}"
                             data-price-night="${parseFloat(room.price_night)}"
                             data-price-3hours="${parseFloat(room.price_3hours)}"
                             data-max-guests="${room.max_guests}"
                             style="cursor: pointer; transition: all 0.2s;">
                            <div class="card-body text-center p-3">
                                <div class="room-icon mb-2">
                                    <i class="fa-solid fa-bed fa-2x text-success"></i>
                                </div>
                                <h6 class="card-title mb-1 fw-bold">${room.number}</h6>
                                <p class="card-text small text-muted mb-2">
                                    ${room.type}
                                    ${room.type_kh ? '<br><small class="text-muted">' + room.type_kh + '</small>' : ''}
                                </p>
                                <div class="mb-2">
                                    ${nightlyPrice}
                                    ${hourlyPrice}
                                </div>
                                <div class="room-status">
                                    <span class="badge bg-success">
                                        <i class="fa-solid fa-check fa-fw me-1"></i>
                                        Available
                                    </span>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fa-solid fa-users fa-fw me-1"></i>
                                        Max: ${room.max_guests} guests
                                    </small>
                                </div>
                            </div>
                            <div class="card-footer p-2 bg-light room-selection-indicator d-none">
                                <div class="text-center">
                                    <i class="fa-solid fa-check-circle text-success fa-lg"></i>
                                    <small class="text-success fw-bold ms-1">Selected</small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
        } else {
            floorHtml += `
                <div class="col-12">
                    <div class="alert alert-warning mb-0">
                        <i class="fa-solid fa-exclamation-triangle fa-fw me-2"></i>
                        No rooms available on this floor
                    </div>
                </div>
            `;
        }

        floorHtml += `
                </div>
            </div>
        `;

        $('#room-grid').append(floorHtml);
    });


    $('#floor-navigation').removeClass('d-none');
    $('#room-grid').removeClass('d-none');
}

$(document).ready(function() {
    // Listen for date and billing type changes
    $(document).on('change', '#checkin-date, #checkout-date, #billing-type', function() {
        fetchAvailableRooms();
    });

    // Floor tab switching
    $(document).on('click', '.floor-tab', function() {
        const floorId = $(this).data('floor-id');

        // Update active tab
        $('.floor-tab').removeClass('active');
        $(this).addClass('active');

        // Show selected floor rooms
        $('.floor-rooms').addClass('d-none');
        $(`.floor-rooms[data-floor-id="${floorId}"]`).removeClass('d-none');
    });

    // Room selection
    $(document).on('click', '.room-item', function() {
        if ($(this).hasClass('room-occupied')) {
            return;
        }

        const roomId = $(this).data('room-id');
        const isSelected = $(this).hasClass('selected');

        if (isSelected) {
            // Deselect room
            $(this).removeClass('selected');
            $(this).find('.room-selection-indicator').addClass('d-none');
            $(this).find('.card-body .room-icon i').removeClass('fa-check-circle text-primary').addClass('fa-bed text-success');

            // Trigger event for parent component
            $(document).trigger('room-deselected', [roomId]);
        } else {
            // Select room
            $(this).addClass('selected');
            $(this).find('.room-selection-indicator').removeClass('d-none');
            $(this).find('.card-body .room-icon i').removeClass('fa-bed text-success').addClass('fa-check-circle text-primary');

            // Trigger event for parent component
            $(document).trigger('room-selected', [{
                id: roomId,
                number: $(this).data('room-number'),
                type: $(this).data('room-type'),
                price: getRoomPrice($(this)),
                maxGuests: $(this).data('max-guests')
            }]);
        }
    });

});
</script>
@endpush
