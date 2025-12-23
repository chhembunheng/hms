@extends('layouts.app')

@section('title', __('billing.edit_void_invoice') . ' - ' . $invoice->invoice_number)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">{{ __('billing.edit_void_invoice') }} - {{ $invoice->invoice_number }}</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('billing.void.edit', $invoice->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                            <div class="row g-4">
                                <div class="col-md-12">
                                    <x-form.textarea
                                        :label="__('billing.cancellation_reason')"
                                        name="notes"
                                        :value="old('notes', $invoice->notes)"
                                        rows="4"
                                        :placeholder="__('billing.cancellation_reason')"
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
                                                    <strong>{{ __('billing.status') }}:</strong><br>
                                                    <span class="badge bg-danger">{{ __('billing.cancelled') }}</span>
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
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('billing.void.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> {{ __('global.back') }}
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> {{ __('global.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
