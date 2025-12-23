<x-app-layout>
    <div class="container-fluid py-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard.index') }}">{{ __('global.home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('checkout.checkout.index') }}">{{ __('checkout.checkout_list') }}</a></li>
                <li class="breadcrumb-item active">{{ __('checkout.invoice') }} - {{ $invoice->invoice_number }}</li>
            </ol>
        </nav>
<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- Invoice Header -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-file-invoice-dollar me-2"></i>{{ __('checkout.invoice', [], 'en') }} / {{ __('checkout.invoice', [], 'km') }} - {{ $invoice->invoice_number }}
                </h5>
                <div>
                    <a href="{{ route('checkout.invoice.print', $invoice->id) }}" target="_blank" class="btn btn-light btn-sm me-2">
                        <i class="fas fa-print me-1"></i>{{ __('global.print', [], 'en') }} / {{ __('global.print', [], 'km') }}
                    </a>
                    @if($invoice->balance_amount > 0)
                        <a href="{{ route('checkout.payment.process', $invoice->id) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-credit-card me-1"></i>{{ __('checkout.process_payment', [], 'en') }} / {{ __('checkout.process_payment', [], 'km') }}
                        </a>
                    @endif
                    <a href="{{ route('checkout.checkout.index') }}" class="btn btn-light btn-sm ms-2">
                        <i class="fas fa-arrow-left me-1"></i>{{ __('global.back', [], 'en') }} / {{ __('global.back', [], 'km') }}
                    </a>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-4">
                    <!-- Invoice Details -->
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>{{ __('checkout.invoice_details', [], 'en') }} / {{ __('checkout.invoice_details', [], 'km') }}</h6>
                        <div class="mb-2">
                            <label class="form-label fw-bold">{{ __('checkout.invoice_number', [], 'en') }} / {{ __('checkout.invoice_number', [], 'km') }}</label>
                            <p class="mb-0">{{ $invoice->invoice_number }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">{{ __('checkout.invoice_date', [], 'en') }} / {{ __('checkout.invoice_date', [], 'km') }}</label>
                            <p class="mb-0">{{ $invoice->invoice_date->format('d-m-Y') }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">{{ __('checkout.due_date', [], 'en') }} / {{ __('checkout.due_date', [], 'km') }}</label>
                            <p class="mb-0">{{ $invoice->due_date ? $invoice->due_date->format('d-m-Y') : '-' }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">{{ __('form.status', [], 'en') }} / {{ __('form.status', [], 'km') }}</label>
                            <p class="mb-0">{{ badge(__('checkout.' . $invoice->status)) }}</p>
                        </div>
                    </div>

                    <!-- Guest & Room Details -->
                    <div class="col-md-6">
                        <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>{{ __('checkout.guest_room_details', [], 'en') }} / {{ __('checkout.guest_room_details', [], 'km') }}</h6>
                        <div class="mb-2">
                            <label class="form-label fw-bold">{{ __('guests.guest_name', [], 'en') }} / {{ __('guests.guest_name', [], 'km') }}</label>
                            <p class="mb-0">{{ $invoice->guest->full_name }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">{{ __('rooms.room', [], 'en') }} / {{ __('rooms.room', [], 'km') }}</label>
                            <p class="mb-0">{{ $invoice->checkIn->room->room_number }} - {{ $invoice->checkIn->room->roomType->localized_name }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">{{ __('checkins.check_in_date', [], 'en') }} / {{ __('checkins.check_in_date', [], 'km') }}</label>
                            <p class="mb-0">{{ $invoice->checkIn->check_in_date->format('d-m-Y') }}</p>
                        </div>
                        <div class="mb-2">
                            <label class="form-label fw-bold">{{ __('checkins.check_out_date', [], 'en') }} / {{ __('checkins.check_out_date', [], 'km') }}</label>
                            <p class="mb-0">{{ $invoice->checkIn->actual_check_out_at->format('d-m-Y') }}</p>
                        </div>
                    </div>

                    <!-- Invoice Items -->
                    <div class="col-12 mt-4">
                        <h6 class="text-primary mb-3"><i class="fas fa-list me-2"></i>{{ __('checkout.invoice_items', [], 'en') }} / {{ __('checkout.invoice_items', [], 'km') }}</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('checkout.description', [], 'en') }} / {{ __('checkout.description', [], 'km') }}</th>
                                        <th class="text-center">{{ __('checkout.quantity', [], 'en') }} / {{ __('checkout.quantity', [], 'km') }}</th>
                                        <th class="text-end">{{ __('checkout.unit_price', [], 'en') }} / {{ __('checkout.unit_price', [], 'km') }}</th>
                                        <th class="text-end">{{ __('checkout.total', [], 'en') }} / {{ __('checkout.total', [], 'km') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($invoice->items)
                                        @foreach($invoice->items as $item)
                                            <tr>
                                                <td>{{ $item['description'] }}</td>
                                                <td class="text-center">{{ $item['quantity'] }}</td>
                                                <td class="text-end">${{ number_format($item['unit_price'], 2) }}</td>
                                                <td class="text-end">${{ number_format($item['total'], 2) }}</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="3" class="text-end">{{ __('checkout.subtotal', [], 'en') }} / {{ __('checkout.subtotal', [], 'km') }}</th>
                                        <th class="text-end">${{ number_format($invoice->subtotal, 2) }}</th>
                                    </tr>
                                    @if($invoice->tax_amount > 0)
                                        <tr>
                                            <th colspan="3" class="text-end">{{ __('checkout.tax', [], 'en') }} / {{ __('checkout.tax', [], 'km') }}</th>
                                            <th class="text-end">${{ number_format($invoice->tax_amount, 2) }}</th>
                                        </tr>
                                    @endif
                                    @if($invoice->discount_amount > 0)
                                        <tr>
                                            <th colspan="3" class="text-end">{{ __('checkout.discount', [], 'en') }} / {{ __('checkout.discount', [], 'km') }}</th>
                                            <th class="text-end">-${{ number_format($invoice->discount_amount, 2) }}</th>
                                        </tr>
                                    @endif
                                    <tr>
                                        <th colspan="3" class="text-end">{{ __('checkout.total_amount', [], 'en') }} / {{ __('checkout.total_amount', [], 'km') }}</th>
                                        <th class="text-end">${{ number_format($invoice->total_amount, 2) }}</th>
                                    </tr>
                                    <tr>
                                        <th colspan="3" class="text-end">{{ __('checkout.paid_amount', [], 'en') }} / {{ __('checkout.paid_amount', [], 'km') }}</th>
                                        <th class="text-end">${{ number_format($invoice->paid_amount, 2) }}</th>
                                    </tr>
                                    <tr class="table-warning">
                                        <th colspan="3" class="text-end">{{ __('checkout.balance_amount', [], 'en') }} / {{ __('checkout.balance_amount', [], 'km') }}</th>
                                        <th class="text-end">${{ number_format($invoice->balance_amount, 2) }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <!-- Payment History -->
                    @if($invoice->paid_amount > 0)
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3"><i class="fas fa-history me-2"></i>{{ __('checkout.payment_history', [], 'en') }} / {{ __('checkout.payment_history', [], 'km') }}</h6>
                            <div class="alert alert-info">
                                <strong>{{ __('checkout.payment_method', [], 'en') }} / {{ __('checkout.payment_method', [], 'km') }}:</strong> {{ $invoice->payment_method ?: __('global.unknown') }}<br>
                                <strong>{{ __('checkout.last_payment', [], 'en') }} / {{ __('checkout.last_payment', [], 'km') }}:</strong> {{ $invoice->updated_at->format('d-m-Y H:i') }}
                            </div>
                        </div>
                    @endif

                    <!-- Notes -->
                    @if($invoice->notes)
                        <div class="col-12 mt-4">
                            <h6 class="text-primary mb-3"><i class="fas fa-sticky-note me-2"></i>{{ __('checkout.notes', [], 'en') }} / {{ __('checkout.notes', [], 'km') }}</h6>
                            <div class="alert alert-light">
                                {{ $invoice->notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
