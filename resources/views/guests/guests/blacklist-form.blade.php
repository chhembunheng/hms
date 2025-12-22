<x-app-layout>
    <x-form.layout :form="$guest">
        <div class="container-fluid py-3">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">
                                <i class="fas fa-ban me-2"></i>
                                {{ $guest->is_blacklisted ? __('guests.edit_blacklist') : __('guests.add_to_blacklist') }}
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
                                        @if($guest->phone)
                                            <br><strong>{{ __('guests.phone') }}:</strong> {{ $guest->phone }}
                                        @endif
                                    </div>
                                </div>

                                <!-- Blacklist Status -->
                                <div class="col-md-12">
                                    <x-form.checkbox
                                        :label="__('guests.is_blacklisted')"
                                        name="is_blacklisted"
                                        :checked="old('is_blacklisted', $guest->is_blacklisted)"
                                        id="is_blacklisted"
                                    />
                                </div>

                                <!-- Blacklist Reason -->
                                <div class="col-md-12" id="blacklist_reason_container" style="{{ $guest->is_blacklisted ? '' : 'display: none;' }}">
                                    <x-form.textarea
                                        :label="__('guests.blacklist_reason')"
                                        name="blacklist_reason"
                                        :value="old('blacklist_reason', $guest->blacklist_reason)"
                                        rows="3"
                                        placeholder="{{ __('guests.blacklist_reason_placeholder') }}"
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
                                    />
                                </div>

                                @if($guest->is_blacklisted && $guest->blacklisted_at)
                                <div class="col-md-12">
                                    <div class="alert alert-warning">
                                        <strong>{{ __('guests.blacklisted_since') }}:</strong>
                                        {{ $guest->blacklisted_at->format(config('init.datetime.display_format')) }}
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-end">
                        <a href="{{ route('guests.list.show', $guest->id) }}" class="btn btn-secondary me-2">
                            <i class="fas fa-times me-1"></i>{{ __('global.cancel') }}
                        </a>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save me-2"></i>{{ __('form.save') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </x-form.layout>

    <script>
        document.getElementById('is_blacklisted').addEventListener('change', function() {
            const container = document.getElementById('blacklist_reason_container');
            if (this.checked) {
                container.style.display = 'block';
            } else {
                container.style.display = 'none';
            }
        });
    </script>
</x-app-layout>
