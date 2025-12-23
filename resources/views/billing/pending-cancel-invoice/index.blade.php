<x-app-layout>
    <div class="container-fluid py-3">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0">{{ __('billing.pending_cancel_invoices') }}</h4>
                        <a href="{{ route('billing.list.index') }}" class="btn btn-light btn-sm">
                            <i class="fas fa-arrow-left"></i> {{ __('global.back_to_list') }}
                        </a>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <form method="GET" action="{{ route('billing.pending-cancel-invoice.index') }}" class="row g-3">
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
                                        <a href="{{ route('billing.pending-cancel-invoice.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> {{ __('global.clear') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Pending Cancel Invoices Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>{{ __('billing.invoice_number') }}</th>
                                        <th>{{ __('billing.guest') }}</th>
                                        <th class="text-end">{{ __('billing.total_amount') }}</th>
                                        <th>{{ __('billing.requested_at') }}</th>
                                        <th>{{ __('billing.cancellation_reason') }}</th>
                                        <th class="text-center">{{ __('global.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->guest->full_name }}</td>
                                            <td class="text-end">${{ number_format($invoice->total_amount, 2) }}</td>
                                            <td>{{ $invoice->updated_at->format('d-m-Y H:i') }}</td>
                                            <td>
                                                @if($invoice->notes)
                                                    <span title="{{ $invoice->notes }}">{{ Str::limit($invoice->notes, 30) }}</span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('billing.pending-cancel-invoice.edit', $invoice->id) }}" class="btn btn-sm btn-outline-warning" title="{{ __('billing.review_cancellation') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" title="{{ __('billing.delete_request') }}"
                                                            onclick="confirmDelete({{ $invoice->id }}, '{{ $invoice->invoice_number }}')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">
                                                <div class="text-muted">
                                                    <i class="fas fa-clock fa-2x mb-2"></i>
                                                    <p>{{ __('billing.no_pending_cancel_invoices') }}</p>
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

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('billing.delete_pending_request') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('billing.delete_pending_request_confirmation') }}</p>
                    <p class="text-danger fw-bold" id="invoiceNumber"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('global.cancel') }}</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">{{ __('global.delete') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function confirmDelete(invoiceId, invoiceNumber) {
        document.getElementById('invoiceNumber').textContent = invoiceNumber;
        document.getElementById('deleteForm').action = '{{ url("billing/pending-cancel-invoice") }}/' + invoiceId + '/delete';
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    </script>
    @endpush
</x-app-layout>
