<x-app-layout>
    <x-form.layout :form="$form">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Extend Stay - {{ $form->booking_number }}</h5>
                        </div>
                        <div class="card-body">
                            <!-- Current Booking Info -->
                            <div class="row g-4 mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>Current Booking Information</h6>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Guest Name</label>
                                        <input type="text" class="form-control" value="{{ $form->guest_name }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Room</label>
                                        <input type="text" class="form-control" value="{{ $form->room->room_number ?? '-' }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Current Check-in Date</label>
                                        <input type="text" class="form-control" value="{{ $form->check_in_date?->format('M d, Y') }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">Current Check-out Date</label>
                                        <input type="text" class="form-control" value="{{ $form->check_out_date?->format('M d, Y') }}" readonly>
                                    </div>
                                </div>
                            </div>

                            <!-- Extension Details -->
                            <div class="row g-4 mb-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3"><i class="fas fa-calendar-plus me-2"></i>Extension Details</h6>
                                </div>

                                <div class="col-md-6">
                                    <x-form.input label="New Check-out Date" name="check_out_date" type="date" :value="old('check_out_date', $form?->check_out_date?->format('Y-m-d'))" required />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input label="Additional Amount" name="total_amount" type="number" step="0.01" :value="old('total_amount', $form?->total_amount)" required />
                                </div>

                                <div class="col-md-6">
                                    <x-form.input label="Additional Paid Amount" name="paid_amount" type="number" step="0.01" :value="old('paid_amount', $form?->paid_amount)" />
                                </div>

                                <div class="col-12">
                                    <x-form.textarea label="Notes" name="notes" :value="old('notes', $form?->notes)" rows="3" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end">
                        <a href="{{ route('checkin.extend.index') }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-save"></i> Extend Stay
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-form.layout>
</x-app-layout>
