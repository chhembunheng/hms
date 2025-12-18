<x-app-layout>
    <div class="container-fluid py-3">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">{{ $form->id ? 'Edit Exchange Rate' : 'Add Exchange Rate' }}</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ $form->id ? route('settings.exchange-rate.edit', $form->id) : route('settings.exchange-rate.add') }}">
                            @csrf
                            @if($form->id)
                                @method('PUT')
                            @endif

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="from_currency" class="form-label">From Currency</label>
                                    <input type="text" class="form-control" id="from_currency" name="from_currency" value="{{ old('from_currency', $form->from_currency ?? 'USD') }}" required maxlength="3">
                                </div>
                                <div class="col-md-6">
                                    <label for="to_currency" class="form-label">To Currency</label>
                                    <input type="text" class="form-control" id="to_currency" name="to_currency" value="{{ old('to_currency', $form->to_currency ?? 'KHR') }}" required maxlength="3">
                                </div>
                                <div class="col-md-6">
                                    <label for="rate" class="form-label">Exchange Rate</label>
                                    <input type="number" step="0.01" class="form-control" id="rate" name="rate" value="{{ old('rate', $form->rate) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="effective_date" class="form-label">Effective Date</label>
                                    <input type="date" class="form-control" id="effective_date" name="effective_date" value="{{ old('effective_date', $form->effective_date?->format('Y-m-d') ?? now()->format('Y-m-d')) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $form->is_active ?? true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="{{ route('settings.exchange-rate.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
