<x-page-index 
    :title="__('Room Folio Charges & Tour Desk')" 
    icon="receipt">

    <x-slot:actions>
        <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 rounded-2 px-3 fw-medium" data-bs-toggle="modal" data-bs-target="#chargeServiceModal">
            <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
            <span>{{ __('Post Charge to Room') }}</span>
        </button>
    </x-slot:actions>

    <x-slot:filters>
        <!-- KPI Badges Row -->
        <div class="row g-2 mb-3 px-1">
            <div class="col-md-4">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="file-clock" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('Pending Unbilled Folio Charges') }}</div>
                        <div class="fw-bold text-warning fs-6">${{ number_format($totalPostedAmount, 2) }} <small class="text-muted fw-normal">({{ format_dual_currency($totalPostedAmount) }})</small></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="bed" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('Active Staying Rooms') }}</div>
                        <div class="fw-bold text-primary fs-6">{{ $stayingCheckIns->count() }} <small class="text-muted fw-normal">({{ __('Eligible for room charges') }})</small></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="check-circle" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('Automated Billing') }}</div>
                        <div class="fw-bold text-success fs-6">{{ __('Charges roll directly into checkout invoice') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('services.room-services.index') }}" class="row g-2 align-items-end px-1">
            <div class="col-md-6">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('Filter by Staying Room / Guest') }}</label>
                <select name="check_in_id" class="form-select form-select-sm rounded-2">
                    <option value="">-- All Staying Rooms & Guests --</option>
                    @foreach($stayingCheckIns as $ci)
                        <option value="{{ $ci->id }}" {{ $checkInId == $ci->id ? 'selected' : '' }}>
                            Room {{ $ci->room?->room_number }} — {{ $ci->guest_name }} ({{ $ci->booking_number }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                    <i data-lucide="filter" style="width: 13px; height: 13px;"></i>
                    <span>{{ __('global.filter') }}</span>
                </button>
                <a href="{{ route('services.room-services.index') }}" class="btn btn-sm btn-outline-secondary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                    <i data-lucide="rotate-ccw" style="width: 13px; height: 13px;"></i>
                    <span>{{ __('global.clear') }}</span>
                </a>
            </div>
        </form>
    </x-slot:filters>

    <!-- Posted Charges Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 datatables-custom">
            <thead class="table-light">
                <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #64748b;">
                    <th class="ps-3">{{ __('Service Date') }}</th>
                    <th>{{ __('Room') }}</th>
                    <th>{{ __('Guest') }}</th>
                    <th>{{ __('Service Item') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Qty') }}</th>
                    <th class="text-end">{{ __('Unit Price') }}</th>
                    <th class="text-end">{{ __('Total (USD / KHR)') }}</th>
                    <th>{{ __('Folio Status') }}</th>
                    <th class="text-end pe-3">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($postedServices as $item)
                    <tr>
                        <td class="ps-3">
                            <div class="fw-semibold text-dark">{{ $item->service_date ? $item->service_date->format('d-m-Y') : $item->created_at->format('d-m-Y') }}</div>
                            <small class="text-muted">{{ $item->created_at->format('H:i') }}</small>
                        </td>
                        <td>
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-2 px-2 py-1 fw-bold">
                                {{ $item->checkIn?->room?->room_number ?? 'N/A' }}
                            </span>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->checkIn?->guest_name ?? 'N/A' }}</div>
                            <small class="text-muted">{{ $item->checkIn?->booking_number }}</small>
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->service?->name_en ?? 'Unknown Service' }}</div>
                            @if($item->notes)
                                <small class="text-muted d-block text-truncate" style="max-width: 250px;">{{ $item->notes }}</small>
                            @endif
                        </td>
                        <td>
                            @php $cat = $item->service?->category ?? 'other'; @endphp
                            @if($cat === 'tour')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">Tour</span>
                            @elseif($cat === 'laundry')
                                <span class="badge bg-info-subtle text-info border border-info-subtle rounded-pill px-2 py-1">Laundry</span>
                            @elseif($cat === 'spa')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">Spa</span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">{{ ucfirst($cat) }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="fw-bold text-dark">{{ $item->quantity }}</span> <small class="text-muted">{{ $item->service?->unit }}</small>
                        </td>
                        <td class="text-end text-muted">
                            ${{ number_format($item->unit_price, 2) }}
                        </td>
                        <td class="text-end">
                            <div class="fw-bold text-dark">${{ number_format($item->total_price, 2) }}</div>
                            <small class="text-muted">{{ format_dual_currency($item->total_price) }}</small>
                        </td>
                        <td>
                            @if($item->is_billed)
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                    <i data-lucide="check" style="width: 12px; height: 12px;" class="me-1"></i>Billed
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">
                                    <i data-lucide="clock" style="width: 12px; height: 12px;" class="me-1"></i>Unbilled
                                </span>
                            @endif
                        </td>
                        <td class="text-end pe-3">
                            @if(!$item->is_billed)
                                <button type="button" class="btn btn-sm btn-light border p-1 rounded-2 text-danger" onclick="deleteCharge({{ $item->id }})" title="Void / Remove Charge">
                                    <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                </button>
                            @else
                                <span class="text-muted small" title="Locked: already billed"><i data-lucide="lock" style="width: 14px; height: 14px;"></i></span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                    <i data-lucide="receipt" style="width: 28px; height: 28px; stroke: #94a3b8;"></i>
                                </div>
                                <h6 class="fw-semibold text-secondary mb-1">No room charges posted yet.</h6>
                                <p class="small text-muted mb-0">Click "Post Charge to Room" to add an Angkor tour, laundry service, or spa treatment to a guest room.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-page-index>

<!-- Post Charge Modal -->
<div class="modal fade" id="chargeServiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-2">
                <h6 class="modal-title fw-bold"><i data-lucide="receipt" style="width: 18px; height: 18px;" class="me-2 text-primary"></i>Post Tour / Service Charge to Room</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="chargeServiceForm">
                @csrf
                <div class="modal-body py-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Select Staying Room / Guest <span class="text-danger">*</span></label>
                            <select name="check_in_id" id="modalCheckInId" class="form-select form-select-sm rounded-2" required>
                                <option value="">-- Choose Staying Room --</option>
                                @foreach($stayingCheckIns as $ci)
                                    <option value="{{ $ci->id }}" {{ $checkInId == $ci->id ? 'selected' : '' }}>
                                        Room {{ $ci->room?->room_number }} — {{ $ci->guest_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-semibold">Select Tour / Service <span class="text-danger">*</span></label>
                            <select name="service_id" id="modalServiceSelect" class="form-select form-select-sm rounded-2" required>
                                <option value="">-- Choose Service / Tour --</option>
                                @foreach($availableServices as $svc)
                                    <option value="{{ $svc->id }}" data-price="{{ $svc->price }}" data-unit="{{ $svc->unit }}">
                                        [{{ strtoupper($svc->category) }}] {{ $svc->name_en }} — ${{ number_format($svc->price, 2) }} / {{ $svc->unit }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Quantity / Weight <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <input type="number" step="0.1" min="0.1" name="quantity" id="modalQuantity" class="form-control rounded-start-2" value="1" required>
                                <span class="input-group-text rounded-end-2" id="modalUnitDisplay">units</span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Unit Price (USD) <span class="text-danger">*</span></label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text rounded-start-2">$</span>
                                <input type="number" step="0.01" min="0" name="unit_price" id="modalUnitPrice" class="form-control rounded-end-2" value="0.00" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Total Folio Charge</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text rounded-start-2">$</span>
                                <input type="text" id="modalTotalCalculated" class="form-control fw-bold text-primary bg-light rounded-end-2" readonly value="0.00">
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label small fw-semibold">Service Date</label>
                            <input type="text" name="service_date" class="form-control form-control-sm pickadate rounded-2" value="{{ date('d-m-Y') }}" autocomplete="off">
                        </div>
                        <div class="col-md-8">
                            <label class="form-label small fw-semibold">Notes / Reference Details</label>
                            <input type="text" name="notes" class="form-control form-control-sm rounded-2" placeholder="e.g. 4 shirts + 3 trousers, or Guide: Mr. Dara">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary rounded-2" data-bs-dismiss="modal">{{ __('global.cancel') }}</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-2">{{ __('Post Charge to Room') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function recalculateModalTotal() {
        const qty = parseFloat($('#modalQuantity').val()) || 0;
        const price = parseFloat($('#modalUnitPrice').val()) || 0;
        const total = (qty * price).toFixed(2);
        $('#modalTotalCalculated').val(total);
    }

    $(document).ready(function() {
        $('#modalServiceSelect').on('change', function() {
            const selected = $(this).find(':selected');
            const price = parseFloat(selected.data('price')) || 0;
            const unit = selected.data('unit') || 'units';
            $('#modalUnitPrice').val(price.toFixed(2));
            $('#modalUnitDisplay').text(unit);
            recalculateModalTotal();
        });

        $('#modalQuantity, #modalUnitPrice').on('input change', recalculateModalTotal);

        $('#chargeServiceForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                url: "{{ route('services.room-services.store') }}",
                method: "POST",
                data: $(this).serialize(),
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Charge Posted',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                },
                error: function(xhr) {
                    let msg = 'Failed to post charge.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    Swal.fire('Error', msg, 'error');
                }
            });
        });
    });

    function deleteCharge(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "Void this charge from the guest room folio?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, void it!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/services/room-charges/${id}/delete`,
                    method: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(response) {
                        Swal.fire('Voided!', response.message, 'success').then(() => {
                            window.location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error', xhr.responseJSON?.message || 'Failed to void charge', 'error');
                    }
                });
            }
        });
    }
</script>
@endpush
