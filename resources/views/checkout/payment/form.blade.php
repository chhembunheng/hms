@extends('layouts.app')

@section('title', __('checkout.process_payment'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('checkout.process_payment') }}</h4>
                </div>
                <div class="card-body">
                    <!-- Invoice Summary -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">{{ __('checkout.invoice_summary') }}</h6>
                                    <p class="mb-1"><strong>{{ __('checkout.invoice_number') }}:</strong> {{ $invoice->invoice_number }}</p>
                                    <p class="mb-1"><strong>{{ __('checkout.guest') }}:</strong> {{ $invoice->guest->full_name }}</p>
                                    <p class="mb-1"><strong>{{ __('checkout.total_amount') }}:</strong> ${{ number_format($invoice->total_amount, 2) }}</p>
                                    <p class="mb-1"><strong>{{ __('checkout.paid_amount') }}:</strong> ${{ number_format($invoice->paid_amount, 2) }}</p>
                                    <p class="mb-1"><strong>{{ __('checkout.balance_amount') }}:</strong> ${{ number_format($invoice->balance_amount, 2) }}</p>
                                    <p class="mb-0">
                                        <strong>{{ __('checkout.status') }}:</strong>
                                        <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : ($invoice->status === 'partially_paid' ? 'bg-warning' : 'bg-danger') }}">
                                            {{ __('checkout.' . $invoice->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="card-title">{{ __('checkout.stay_details') }}</h6>
                                    <p class="mb-1"><strong>{{ __('rooms.room') }}:</strong> {{ $invoice->checkIn->room->room_number }}</p>
                                    <p class="mb-1"><strong>{{ __('checkins.check_in_date') }}:</strong> {{ $invoice->checkIn->check_in_date->format('d-m-Y') }}</p>
                                    <p class="mb-1"><strong>{{ __('checkins.check_out_date') }}:</strong> {{ $invoice->checkIn->actual_check_out_at->format('d-m-Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Form -->
                    @if($invoice->balance_amount > 0)
                        <form action="{{ route('checkout.payment.process', $invoice->id) }}" method="POST" id="paymentForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">{{ __('checkout.payment_details') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="payment_method" class="form-label">{{ __('checkout.payment_method') }} <span class="text-danger">*</span></label>
                                                        <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                                            <option value="">{{ __('global.select_option') }}</option>
                                                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>{{ __('checkout.cash') }}</option>
                                                            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>{{ __('checkout.card') }}</option>
                                                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>{{ __('checkout.bank_transfer') }}</option>
                                                            <option value="check" {{ old('payment_method') === 'check' ? 'selected' : '' }}>{{ __('checkout.check') }}</option>
                                                        </select>
                                                        @error('payment_method')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for="payment_amount" class="form-label">{{ __('checkout.payment_amount') }} <span class="text-danger">*</span></label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">$</span>
                                                            <input type="number" class="form-control @error('payment_amount') is-invalid @enderror" id="payment_amount" name="payment_amount"
                                                                   value="{{ old('payment_amount', $invoice->balance_amount) }}" step="0.01" min="0.01" max="{{ $invoice->balance_amount }}" required>
                                                        </div>
                                                        @error('payment_amount')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                        <div class="form-text">{{ __('checkout.maximum_amount') }}: ${{ number_format($invoice->balance_amount, 2) }}</div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="payment_date" class="form-label">{{ __('checkout.payment_date') }}</label>
                                                <input type="datetime-local" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date"
                                                       value="{{ old('payment_date', now()->format('Y-m-d\TH:i')) }}">
                                                @error('payment_date')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="reference_number" class="form-label">{{ __('checkout.reference_number') }}</label>
                                                <input type="text" class="form-control @error('reference_number') is-invalid @enderror" id="reference_number" name="reference_number"
                                                       value="{{ old('reference_number') }}" placeholder="{{ __('checkout.transaction_reference') }}">
                                                @error('reference_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="notes" class="form-label">{{ __('checkout.payment_notes') }}</label>
                                                <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes" rows="3"
                                                          placeholder="{{ __('checkout.optional_payment_notes') }}">{{ old('notes') }}</textarea>
                                                @error('notes')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">{{ __('checkout.payment_summary') }}</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ __('checkout.total_amount') }}:</span>
                                                    <strong>${{ number_format($invoice->total_amount, 2) }}</strong>
                                                </div>
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ __('checkout.previous_payments') }}:</span>
                                                    <strong>${{ number_format($invoice->paid_amount, 2) }}</strong>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ __('checkout.balance_due') }}:</span>
                                                    <strong class="text-danger">${{ number_format($invoice->balance_amount, 2) }}</strong>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ __('checkout.payment_amount') }}:</span>
                                                    <strong id="displayPaymentAmount">${{ number_format($invoice->balance_amount, 2) }}</strong>
                                                </div>
                                                <hr>
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ __('checkout.remaining_balance') }}:</span>
                                                    <strong id="remainingBalance">$0.00</strong>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-grid gap-2 mt-3">
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-credit-card"></i> {{ __('checkout.process_payment') }}
                                        </button>
                                        <a href="{{ route('checkout.invoice.show', $invoice->id) }}" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> {{ __('global.back') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-success">
                            <h5><i class="fas fa-check-circle"></i> {{ __('checkout.invoice_fully_paid') }}</h5>
                            <p class="mb-0">{{ __('checkout.no_payment_required') }}</p>
                        </div>
                        <div class="text-center">
                            <a href="{{ route('checkout.invoice.show', $invoice->id) }}" class="btn btn-primary">
                                <i class="fas fa-eye"></i> {{ __('checkout.view_invoice') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentAmountInput = document.getElementById('payment_amount');
    const displayPaymentAmount = document.getElementById('displayPaymentAmount');
    const remainingBalance = document.getElementById('remainingBalance');
    const balanceDue = {{ $invoice->balance_amount }};

    function updatePaymentSummary() {
        const paymentAmount = parseFloat(paymentAmountInput.value) || 0;
        const remaining = balanceDue - paymentAmount;

        displayPaymentAmount.textContent = '$' + paymentAmount.toFixed(2);
        remainingBalance.textContent = '$' + Math.max(0, remaining).toFixed(2);

        if (remaining < 0) {
            remainingBalance.classList.add('text-danger');
        } else {
            remainingBalance.classList.remove('text-danger');
        }
    }

    paymentAmountInput.addEventListener('input', updatePaymentSummary);
    updatePaymentSummary(); // Initial calculation

    // Form validation
    const paymentForm = document.getElementById('paymentForm');
    if (paymentForm) {
        paymentForm.addEventListener('submit', function(e) {
            const paymentAmount = parseFloat(paymentAmountInput.value);
            if (paymentAmount > balanceDue) {
                e.preventDefault();
                alert('{{ __("checkout.payment_amount_exceeds_balance") }}');
                return false;
            }
        });
    }
});
</script>
@endpush
@endsection
