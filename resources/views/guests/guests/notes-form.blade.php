<x-app-layout>
    <x-form.layout :form="$guest">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0">
                                <i class="fas fa-sticky-note me-2"></i>{{ __('guests.update_notes') }}
                            </h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Guest Information -->
                                <div class="col-12">
                                    <div class="alert alert-info">
                                        <strong>{{ __('guests.guest') }}:</strong> {{ $guest->full_name }}
                                        @if($guest->email)
                                            <br><strong>{{ __('guests.email') }}:</strong> {{ $guest->email }}
                                        @endif
                                    </div>
                                </div>

                                <!-- Public Notes -->
                                <div class="col-md-12">
                                    <x-form.textarea
                                        :label="__('guests.notes')"
                                        name="notes"
                                        :value="old('notes', $guest->notes)"
                                        rows="4"
                                        placeholder="{{ __('guests.notes_placeholder') }}"
                                        help="{{ __('guests.notes_help') }}"
                                    />
                                </div>

                                <!-- Internal Notes -->
                                <div class="col-md-12">
                                    <x-form.textarea
                                        :label="__('guests.internal_notes')"
                                        name="internal_notes"
                                        :value="old('internal_notes', $guest->internal_notes)"
                                        rows="4"
                                        placeholder="{{ __('guests.internal_notes_placeholder') }}"
                                        help="{{ __('guests.internal_notes_help') }}"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('guests.list.show', $guest->id) }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-1"></i>{{ __('global.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-save me-2"></i>{{ __('form.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-form.layout>
</x-app-layout>
