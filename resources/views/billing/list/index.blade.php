<x-page-index 
    :title="__('billing.payment_invoice')" 
    icon="file-text">

    <x-slot:filters>
        <div class="px-2 py-1">
            <form method="GET" action="{{ route('billing.list.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="search" class="form-label small fw-semibold text-muted mb-1">{{ __('global.search') }}</label>
                    <input type="text" class="form-control form-control-sm rounded-2" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('global.search') }}...">
                </div>

                <div class="col-md-2">
                    <label for="status" class="form-label small fw-semibold text-muted mb-1">{{ __('billing.filter_by_status') }}</label>
                    <select class="form-select form-select-sm rounded-2" id="status" name="status">
                        <option value="">{{ __('global.all') }}</option>
                        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>{{ __('billing.paid') }}</option>
                        <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>{{ __('billing.unpaid') }}</option>
                        <option value="partially_paid" {{ request('status') === 'partially_paid' ? 'selected' : '' }}>{{ __('billing.partially_paid') }}</option>
                        <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>{{ __('billing.overdue') }}</option>
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

                <div class="col-md-3 d-flex align-items-end justify-content-end gap-2">
                    <a href="{{ route('billing.list.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 rounded-2 px-3">
                        <i data-lucide="rotate-ccw" style="width: 13px; height: 13px;"></i>
                        <span>{{ __('global.clear') }}</span>
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 rounded-2 px-3">
                        <i data-lucide="filter" style="width: 13px; height: 13px;"></i>
                        <span>{{ __('global.filter') }}</span>
                    </button>
                </div>
            </form>
        </div>
    </x-slot:filters>

    <!-- Invoices Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 datatables-custom">
            <thead class="table-light">
                <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #64748b;">
                    <th class="ps-3">{{ __('billing.invoice_number') }}</th>
                    <th>{{ __('billing.guest') }}</th>
                    <th>{{ __('billing.total_amount') }}</th>
                    <th>{{ __('billing.paid_amount') }}</th>
                    <th>{{ __('billing.balance_amount') }}</th>
                    <th>{{ __('billing.status') }}</th>
                    <th>{{ __('billing.invoice_date') }}</th>
                    <th class="text-end pe-3">{{ __('global.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td class="ps-3 fw-semibold">
                            <a href="{{ route('checkout.invoice.show', $invoice->id) }}" class="text-decoration-none text-primary">
                                {{ $invoice->invoice_number }}
                            </a>
                        </td>
                        <td class="fw-medium text-dark">{{ $invoice->guest->full_name }}</td>
                        <td class="fw-semibold text-dark">${{ number_format($invoice->total_amount, 2) }}</td>
                        <td class="text-success fw-medium">${{ number_format($invoice->paid_amount, 2) }}</td>
                        <td class="{{ $invoice->balance_amount > 0 ? 'text-danger fw-bold' : 'text-muted' }}">
                            ${{ number_format($invoice->balance_amount, 2) }}
                        </td>
                        <td>
                            @if($invoice->status === 'paid')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                    {{ __('billing.paid') }}
                                </span>
                            @elseif($invoice->status === 'partially_paid')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">
                                    {{ __('billing.partially_paid') }}
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">
                                    {{ __('billing.' . $invoice->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                        <td class="text-end pe-3">
                            <div class="d-inline-flex align-items-center gap-1">
                                <a href="{{ route('checkout.invoice.show', $invoice->id) }}" class="btn btn-sm btn-light border p-1 rounded-2 text-primary" title="{{ __('billing.view_invoice') }}">
                                    <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                                </a>
                                <a href="{{ route('checkout.invoice.print', $invoice->id) }}" class="btn btn-sm btn-light border p-1 rounded-2 text-secondary" title="{{ __('billing.print_invoice') }}" target="_blank">
                                    <i data-lucide="printer" style="width: 14px; height: 14px;"></i>
                                </a>
                                @if($invoice->balance_amount > 0)
                                    <a href="{{ route('checkout.payment.process', $invoice->id) }}" class="btn btn-sm btn-light border p-1 rounded-2 text-success" title="{{ __('billing.process_payment') }}">
                                        <i data-lucide="credit-card" style="width: 14px; height: 14px;"></i>
                                    </a>
                                @endif
                                @if($invoice->paid_amount == 0)
                                    <button type="button" class="btn btn-sm btn-light border p-1 rounded-2 text-danger" title="{{ __('billing.delete_invoice') }}"
                                            onclick="confirmDelete({{ $invoice->id }}, '{{ $invoice->invoice_number }}')">
                                        <i data-lucide="trash-2" style="width: 14px; height: 14px;"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="d-flex flex-column align-items-center text-muted">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                    <i data-lucide="file-text" style="width: 28px; height: 28px; stroke: #94a3b8;"></i>
                                </div>
                                <h6 class="fw-semibold text-secondary mb-1">{{ __('billing.no_invoices_found') }}</h6>
                                <p class="small text-muted mb-0">{{ __('billing.no_invoices_description') }}</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($invoices->hasPages())
        <div class="d-flex justify-content-between align-items-center pt-3 px-3 border-top">
            <small class="text-muted">
                {{ __('global.showing') ?? 'Showing' }} {{ $invoices->firstItem() }} {{ __('global.to') ?? 'to' }} {{ $invoices->lastItem() }} {{ __('global.of') ?? 'of' }} {{ $invoices->total() }} {{ __('global.entries') ?? 'entries' }}
            </small>
            <div>
                {{ $invoices->appends(request()->query())->links() }}
            </div>
        </div>
    @endif
</x-page-index>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-2">
                <h6 class="modal-title fw-bold" id="deleteModalLabel">{{ __('billing.delete_invoice') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-3">
                <p class="text-muted mb-2">{{ __('billing.delete_invoice_confirmation') }}</p>
                <div class="alert alert-danger py-2 px-3 mb-0 fw-bold fs-6" id="invoiceNumber"></div>
            </div>
            <div class="modal-footer border-top py-2">
                <button type="button" class="btn btn-sm btn-outline-secondary rounded-2" data-bs-dismiss="modal">{{ __('global.cancel') }}</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger rounded-2">{{ __('global.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmDelete(invoiceId, invoiceNumber) {
    document.getElementById('invoiceNumber').textContent = invoiceNumber;
    document.getElementById('deleteForm').action = '{{ url("billing/list") }}/' + invoiceId + '/delete';
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
