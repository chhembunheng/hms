<x-app-layout>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h3 class="card-title mb-0">{{ __('billing.payment_invoice') }}</h3>
                    </div>
                    <div class="card-body">
                        <!-- Filters -->
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <form method="GET" action="{{ route('billing.list.index') }}" class="row g-3">
                                    <div class="col-md-2">
                                        <label for="status" class="form-label">{{ __('billing.filter_by_status') }}</label>
                                        <select class="form-select" id="status" name="status">
                                            <option value="">{{ __('global.all') }}</option>
                                            <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>{{ __('billing.paid') }}</option>
                                            <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>{{ __('billing.unpaid') }}</option>
                                            <option value="partially_paid" {{ request('status') === 'partially_paid' ? 'selected' : '' }}>{{ __('billing.partially_paid') }}</option>
                                            <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>{{ __('billing.overdue') }}</option>
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
                                    <div class="col-md-3">
                                        <label for="search" class="form-label">{{ __('billing.search') }}</label>
                                        <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="{{ __('billing.search') }}...">
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <button type="submit" class="btn btn-primary me-2">
                                            <i class="fas fa-search"></i> {{ __('global.search') }}
                                        </button>
                                        <a href="{{ route('billing.list.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times"></i> {{ __('global.clear') }}
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Invoices Table -->
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('billing.invoice_number') }}</th>
                                        <th>{{ __('billing.guest') }}</th>
                                        <th>{{ __('billing.total_amount') }}</th>
                                        <th>{{ __('billing.paid_amount') }}</th>
                                        <th>{{ __('billing.balance_amount') }}</th>
                                        <th>{{ __('billing.status') }}</th>
                                        <th>{{ __('billing.invoice_date') }}</th>
                                        <th>{{ __('global.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <a href="{{ route('checkout.invoice.show', $invoice->id) }}" class="text-decoration-none">
                                                    {{ $invoice->invoice_number }}
                                                </a>
                                            </td>
                                            <td>{{ $invoice->guest->full_name }}</td>
                                            <td>${{ number_format($invoice->total_amount, 2) }}</td>
                                            <td>${{ number_format($invoice->paid_amount, 2) }}</td>
                                            <td>${{ number_format($invoice->balance_amount, 2) }}</td>
                                            <td>
                                                <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : ($invoice->status === 'partially_paid' ? 'bg-warning' : 'bg-danger') }}">
                                                    {{ __('billing.' . $invoice->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $invoice->invoice_date->format('d-m-Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('checkout.invoice.show', $invoice->id) }}" class="btn btn-sm btn-info" title="{{ __('billing.view_invoice') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('checkout.invoice.print', $invoice->id) }}" class="btn btn-sm btn-secondary" title="{{ __('billing.print_invoice') }}" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                    @if($invoice->balance_amount > 0)
                                                        <a href="{{ route('checkout.payment.process', $invoice->id) }}" class="btn btn-sm btn-success" title="{{ __('billing.process_payment') }}">
                                                            <i class="fas fa-credit-card"></i>
                                                        </a>
                                                    @endif
                                                    @if($invoice->paid_amount == 0)
                                                        <button type="button" class="btn btn-sm btn-danger" title="{{ __('billing.delete_invoice') }}"
                                                                onclick="confirmDelete({{ $invoice->id }}, '{{ $invoice->invoice_number }}')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center py-4">
                                                <div class="d-flex flex-column align-items-center">
                                                    <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                                    <h5 class="text-muted">{{ __('billing.no_invoices_found') }}</h5>
                                                    <p class="text-muted mb-0">{{ __('billing.no_invoices_description') }}</p>
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
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">{{ __('billing.delete_invoice') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('billing.delete_invoice_confirmation') }}</p>
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
        document.getElementById('deleteForm').action = '{{ url("billing/list") }}/' + invoiceId + '/delete';
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
    </script>
    @endpush
</x-app-layout>
