<x-page-index 
    :title="__('billing.payment_history')" 
    icon="credit-card">

    <x-slot:filters>
        <!-- KPI Summary Cards -->
        <div class="row g-2 mb-3 px-1">
            <div class="col-md-4">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="dollar-sign" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('billing.total_payments') }}</div>
                        <div class="fw-bold text-primary fs-6">${{ number_format($totalPayments, 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="calendar" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('billing.payments_in_period') }}</div>
                        <div class="fw-bold text-info fs-6">${{ number_format($totalPaymentsInPeriod, 2) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-secondary-subtle text-secondary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="receipt" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('billing.total_transactions') }}</div>
                        <div class="fw-bold text-dark fs-6">{{ number_format($paymentCount) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('payment.history.index') }}" class="row g-2 align-items-end px-1">
            <div class="col-md-2">
                <label for="payment_method" class="form-label small fw-semibold text-muted mb-1">{{ __('billing.filter_by_payment_method') }}</label>
                <select class="form-select form-select-sm rounded-2" id="payment_method" name="payment_method">
                    <option value="">{{ __('global.all') }}</option>
                    @foreach(paymentMethods() as $pCode => $pName)
                        <option value="{{ $pCode }}" {{ request('payment_method') === $pCode ? 'selected' : '' }}>{{ $pName }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="date_from" class="form-label small fw-semibold text-muted mb-1">{{ __('billing.date_from') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control form-control-sm rounded-start-2 pickadate" id="date_from" name="date_from" placeholder="dd-mm-yyyy" value="{{ request('date_from') ? format_date(request('date_from')) : '' }}" autocomplete="off">
                    <span class="input-group-text rounded-end-2"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                </div>
            </div>
            <div class="col-md-2">
                <label for="date_to" class="form-label small fw-semibold text-muted mb-1">{{ __('billing.date_to') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" class="form-control form-control-sm rounded-start-2 pickadate" id="date_to" name="date_to" placeholder="dd-mm-yyyy" value="{{ request('date_to') ? format_date(request('date_to')) : '' }}" autocomplete="off">
                    <span class="input-group-text rounded-end-2"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                </div>
            </div>
            <div class="col-md-2">
                <label for="amount_from" class="form-label small fw-semibold text-muted mb-1">{{ __('billing.amount_from') }}</label>
                <input type="number" class="form-control form-control-sm rounded-2" id="amount_from" name="amount_from" value="{{ request('amount_from') }}" step="0.01" min="0">
            </div>
            <div class="col-md-2">
                <label for="amount_to" class="form-label small fw-semibold text-muted mb-1">{{ __('billing.amount_to') }}</label>
                <input type="number" class="form-control form-control-sm rounded-2" id="amount_to" name="amount_to" value="{{ request('amount_to') }}" step="0.01" min="0">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                    <i data-lucide="filter" style="width: 13px; height: 13px;"></i>
                    <span>{{ __('global.filter') }}</span>
                </button>
                <a href="{{ route('payment.history.index') }}" class="btn btn-sm btn-outline-secondary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                    <i data-lucide="rotate-ccw" style="width: 13px; height: 13px;"></i>
                    <span>{{ __('global.clear') }}</span>
                </a>
            </div>
        </form>
    </x-slot:filters>

    <!-- Payments Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 datatables-custom">
            <thead class="table-light">
                <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #64748b;">
                    <th class="ps-3">{{ __('billing.invoice_number') }}</th>
                    <th>{{ __('billing.guest') }}</th>
                    <th>{{ __('billing.payment_date') }}</th>
                    <th>{{ __('billing.payment_method') }}</th>
                    <th class="text-end">{{ __('billing.payment_amount') }}</th>
                    <th>{{ __('billing.reference_number') }}</th>
                    <th>{{ __('billing.processed_by') }}</th>
                    <th class="text-end pe-3">{{ __('global.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                    <tr>
                        <td class="ps-3 fw-semibold">
                            <a href="{{ route('checkout.invoice.show', $payment->invoice_id) }}" class="text-decoration-none text-primary">
                                {{ $payment->invoice->invoice_number }}
                            </a>
                        </td>
                        <td class="fw-medium text-dark">{{ $payment->invoice->guest->full_name }}</td>
                        <td class="text-muted small">{{ $payment->payment_date->format('d-m-Y H:i') }}</td>
                        <td>
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">
                                {{ $payment->payment_method_label }}
                            </span>
                        </td>
                        <td class="text-end fw-bold text-success">${{ number_format($payment->amount, 2) }}</td>
                        <td>{{ $payment->reference_number ?: '-' }}</td>
                        <td class="text-muted small">{{ $payment->processor->name ?? __('global.unknown') }}</td>
                        <td class="text-end pe-3">
                            <a href="{{ route('checkout.invoice.show', $payment->invoice_id) }}" class="btn btn-sm btn-light border p-1 rounded-2 text-primary" title="{{ __('billing.view_invoice') }}">
                                <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                    <i data-lucide="credit-card" style="width: 28px; height: 28px; stroke: #94a3b8;"></i>
                                </div>
                                <h6 class="fw-semibold text-secondary mb-1">{{ __('billing.no_payments_found') }}</h6>
                                <p class="small text-muted mb-0">{{ __('billing.no_payments_description') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($payments->hasPages())
        <div class="d-flex justify-content-between align-items-center pt-3 px-3 border-top">
            <small class="text-muted">
                {{ __('global.showing') ?? 'Showing' }} {{ $payments->firstItem() }} {{ __('global.to') ?? 'to' }} {{ $payments->lastItem() }} {{ __('global.of') ?? 'of' }} {{ $payments->total() }} {{ __('global.entries') ?? 'entries' }}
            </small>
            <div>{{ $payments->appends(request()->query())->links() }}</div>
        </div>
    @endif
</x-page-index>
