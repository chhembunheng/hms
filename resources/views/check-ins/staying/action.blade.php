<div class="d-inline-flex dropdown ms-2">
    <a href="#" class="text-body" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">
        @foreach ($actions as $action)
            @if ($action['action'] === 'checkout')
                <a href="#" class="dropdown-item" onclick="showCheckOutModal({{ $row->id }}, '{{ $row->booking_number }}', {{ $row->total_amount }}, {{ $row->paid_amount }})">
                    <i class="fa-light {{ $action['icon'] }} me-2"></i> {{ $action->translations->firstWhere('locale', app()->getLocale())->name }}
                </a>
                @continue
            @endif
            @if ($action['target'] === 'self')
                <a href="{{ route($action['action_route'], ['id' => $row->id]) }}" class="dropdown-item">
                    <i class="fa-light {{ $action['icon'] }} me-2"></i> {{ $action->translations->firstWhere('locale', app()->getLocale())->name }}
                </a>
            @endif
        @endforeach
    </div>
</div>


<script>
function showCheckOutModal(id, bookingNumber, totalAmount, paidAmount) {
    const remainingAmount = totalAmount - paidAmount;

    // Create modal HTML
    const modalHtml = `
        <div class="modal fade" id="checkOutModal" tabindex="-1" aria-labelledby="checkOutModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="checkOutModalLabel">{{ __('checkins.check_out') }} & {{ __('checkins.payment_status') }} - ${bookingNumber}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="checkOutForm">
                            <div class="mb-3">
                                <label for="totalAmount" class="form-label">{{ __('checkins.total_amount') }}</label>
                                <input type="text" class="form-control" id="totalAmount" value="$${totalAmount.toFixed(2)}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="paidAmount" class="form-label">{{ __('checkins.paid_amount') }}</label>
                                <input type="text" class="form-control" id="paidAmount" value="$${paidAmount.toFixed(2)}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="remainingAmount" class="form-label">{{ __('checkins.remaining_amount') }}</label>
                                <input type="text" class="form-control" id="remainingAmount" value="$${remainingAmount.toFixed(2)}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="paymentAmount" class="form-label">{{ __('checkins.paid_amount') }}</label>
                                <input type="number" class="form-control" id="paymentAmount" step="0.01" min="0" max="${remainingAmount}" required>
                            </div>
                            <div class="mb-3">
                                <label for="paymentMethod" class="form-label">{{ __('checkins.payment_method') }}</label>
                                <select class="form-select form-select-sm select2" id="paymentMethod">
                                    <option value="">{{ __('form.select_option') }}</option>
                                    @foreach (paymentMethods() as $code => $name)
                                        <option value="{{ $code }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="checkOutNotes" class="form-label">{{ __('form.notes') }}</label>
                                <textarea class="form-control" id="checkOutNotes" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.cancel') }}</button>
                        <button type="button" class="btn btn-primary" onclick="processCheckOut(${id})">{{ __('checkins.check_out') }} & {{ __('checkins.payment_status') }}</button>
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

    // Initialize select2 for payment method
    $('#paymentMethod').select2({
        dropdownParent: $('#checkOutModal'),
        width: '100%'
    });
}

function processCheckOut(id) {
    const paymentAmount = parseFloat(document.getElementById('paymentAmount').value);
    const paymentMethod = document.getElementById('paymentMethod').value;
    const notes = document.getElementById('checkOutNotes').value;

    if (!paymentAmount || paymentAmount < 0) {
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
            success(data.message);
            // Close modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('checkOutModal'));
            modal.hide();
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        } else {
            error(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        error('An error occurred during check-out');
    });
}
</script>
