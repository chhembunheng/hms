<x-app-layout>
    <x-form.layout :form="$checkout">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <x-form.input :label="__('checkins.booking_number')" name="booking_number" :value="old('booking_number', $checkout?->booking_number)" readonly />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('checkins.guest_name')" name="guest_name" :value="old('guest_name', $checkout?->guest_name)" readonly />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('checkins.total_amount')" name="total_amount" :value="old('total_amount', $checkout?->total_amount)" readonly />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('checkins.paid_amount')" name="paid_amount" :value="old('paid_amount', $checkout?->paid_amount)" required type="number" step="0.01" />
                                </div>
                                <div class="col-md-6">
                                    <x-form.input :label="__('checkins.actual_check_out_at')" name="actual_check_out_at" :value="old('actual_check_out_at', $checkout?->actual_check_out_at?->format('Y-m-d\TH:i'))" type="datetime-local" />
                                </div>
                                <div class="col-md-12">
                                    <x-form.textarea :label="__('form.notes')" name="notes" :value="old('notes', $checkout?->notes)" rows="3" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary px-4 py-2">{{ __('form.save') }}</button>
                    </div>
                </div>
            </div>
        </div>
    </x-form.layout>
</x-app-layout>
