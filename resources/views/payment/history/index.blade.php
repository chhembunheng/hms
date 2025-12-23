<x-app-layout>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h3 class="card-title mb-0">{{ __('billing.payment_history') }}</h3>
                    </div>
                    <div class="card-body">
                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ __('billing.total_payments') }}</h5>
                                        <h3 class="mb-0">${{ number_format($totalPayments, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ __('billing.payments_in_period') }}</h5>
                                        <h3 class="mb-0">${{ number_format($totalPaymentsInPeriod, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-secondary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">{{ __('billing.total_transactions') }}</h5>
                                        <h3 class="mb-0">{{ number_format($paymentCount) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <form method="GET" action="{{ route('payment.history.index') }}" class="row g-3">
                                    <div class="col-md-2">
                                        <label for="payment_method" class="form-label">{{ __('billing.filter_by_payment_method') }}</label>
                                        <select class="form-select" id="payment_method" name="payment_method">
                                            <option value="">{{ __('global.all') }}</option>
                                            <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>{{ __('checkout.cash') }}</option>
                                            <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>{{ __('checkout.card') }}</option>
                                            <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>{{ __('checkout.bank_transfer') }}</option>
                                            <option value="check" {{ request('payment_method') === 'check' ? 'selected' : '' }}>{{ __('checkout.check') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label for="date_from" class="form-label">{{ __('billing.date_from') }}</label>
                                        <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="date_to" class="form-label">{{ __('billing.date_to') }}</label>
                                        <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="amount_from" class="form-label">{{ __('billing.amount_from') }}</label>
                                        <input type="number" class="form-control" id="amount_from" name="amount_from" value="{{ request('amount_from') }}" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-2">
                                        <label for="amount_to" class="form-label">{{ __('billing.amount_to') }}</label>
                                        <input type="number" class="form-control" id="amount_to" name="amount_to" value="{{ request('amount_to') }}" step="0.01" min="0">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fas fa-search"></i> {{ __('global.search') }}
                                        </button>
                                        <a href="{{ route('payment.history.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> {{ __('global.clear') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Payments Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('billing.invoice_number') }}</th>
                                        <th>{{ __('billing.guest') }}</th>
                                        <th>{{ __('billing.payment_date') }}</th>
                                        <th>{{ __('billing.payment_method') }}</th>
                                        <th>{{ __('billing.payment_amount') }}</th>
                                        <th>{{ __('billing.reference_number') }}</th>
                                        <th>{{ __('billing.processed_by') }}</th>
                                        <th>{{ __('global.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payments as $payment)
                                        <tr>
                                            <td>
                                                <a href="{{ route('checkout.invoice.show', $payment->invoice_id) }}" class="text-decoration-none">
                                                    {{ $payment->invoice->invoice_number }}
                                                </a>
                                            </td>
                                            <td>{{ $payment->invoice->guest->full_name }}</td>
                                            <td>{{ $payment->payment_date->format('d-m-Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $payment->payment_method_label }}</span>
                                            </td>
                                            <td class="text-end fw-bold">${{ number_format($payment->amount, 2) }}</td>
                                            <td>{{ $payment->reference_number ?: '-' }}</td>
                                            <td>{{ $payment->processor->name ?? __('global.unknown') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('checkout.invoice.show', $payment->invoice_id) }}" class="btn btn-sm btn-info" title="{{ __('billing.view_invoice') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">{{ __('billing.no_payments_found') }}</h5>
                                                    <p class="text-muted mb-0">{{ __('billing.no_payments_description') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($payments->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $payments->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
