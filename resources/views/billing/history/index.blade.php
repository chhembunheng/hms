<x-page-index 
    :title="__('billing.invoice_history')" 
    icon="history">

    <x-slot:actions>
        <a href="{{ route('billing.list.index') }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 rounded-2">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            <span>{{ __('global.back_to_list') }}</span>
        </a>
    </x-slot:actions>

    <x-slot:filters>
        <form method="GET" action="{{ route('billing.history.index') }}" class="row g-3 px-1 py-1">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('billing.date_from') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="date_from" class="form-control form-control-sm rounded-start-2 pickadate" placeholder="dd-mm-yyyy" value="{{ request('date_from') ? format_date(request('date_from')) : '' }}" autocomplete="off">
                    <span class="input-group-text rounded-end-2"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('billing.date_to') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="date_to" class="form-control form-control-sm rounded-start-2 pickadate" placeholder="dd-mm-yyyy" value="{{ request('date_to') ? format_date(request('date_to')) : '' }}" autocomplete="off">
                    <span class="input-group-text rounded-end-2"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('billing.search') }}</label>
                <input type="text" name="search" class="form-control form-control-sm rounded-2" value="{{ request('search') }}" placeholder="{{ __('billing.search_by_invoice_or_guest') }}">
            </div>
            <div class="col-md-2 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-sm btn-primary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                    <i data-lucide="filter" style="width: 13px; height: 13px;"></i>
                    <span>{{ __('global.filter') }}</span>
                </button>
                <a href="{{ route('billing.history.index') }}" class="btn btn-sm btn-outline-secondary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                    <i data-lucide="rotate-ccw" style="width: 13px; height: 13px;"></i>
                    <span>{{ __('global.clear') }}</span>
                </a>
            </div>
        </form>
    </x-slot:filters>

    <!-- Deleted Invoices Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 datatables-custom">
            <thead class="table-light">
                <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #64748b;">
                    <th class="ps-3">{{ __('billing.invoice_number') }}</th>
                    <th>{{ __('billing.guest') }}</th>
                    <th class="text-end">{{ __('billing.total_amount') }}</th>
                    <th>{{ __('billing.deleted_at') }}</th>
                    <th class="text-end pe-3">{{ __('global.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $invoice)
                    <tr>
                        <td class="ps-3 fw-bold text-dark">{{ $invoice->invoice_number }}</td>
                        <td class="fw-medium text-dark">{{ $invoice->guest->full_name }}</td>
                        <td class="text-end fw-semibold">${{ number_format($invoice->total_amount, 2) }}</td>
                        <td class="text-muted small">{{ $invoice->deleted_at->format('d-m-Y H:i') }}</td>
                        <td class="text-end pe-3">
                            <button type="button" class="btn btn-sm btn-light border p-1 rounded-2 text-primary" title="{{ __('billing.view_details') }}"
                                    onclick="viewInvoiceDetails({{ $invoice->id }})">
                                <i data-lucide="eye" style="width: 14px; height: 14px;"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                    <i data-lucide="history" style="width: 28px; height: 28px; stroke: #94a3b8;"></i>
                                </div>
                                <h6 class="fw-semibold text-secondary mb-1">{{ __('billing.no_deleted_invoices') }}</h6>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($invoices->hasPages())
        <div class="d-flex justify-content-between align-items-center pt-3 px-3 border-top">
            <small class="text-muted">
                {{ __('global.showing') ?? 'Showing' }} {{ $invoices->firstItem() }} {{ __('global.to') ?? 'to' }} {{ $invoices->lastItem() }} {{ __('global.of') ?? 'of' }} {{ $invoices->total() }} {{ __('global.entries') ?? 'entries' }}
            </small>
            <div>{{ $invoices->appends(request()->query())->links() }}</div>
        </div>
    @endif
</x-page-index>

<!-- Invoice Details Modal -->
<div class="modal fade" id="invoiceDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom py-2">
                <h6 class="modal-title fw-bold">{{ __('billing.invoice_details') }}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-4 text-center" id="invoiceDetailsContent">
                <i data-lucide="file-text" style="width: 48px; height: 48px; stroke: #94a3b8;" class="mb-3"></i>
                <h5>{{ __('billing.invoice_details') }}</h5>
                <p class="text-muted">{{ __('billing.invoice_details_not_available') }}</p>
                <p class="text-muted small">{{ __('billing.invoice_was_deleted_on') }}: <strong id="deletedDate"></strong></p>
            </div>
            <div class="modal-footer border-top py-2">
                <button type="button" class="btn btn-sm btn-secondary rounded-2" data-bs-dismiss="modal">{{ __('global.close') }}</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewInvoiceDetails(invoiceId) {
    new bootstrap.Modal(document.getElementById('invoiceDetailsModal')).show();
}
</script>
@endpush
