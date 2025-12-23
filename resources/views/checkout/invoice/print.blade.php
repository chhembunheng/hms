@php
    $systemConfig = \App\Models\Settings\SystemConfiguration::first();
@endphp
@extends('layouts.print')

@section('title', __('checkout.invoice') . ' - ' . $invoice->invoice_number)

@section('content')
<div class="invoice-container">
    <!-- Invoice Header -->
    <div class="invoice-header text-center mb-4">
        <h2 class="mb-2">{{ __('checkout.invoice') }}</h2>
        <h4 class="text-primary">{{ $invoice->invoice_number }}</h4>
        <p class="mb-0">{{ __('checkout.invoice_date') }}: {{ $invoice->invoice_date->format('d-m-Y') }}</p>
        @if($invoice->due_date)
            <p class="mb-0">{{ __('checkout.due_date') }}: {{ $invoice->due_date->format('d-m-Y') }}</p>
        @endif
    </div>

    <!-- Guest & Hotel Information -->
    <div class="row mb-4">
        <div class="col-6">
            <h6>{{ __('checkout.bill_to') }}</h6>
            <strong>{{ $invoice->guest->full_name }}</strong><br>
            {{ $invoice->guest->email }}<br>
            {{ $invoice->guest->phone }}<br>
            {{ $invoice->guest->address }}
        </div>
        <div class="col-6 text-end">
            <h6>{{ __('checkout.hotel_information') }}</h6>
            <strong>{{ $systemConfig->localized_hotel_name ?? 'HMS Hotel' }}</strong><br>
            {{ $systemConfig->localized_location ?? '123 Hotel Street, City, Country' }}<br>
            @if($systemConfig->phone_number)
                {{ __('global.phone') }}: {{ $systemConfig->phone_number }}<br>
            @endif
            @if($systemConfig->email)
                {{ __('global.email') }}: {{ $systemConfig->email }}
            @endif
        </div>
    </div>

    <!-- Stay Details -->
    <div class="mb-4">
        <h6>{{ __('checkout.stay_details') }}</h6>
        <table class="table table-sm">
            <tr>
                <td><strong>{{ __('rooms.room') }}:</strong></td>
                <td>{{ $invoice->checkIn->room->room_number }} - {{ $invoice->checkIn->room->roomType->localized_name }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('checkins.check_in_date') }}:</strong></td>
                <td>{{ $invoice->checkIn->check_in_date->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <td><strong>{{ __('checkins.check_out_date') }}:</strong></td>
                <td>{{ $invoice->checkIn->actual_check_out_at->format('d-m-Y') }}</td>
            </tr>
        </table>
    </div>

    <!-- Invoice Items -->
    <div class="mb-4">
        <h6>{{ __('checkout.invoice_items') }}</h6>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{ __('checkout.description') }}</th>
                    <th class="text-center">{{ __('checkout.quantity') }}</th>
                    <th class="text-end">{{ __('checkout.unit_price') }}</th>
                    <th class="text-end">{{ __('checkout.total') }}</th>
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
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end">{{ __('checkout.subtotal') }}</th>
                    <th class="text-end">${{ number_format($invoice->subtotal, 2) }}</th>
                </tr>
                @if($invoice->tax_amount > 0)
                    <tr>
                        <th colspan="3" class="text-end">{{ __('checkout.tax') }}</th>
                        <th class="text-end">${{ number_format($invoice->tax_amount, 2) }}</th>
                    </tr>
                @endif
                @if($invoice->discount_amount > 0)
                    <tr>
                        <th colspan="3" class="text-end">{{ __('checkout.discount') }}</th>
                        <th class="text-end">-${{ number_format($invoice->discount_amount, 2) }}</th>
                    </tr>
                @endif
                <tr class="table-active">
                    <th colspan="3" class="text-end">{{ __('checkout.total_amount') }}</th>
                    <th class="text-end">${{ number_format($invoice->total_amount, 2) }}</th>
                </tr>
                <tr>
                    <th colspan="3" class="text-end">{{ __('checkout.paid_amount') }}</th>
                    <th class="text-end">${{ number_format($invoice->paid_amount, 2) }}</th>
                </tr>
                <tr class="table-warning">
                    <th colspan="3" class="text-end">{{ __('checkout.balance_amount') }}</th>
                    <th class="text-end">${{ number_format($invoice->balance_amount, 2) }}</th>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Payment Information -->
    @if($invoice->paid_amount > 0)
        <div class="mb-4">
            <h6>{{ __('checkout.payment_information') }}</h6>
            <p><strong>{{ __('checkout.payment_method') }}:</strong> {{ $invoice->payment_method ?: __('global.unknown') }}</p>
            <p><strong>{{ __('checkout.payment_date') }}:</strong> {{ $invoice->updated_at->format('d-m-Y H:i') }}</p>
        </div>
    @endif

    <!-- Status -->
    <div class="mb-4">
        <h6>{{ __('checkout.invoice_status') }}</h6>
        <span class="badge {{ $invoice->status === 'paid' ? 'bg-success' : ($invoice->status === 'partially_paid' ? 'bg-warning' : 'bg-danger') }}">
            {{ __('checkout.' . $invoice->status) }}
        </span>
    </div>

    <!-- Notes -->
    @if($invoice->notes)
        <div class="mb-4">
            <h6>{{ __('checkout.notes') }}</h6>
            <p>{{ $invoice->notes }}</p>
        </div>
    @endif

    <!-- Footer -->
    <div class="text-center mt-5">
        <p class="mb-0">{{ __('checkout.thank_you_for_staying') }}</p>
        <small class="text-muted">{{ __('checkout.generated_on') }} {{ now()->format('d-m-Y H:i') }}</small>
    </div>
</div>

<style>
.invoice-container {
    max-width: 800px;
    margin: 0 auto;
    padding: 20px;
    font-size: 14px;
}

.invoice-header {
    border-bottom: 2px solid #007bff;
    padding-bottom: 20px;
}

.table th, .table td {
    padding: 8px;
    vertical-align: top;
}

.table-active {
    background-color: #f8f9fa !important;
}

@media print {
    .invoice-container {
        padding: 0;
    }

    body {
        font-size: 12px;
    }
}
</style>
@endsection
