<x-page-index 
    :title="__('SAI Airport Transfers & Transport Desk')" 
    icon="plane">

    <x-slot:actions>
        <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 rounded-2 px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#createTransferModal">
            <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
            <span>{{ __('Schedule Transfer') }}</span>
        </button>
    </x-slot:actions>

    <x-slot:filters>
        <!-- KPI Metric Row -->
        <div class="row g-2 mb-3 px-1">
            <div class="col-sm-6 col-xl-3">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="plane-landing" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __("Today's Pickups") }}</div>
                        <div class="fw-bold text-primary fs-6">{{ number_format($todayPickups) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="plane-takeoff" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __("Today's Drop-offs") }}</div>
                        <div class="fw-bold text-warning fs-6">{{ number_format($todayDropoffs) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="clock" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('Scheduled / Pending') }}</div>
                        <div class="fw-bold text-info fs-6">{{ number_format($pendingTransfers) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="dollar-sign" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('Completed Transfer Rev') }}</div>
                        <div class="fw-bold text-success fs-6">${{ number_format($totalRevenue, 2) }} <small class="text-muted fw-normal">({{ format_dual_currency($totalRevenue) }})</small></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('services.transfers.index') }}" class="row g-2 align-items-end px-1">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('From Date') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="start_date" class="form-control form-control-sm rounded-start-2 pickadate" value="{{ format_date($startDate) }}" placeholder="dd-mm-yyyy" autocomplete="off">
                    <span class="input-group-text rounded-end-2"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('To Date') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="end_date" class="form-control form-control-sm rounded-start-2 pickadate" value="{{ format_date($endDate) }}" placeholder="dd-mm-yyyy" autocomplete="off">
                    <span class="input-group-text rounded-end-2"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('Transfer Type') }}</label>
                <select name="type" class="form-select form-select-sm rounded-2">
                    <option value="all" {{ $type === 'all' ? 'selected' : '' }}>{{ __('All Types') }}</option>
                    <option value="pickup" {{ $type === 'pickup' ? 'selected' : '' }}>{{ __('Pickup (Arrival)') }}</option>
                    <option value="dropoff" {{ $type === 'dropoff' ? 'selected' : '' }}>{{ __('Drop-off (Departure)') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('Status') }}</label>
                <select name="status" class="form-select form-select-sm rounded-2">
                    <option value="all" {{ $status === 'all' ? 'selected' : '' }}>{{ __('All Statuses') }}</option>
                    <option value="scheduled" {{ $status === 'scheduled' ? 'selected' : '' }}>{{ __('Scheduled') }}</option>
                    <option value="in_progress" {{ $status === 'in_progress' ? 'selected' : '' }}>{{ __('In Progress') }}</option>
                    <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>{{ __('Completed') }}</option>
                    <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>{{ __('Cancelled') }}</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                    <i data-lucide="filter" style="width: 13px; height: 13px;"></i>
                    <span>{{ __('global.filter') }}</span>
                </button>
                <a href="{{ route('services.transfers.index') }}" class="btn btn-sm btn-outline-secondary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                    <i data-lucide="rotate-ccw" style="width: 13px; height: 13px;"></i>
                    <span>{{ __('global.clear') }}</span>
                </a>
            </div>
        </form>
    </x-slot:filters>

    <!-- Transfers List -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 datatables-custom">
            <thead class="table-light">
                <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #64748b;">
                    <th class="ps-3">{{ __('Date & Time') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('Guest / Room') }}</th>
                    <th>Flight No.</th>
                    <th>{{ __('Vehicle') }}</th>
                    <th>{{ __('Route') }}</th>
                    <th>{{ __('Pax / Luggage') }}</th>
                    <th>{{ __('Driver') }}</th>
                    <th class="text-end">{{ __('Price') }}</th>
                    <th>{{ __('Folio Charge') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th class="text-end pe-3">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($transfers as $item)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-bold text-dark">{{ $item->transfer_datetime ? $item->transfer_datetime->format('d-m-Y') : 'N/A' }}</div>
                            <small class="text-primary font-monospace">{{ $item->transfer_datetime ? $item->transfer_datetime->format('H:i') : '' }}</small>
                        </td>
                        <td>
                            @if($item->transfer_type === 'pickup')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1">
                                    <i data-lucide="arrow-down" style="width: 12px; height: 12px;" class="me-1"></i>Pickup
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">
                                    <i data-lucide="arrow-up" style="width: 12px; height: 12px;" class="me-1"></i>Drop-off
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold text-dark">{{ $item->guest_name }}</div>
                            @if($item->checkIn && $item->checkIn->room)
                                <span class="badge bg-light text-dark border rounded-pill px-2 py-1">
                                    Room {{ $item->checkIn->room->room_number }}
                                </span>
                            @endif
                            @if($item->guest_phone)
                                <div class="text-muted small"><i data-lucide="phone" style="width: 12px; height: 12px;" class="me-1"></i>{{ $item->guest_phone }}</div>
                            @endif
                        </td>
                        <td>
                            @if($item->flight_number)
                                <span class="badge bg-light text-dark border font-monospace">{{ $item->flight_number }}</span>
                            @else
                                <span class="text-muted small">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-capitalize fw-semibold small">
                                @if($item->vehicle_type === 'remork_tuktuk')
                                    Tuk-Tuk (Remork)
                                @elseif($item->vehicle_type === 'van')
                                    Private Van
                                @elseif($item->vehicle_type === 'suv')
                                    Private SUV
                                @else
                                    Sedan Car
                                @endif
                            </span>
                        </td>
                        <td>
                            <div class="small text-truncate" style="max-width: 200px;" title="{{ $item->pickup_location }} → {{ $item->dropoff_location }}">
                                <div class="text-success"><i data-lucide="map-pin" style="width: 12px; height: 12px;" class="me-1"></i>{{ $item->pickup_location }}</div>
                                <div class="text-danger"><i data-lucide="navigation" style="width: 12px; height: 12px;" class="me-1"></i>{{ $item->dropoff_location ?: 'Hotel' }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border rounded-pill px-2 py-1">{{ $item->passenger_count }} pax</span>
                            @if($item->luggage_count)
                                <span class="badge bg-light text-muted border rounded-pill px-2 py-1">{{ $item->luggage_count }} bags</span>
                            @endif
                        </td>
                        <td>
                            @if($item->driver_name)
                                <div class="fw-semibold small text-dark">{{ $item->driver_name }}</div>
                                <div class="text-muted small">{{ $item->driver_phone }}</div>
                            @else
                                <span class="text-muted small fst-italic">Unassigned</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="fw-bold text-dark">${{ number_format($item->price, 2) }}</div>
                            <small class="text-muted">{{ format_dual_currency($item->price) }}</small>
                        </td>
                        <td>
                            @if($item->is_charged_to_room)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                    <i data-lucide="check" style="width: 12px; height: 12px;" class="me-1"></i>Folio Billed
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">Direct Cash</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status === 'completed')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">{{ __('Completed') }}</span>
                            @elseif($item->status === 'in_progress')
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-1">{{ __('In Progress') }}</span>
                            @elseif($item->status === 'scheduled')
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-2 py-1">{{ __('Scheduled') }}</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">{{ __('Cancelled') }}</span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-light border p-1 rounded-2" type="button" data-bs-toggle="dropdown">
                                    <i data-lucide="more-vertical" style="width: 14px; height: 14px;"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-1" style="font-size: 0.85rem;">
                                    <li><h6 class="dropdown-header text-uppercase small text-muted">Update Status</h6></li>
                                    <li><a class="dropdown-item py-1 text-primary" href="javascript:void(0)" onclick="updateStatus({{ $item->id }}, 'scheduled')">Mark Scheduled</a></li>
                                    <li><a class="dropdown-item py-1 text-info" href="javascript:void(0)" onclick="updateStatus({{ $item->id }}, 'in_progress')">Mark In Progress</a></li>
                                    <li><a class="dropdown-item py-1 text-success" href="javascript:void(0)" onclick="updateStatus({{ $item->id }}, 'completed')">Mark Completed</a></li>
                                    <li><a class="dropdown-item py-1 text-warning" href="javascript:void(0)" onclick="updateStatus({{ $item->id }}, 'cancelled')">Cancel Transfer</a></li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li><a class="dropdown-item py-1 text-danger" href="javascript:void(0)" onclick="deleteTransfer({{ $item->id }})">Delete</a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                    <i data-lucide="plane" style="width: 28px; height: 28px; stroke: #94a3b8;"></i>
                                </div>
                                <h6 class="fw-semibold text-secondary mb-1">No airport transfers found for this date range.</h6>
                                <p class="small text-muted mb-0">Click "Schedule Transfer" above to add an arrival pickup or departure transfer to SAI Airport.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-page-index>

<!-- Create Transfer Modal -->
<div class="modal fade" id="createTransferModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-2">
                <h6 class="modal-title fw-bold"><i data-lucide="plane-landing" style="width: 18px; height: 18px;" class="me-2 text-primary"></i>Schedule Airport / Local Transfer</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="createTransferForm">
                @csrf
                <div class="modal-body py-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Link Staying Room (Optional)</label>
                            <select name="check_in_id" id="checkInSelect" class="form-select form-select-sm rounded-2">
                                <option value="">-- Independent / Not In-House Yet --</option>
                                @foreach($stayingCheckIns as $ci)
                                    <option value="{{ $ci->id }}" data-guest="{{ $ci->guest_name }}" data-phone="{{ $ci->guest_phone }}">
                                        Room {{ $ci->room?->room_number }} - {{ $ci->guest_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Transfer Type <span class="text-danger">*</span></label>
                            <select name="transfer_type" class="form-select form-select-sm rounded-2" required id="transferTypeSelect">
                                <option value="pickup">Airport Pickup (Arrival → Hotel)</option>
                                <option value="dropoff">Airport Drop-off (Hotel → SAI Airport)</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Guest Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="guest_name" id="guestNameInput" class="form-control form-control-sm rounded-2" required placeholder="e.g. John Doe">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Guest Phone / WhatsApp</label>
                            <input type="text" name="guest_phone" id="guestPhoneInput" class="form-control form-control-sm rounded-2" placeholder="+855 ...">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Transfer Date & Time <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="transfer_datetime" class="form-control form-control-sm rounded-2" required value="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Flight Number</label>
                            <input type="text" name="flight_number" class="form-control form-control-sm rounded-2" placeholder="e.g. K6 824 / SQ 164">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Vehicle Type <span class="text-danger">*</span></label>
                            <select name="vehicle_type" class="form-select form-select-sm rounded-2" required id="vehicleTypeSelect">
                                <option value="van" data-price="45">Private Van (up to 10 pax) - $45</option>
                                <option value="car" data-price="35">Private Sedan Car - $35</option>
                                <option value="remork_tuktuk" data-price="25">Khmer Remork / Tuk-Tuk - $25</option>
                                <option value="suv" data-price="40">Private SUV / Lexus - $40</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Pickup Location <span class="text-danger">*</span></label>
                            <input type="text" name="pickup_location" id="pickupLocationInput" class="form-control form-control-sm rounded-2" required value="Siem Reap Angkor Int. Airport (SAI) Terminal">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Drop-off Location</label>
                            <input type="text" name="dropoff_location" id="dropoffLocationInput" class="form-control form-control-sm rounded-2" value="Hotel Lobby">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Passengers (Pax)</label>
                            <input type="number" name="passenger_count" class="form-control form-control-sm rounded-2" value="2" min="1" max="50" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Luggage Count</label>
                            <input type="number" name="luggage_count" class="form-control form-control-sm rounded-2" value="2" min="0" max="50">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-semibold">Price (USD) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text rounded-start-2">$</span>
                                <input type="number" step="0.01" name="price" id="priceInput" class="form-control form-control-sm rounded-end-2" value="45.00" required>
                            </div>
                        </div>
                        <div class="col-md-3 d-flex align-items-center mt-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_charged_to_room" id="isChargedSwitch" value="1" checked>
                                <label class="form-check-label fw-semibold small" for="isChargedSwitch">Post to Room Folio</label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Driver Name</label>
                            <input type="text" name="driver_name" class="form-control form-control-sm rounded-2" placeholder="e.g. Sokha Chan">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Driver Contact Phone</label>
                            <input type="text" name="driver_phone" class="form-control form-control-sm rounded-2" placeholder="e.g. 012 345 678">
                        </div>

                        <div class="col-12">
                            <label class="form-label small fw-semibold">Notes / Special Flight Instructions</label>
                            <textarea name="notes" class="form-control form-control-sm rounded-2" rows="2" placeholder="Driver to hold guest name placard at SAI arrival exit gate..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-2" data-bs-dismiss="modal">{{ __('global.cancel') }}</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-2">Save & Schedule</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('#checkInSelect').on('change', function() {
            const selected = $(this).find(':selected');
            const guest = selected.data('guest');
            const phone = selected.data('phone');
            if (guest) {
                $('#guestNameInput').val(guest);
                if (phone) $('#guestPhoneInput').val(phone);
            }
        });

        $('#transferTypeSelect').on('change', function() {
            if ($(this).val() === 'pickup') {
                $('#pickupLocationInput').val('Siem Reap Angkor Int. Airport (SAI) Arrival Terminal');
                $('#dropoffLocationInput').val('Hotel Lobby');
            } else {
                $('#pickupLocationInput').val('Hotel Lobby');
                $('#dropoffLocationInput').val('Siem Reap Angkor Int. Airport (SAI) Departure Terminal');
            }
        });

        $('#vehicleTypeSelect').on('change', function() {
            const defaultPrice = $(this).find(':selected').data('price');
            if (defaultPrice) {
                $('#priceInput').val(defaultPrice.toFixed(2));
            }
        });

        $('#createTransferForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('services.transfers.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    let msg = 'Failed to schedule transfer.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg
                    });
                }
            });
        });
    });

    function updateStatus(id, status) {
        $.ajax({
            url: `/services/transfers/${id}/status`,
            method: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                status: status
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Updated',
                    text: response.message,
                    timer: 1200,
                    showConfirmButton: false
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function(xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Failed to update status', 'error');
            }
        });
    }

    function deleteTransfer(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Delete this transfer record?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/services/transfers/${id}/delete`,
                    method: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        Swal.fire('Deleted!', response.message, 'success').then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to delete transfer', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
