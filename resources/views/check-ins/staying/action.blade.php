<div class="d-inline-flex dropdown ms-2">
    <a href="#" class="text-body" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">
        @foreach ($actions as $action)
            @if ($action['action'] === 'index' || $action['action'] === 'view')
                @continue
            @endif
            @if ($action['action'] === 'checkout')
                @php
                    $actIcon = $action['icon'] ?? 'fa-receipt';
                    if (!str_contains($actIcon, 'fa-solid') && !str_contains($actIcon, 'fa-regular')) {
                        $actIcon = str_starts_with($actIcon, 'fa-') ? 'fa-solid ' . $actIcon : 'fa-solid fa-' . $actIcon;
                    }
                @endphp
                <a href="#" class="dropdown-item d-flex align-items-center" onclick="showCheckOutModal({{ $row->id }}, '{{ $row->booking_number }}', {{ $row->total_amount }}, {{ $row->paid_amount }})">
                    <i class="{{ $actIcon }} me-2"></i> {{ $action->translations->firstWhere('locale', app()->getLocale())->name }}
                </a>
                @continue
            @endif
            @if ($action['target'] === 'self')
                @php
                    $actIcon = $action['icon'] ?? 'fa-circle';
                    if (!str_contains($actIcon, 'fa-solid') && !str_contains($actIcon, 'fa-regular')) {
                        $actIcon = str_starts_with($actIcon, 'fa-') ? 'fa-solid ' . $actIcon : 'fa-solid fa-' . $actIcon;
                    }
                @endphp
                <a href="{{ route($action['action_route'], ['id' => $row->id]) }}" class="dropdown-item d-flex align-items-center">
                    <i class="{{ $actIcon }} me-2"></i> {{ $action->translations->firstWhere('locale', app()->getLocale())->name }}
                </a>
            @endif
        @endforeach
    </div>
</div>


<script>
function showCheckOutModal(id, bookingNumber, totalAmount, paidAmount) {
    const remainingAmount = Math.max(0, totalAmount - paidAmount);
    const rate = {{ active_exchange_rate() }};
    const totalKhr = new Intl.NumberFormat().format(Math.round(totalAmount * rate / 100) * 100);
    const paidKhr = new Intl.NumberFormat().format(Math.round(paidAmount * rate / 100) * 100);
    const remainingKhr = new Intl.NumberFormat().format(Math.round(remainingAmount * rate / 100) * 100);

    // Create modal HTML
    const modalHtml = `
        <div class="modal fade" id="checkOutModal" tabindex="-1" aria-labelledby="checkOutModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title d-flex align-items-center" id="checkOutModalLabel"><i class="fa-solid fa-receipt me-2"></i>{{ __('checkins.check_out') }} & {{ __('checkins.payment_status') }} - ${bookingNumber}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-light border py-2 mb-3">
                            <small class="text-muted d-flex align-items-center"><i class="fa-solid fa-coins me-1 text-warning"></i>Exchange Rate: <strong>&nbsp;1 USD = ${new Intl.NumberFormat().format(rate)} KHR (៛)</strong></small>
                        </div>
                        <form id="checkOutForm">
                            <div class="row g-2 mb-3">
                                <div class="col-6">
                                    <label class="form-label text-muted small mb-1">{{ __('checkins.total_amount') }}</label>
                                    <div class="fw-bold fs-6">$${totalAmount.toFixed(2)}</div>
                                    <small class="text-primary">${totalKhr} ៛</small>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted small mb-1">{{ __('checkins.paid_amount') }}</label>
                                    <div class="fw-bold fs-6 text-success">$${paidAmount.toFixed(2)}</div>
                                    <small class="text-success">${paidKhr} ៛</small>
                                </div>
                            </div>
                            <div class="p-2 bg-light rounded border mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-semibold text-secondary">{{ __('checkins.remaining_amount') }}:</span>
                                    <div class="text-end">
                                        <div class="fw-bold text-danger fs-5">$${remainingAmount.toFixed(2)}</div>
                                        <small class="text-danger fw-bold">${remainingKhr} ៛</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="paymentAmount" class="form-label fw-semibold">Payment to Settle Now (USD) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">$</span>
                                    <input type="number" class="form-control" id="paymentAmount" step="0.01" min="0" max="${remainingAmount}" value="${remainingAmount.toFixed(2)}" required>
                                </div>
                                <div class="form-text text-primary" id="modalKhrCalc">≈ ${remainingKhr} ៛</div>
                            </div>
                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label fw-semibold">{{ __('checkins.payment_method') }}</label>
                                <select class="form-select form-select-sm select2" id="paymentMethod">
                                    @foreach (paymentMethods() as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="checkOutNotes" class="form-label">{{ __('form.notes') }}</label>
                                <textarea class="form-control" id="checkOutNotes" rows="2" placeholder="Minibar, laundry, room condition, etc."></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.cancel') }}</button>
                        <button type="button" class="btn btn-primary d-flex align-items-center" onclick="processCheckOut(${id})"><i class="fa-solid fa-check me-1"></i>Confirm {{ __('checkins.check_out') }}</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Remove existing modal if present
    const existingModal = document.getElementById('checkOutModal');
    if (existingModal) {
        existingModal.remove();
    }

    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);

    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('checkOutModal'));
    modal.show();

    // Listen to amount changes for KHR calculation
    const paymentInput = document.getElementById('paymentAmount');
    if (paymentInput) {
        paymentInput.addEventListener('input', function() {
            const val = parseFloat(this.value) || 0;
            const khr = new Intl.NumberFormat().format(Math.round(val * rate / 100) * 100);
            document.getElementById('modalKhrCalc').textContent = `≈ ${khr} ៛`;
        });
    }

    // Initialize select2 for payment method
    $('#paymentMethod').select2({
        dropdownParent: $('#checkOutModal'),
        width: '100%'
    });
}

function processCheckOut(id) {
    const paymentAmountInput = document.getElementById('paymentAmount').value;
    const paymentAmount = parseFloat(paymentAmountInput);
    const paymentMethod = document.getElementById('paymentMethod').value;
    const notes = document.getElementById('checkOutNotes').value;

    if (isNaN(paymentAmount) || paymentAmount < 0) {
        toastr.error('Please enter a valid payment amount');
        return;
    }

    // Send check-out request with payment data
    fetch(`{{ url('checkin/staying') }}/${id}/check-out`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            paid_amount: paymentAmount,
            payment_method: paymentMethod,
            notes: notes
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            toastr.success(data.message);
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('checkOutModal'));
            if (modal) {
                modal.hide();
            }
            setTimeout(() => {
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.reload();
                }
            }, 1000);
        } else {
            toastr.error(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toastr.error('An error occurred during check-out');
    });
}
</script>
