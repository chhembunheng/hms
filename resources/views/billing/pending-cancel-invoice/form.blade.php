@extends('layouts.app')

@section('title', __('billing.review_cancellation_request') . ' - ' . $invoice->invoice_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('billing.review_cancellation_request') }} - {{ $invoice->invoice_number }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('billing.pending-cancel-invoice.edit', $invoice->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <x-form.select
                                        :label="__('billing.cancellation_action')"
                                        name="action"
                                        :value="old('action')"
                                        :options="[
                                            'approve' => __('billing.approve_cancellation'),
                                            'reject' => __('billing.reject_cancellation')
                                        ]"
                                        :selected="old('action')"
                                        required
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-form.textarea
                                        :label="__('billing.admin_notes')"
                                        name="notes"
                                        :value="old('notes')"
                                        rows="3"
                                        :placeholder="__('billing.admin_notes_placeholder')"
                                    />
                                </div>
                            </div>

                            <!-- Invoice Summary (Read-only) -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3">{{ __('billing.invoice_summary') }}</h5>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>{{ __('billing.guest') }}:</strong><br>
                                                    {{ $invoice->guest->full_name }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>{{ __('billing.invoice_date') }}:</strong><br>
                                                    {{ $invoice->invoice_date->format('d-m-Y') }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>{{ __('billing.total_amount') }}:</strong><br>
                                                    ${{ number_format($invoice->total_amount, 2) }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>{{ __('billing.current_status') }}:</strong><br>
                                                    <span class="badge bg-warning">{{ __('billing.pending_cancellation') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Cancellation Request Details -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3">{{ __('billing.cancellation_request_details') }}</h5>
                                    <div class="card border-warning">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>{{ __('billing.requested_at') }}:</strong><br>
                                                    {{ $invoice->updated_at->format('d-m-Y H:i') }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>{{ __('billing.cancellation_reason') }}:</strong><br>
                                                    {{ $invoice->notes ?: __('billing.no_reason_provided') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Invoice Items (Read-only) -->
                            @if($invoice->items)
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="mb-3">{{ __('billing.items') }}</h5>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('billing.description') }}</th>
                                                        <th class="text-center">{{ __('billing.quantity') }}</th>
                                                        <th class="text-end">{{ __('billing.unit_price') }}</th>
                                                        <th class="text-end">{{ __('billing.total') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($invoice->items as $item)
                                                        <tr>
                                                            <td>{{ $item['description'] }}</td>
                                                            <td class="text-center">{{ $item['quantity'] }}</td>
                                                            <td class="text-end">${{ number_format($item['unit_price'], 2) }}</td>
                                                            <td class="text-end">${{ number_format($item['total'], 2) }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr>
                                                        <th colspan="3" class="text-end">{{ __('billing.subtotal') }}</th>
                                                        <th class="text-end">${{ number_format($invoice->subtotal, 2) }}</th>
                                                    </tr>
                                                    @if($invoice->tax_amount > 0)
                                                        <tr>
                                                            <th colspan="3" class="text-end">{{ __('billing.tax') }}</th>
                                                            <th class="text-end">${{ number_format($invoice->tax_amount, 2) }}</th>
                                                        </tr>
                                                    @endif
                                                    @if($invoice->discount_amount > 0)
                                                        <tr>
                                                            <th colspan="3" class="text-end">{{ __('billing.discount') }}</th>
                                                            <th class="text-end">-${{ number_format($invoice->discount_amount, 2) }}</th>
                                                        </tr>
                                                    @endif
                                                    <tr class="table-active">
                                                        <th colspan="3" class="text-end">{{ __('billing.total_amount') }}</th>
                                                        <th class="text-end">${{ number_format($invoice->total_amount, 2) }}</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="text-end">
                        <x-form.button-back :href="route('billing.pending-cancel-invoice.index')" />
                        <x-form.button-submit />
                    </div>
                </div>
            </div>
        </div>
    </x-form.layout>
</x-app-layout>
