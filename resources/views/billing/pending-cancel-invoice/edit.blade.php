<x-app-layout>
    <x-form.layout :form="$form ?? null" :action="route('billing.pending-cancel-invoice.edit', $invoice->id)" method="PATCH">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-info text-white">
                            <h4 class="card-title mb-0">{{ __('billing.review_cancellation_request') }} - {{ $invoice->invoice_number }}</h4>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <x-form.select
                                        :label="__('billing.cancellation_decision')"
                                        name="action"
                                        :options="[
                                            'approve' => __('billing.approve_cancellation'),
                                            'reject' => __('billing.reject_cancellation')
                                        ]"
                                        required
                                    />
                                </div>
                                <div class="col-md-6">
                                    <x-form.textarea
                                        :label="__('billing.admin_notes')"
                                        name="notes"
                                        :value="old('notes', $invoice->notes)"
                                        rows="3"
                                        :placeholder="__('billing.admin_notes_placeholder')"
                                    />
                                </div>
                            </div>

                            <!-- Cancellation Request Details -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3">{{ __('billing.cancellation_request_details') }}</h5>
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <strong>{{ __('billing.requested_at') }}:</strong><br>
                                                    {{ $invoice->updated_at->format('d-m-Y H:i') }}
                                                </div>
                                                <div class="col-md-8">
                                                    <strong>{{ __('billing.cancellation_reason') }}:</strong><br>
                                                    {{ $invoice->notes ?: __('billing.no_reason_provided') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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
                                                    <strong>{{ __('billing.balance_amount') }}:</strong><br>
                                                    ${{ number_format($invoice->balance_amount, 2) }}
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

                            <!-- Payment History -->
                            @if($invoice->payments->count() > 0)
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h5 class="mb-3">{{ __('billing.payment_history') }}</h5>
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>{{ __('billing.payment_date') }}</th>
                                                        <th>{{ __('billing.payment_method') }}</th>
                                                        <th>{{ __('billing.payment_amount') }}</th>
                                                        <th>{{ __('billing.reference_number') }}</th>
                                                        <th>{{ __('billing.processed_by') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($invoice->payments as $payment)
                                                        <tr>
                                                            <td>{{ $payment->payment_date->format('d-m-Y H:i') }}</td>
                                                            <td>{{ $payment->payment_method_label }}</td>
                                                            <td>${{ number_format($payment->amount, 2) }}</td>
                                                            <td>{{ $payment->reference_number ?: '-' }}</td>
                                                            <td>{{ $payment->processor->name ?? __('global.unknown') }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
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
