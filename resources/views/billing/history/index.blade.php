<x-app-layout>
    <div class="container-fluid py-3">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">{{ __('billing.invoice_history') }}</h4>
                        <a href="{{ route('billing.list.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('global.back_to_list') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <form method="GET" action="{{ route('billing.history.index') }}" class="row g-3">
                                    <div class="col-md-3">
                                        <x-form.input label="{{ __('billing.date_from') }}" name="date_from" type="date" :value="request('date_from')" />
                                    </div>
                                    <div class="col-md-3">
                                        <x-form.input label="{{ __('billing.date_to') }}" name="date_to" type="date" :value="request('date_to')" />
                                    </div>
                                    <div class="col-md-4">
                                        <x-form.input label="{{ __('billing.search') }}" name="search" type="text" :value="request('search')" placeholder="{{ __('billing.search_by_invoice_or_guest') }}" />
                                    </div>
                                    <div class="col-md-2 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fas fa-search"></i> {{ __('global.search') }}
                                        </button>
                                        <a href="{{ route('billing.history.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> {{ __('global.clear') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Deleted Invoices Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>{{ __('billing.invoice_number') }}</th>
                                        <th>{{ __('billing.guest') }}</th>
                                        <th class="text-end">{{ __('billing.total_amount') }}</th>
                                        <th>{{ __('billing.deleted_at') }}</th>
                                        <th class="text-center">{{ __('global.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->guest->full_name }}</td>
                                            <td class="text-end">${{ number_format($invoice->total_amount, 2) }}</td>
                                            <td>{{ $invoice->deleted_at->format('d-m-Y H:i') }}</td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <button type="button" class="btn btn-sm btn-outline-info" title="{{ __('billing.view_details') }}"
                                                            onclick="viewInvoiceDetails({{ $invoice->id }})">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-history fa-2x mb-2"></i>
                                                    <p>{{ __('billing.no_deleted_invoices') }}</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        @if($invoices->hasPages())
                            <div class="d-flex justify-content-center mt-4">
                                {{ $invoices->appends(request()->query())->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Details Modal -->
    <div class="modal fade" id="invoiceDetailsModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('billing.invoice_details') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="invoiceDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.close') }}</button>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function viewInvoiceDetails(invoiceId) {
        // For now, just show a placeholder. In a real implementation, you might load details via AJAX
        const content = `
            <div class="text-center">
                <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                <h5>{{ __('billing.invoice_details') }}</h5>
                <p class="text-muted">{{ __('billing.invoice_details_not_available') }}</p>
                <p class="text-muted">{{ __('billing.invoice_was_deleted_on') }}: <strong id="deletedDate"></strong></p>
            </div>
        `;

        document.getElementById('invoiceDetailsContent').innerHTML = content;
        new bootstrap.Modal(document.getElementById('invoiceDetailsModal')).show();
    }
    </script>
    @endpush
</x-app-layout>
