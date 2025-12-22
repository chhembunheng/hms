<x-app-layout>
    <div class="container-fluid py-3">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Guest Header -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="fas fa-user me-2"></i>{{ $guest->full_name }}
                            @if($guest->is_blacklisted)
                                <span class="badge bg-danger ms-2">{{ __('guests.blacklisted') }}</span>
                            @endif
                        </h5>
                        <div>
                            <a href="{{ route('guests.stay-history.index', $guest->id) }}" class="btn btn-info btn-sm me-2">
                                <i class="fas fa-history me-1"></i>{{ __('guests.stay_history') }}
                            </a>
                            <a href="{{ route('guests.list.edit', $guest->id) }}" class="btn btn-light btn-sm me-2">
                                <i class="fas fa-edit me-1"></i>{{ __('global.edit') }}
                            </a>
                            <div class="dropdown d-inline">
                                <button class="btn btn-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-cogs me-1"></i>{{ __('global.actions') }}
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('guests.list.blacklist', $guest->id) }}">
                                        <i class="fas fa-ban me-2"></i>{{ $guest->is_blacklisted ? __('guests.edit_blacklist') : __('guests.add_to_blacklist') }}
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('guests.list.notes', $guest->id) }}">
                                        <i class="fas fa-sticky-note me-2"></i>{{ __('guests.update_notes') }}
                                    </a></li>
                                </ul>
                            </div>
                            <a href="{{ route('guests.list.index') }}" class="btn btn-light btn-sm ms-2">
                                <i class="fas fa-arrow-left me-1"></i>{{ __('global.back') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4">
                            <!-- Personal Information -->
                            <div class="col-12">
                                <h6 class="text-primary mb-3"><i class="fas fa-user me-2"></i>{{ __('guests.personal_information') }}</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.first_name') }}</label>
                                    <p class="mb-0">{{ $guest->first_name ?: '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.last_name') }}</label>
                                    <p class="mb-0">{{ $guest->last_name ?: '-' }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.email') }}</label>
                                    <p class="mb-0">{{ $guest->email ?: '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.phone') }}</label>
                                    <p class="mb-0">{{ $guest->phone ?: '-' }}</p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.gender') }}</label>
                                    <p class="mb-0">{{ $guest->gender ? __('guests.' . $guest->gender) : '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.date_of_birth') }}</label>
                                    <p class="mb-0">{{ $guest->date_of_birth ? $guest->date_of_birth->format('d-m-Y') : '-' }}</p>
                                </div>
                            </div>

                            <!-- Identification -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-id-card me-2"></i>{{ __('guests.identification') }}</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.national_id') }}</label>
                                    <p class="mb-0">{{ $guest->national_id ?: '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.passport') }}</label>
                                    <p class="mb-0">{{ $guest->passport ?: '-' }}</p>
                                </div>
                            </div>

                            <!-- Guest Details -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-info-circle me-2"></i>{{ __('guests.guest_details') }}</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.guest_type') }}</label>
                                    <p class="mb-0">{{ $guest->guest_type ? badge($guest->guest_type) : '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.country') }}</label>
                                    <p class="mb-0">{{ $guest->country ?: '-' }}</p>
                                </div>
                            </div>

                            <!-- Address Information -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-map-marker-alt me-2"></i>{{ __('guests.address_information') }}</h6>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.address') }}</label>
                                    <p class="mb-0">{{ $guest->address ?: '-' }}</p>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.city') }}</label>
                                    <p class="mb-0">{{ $guest->city ?: '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.state') }}</label>
                                    <p class="mb-0">{{ $guest->state ?: '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.postal_code') }}</label>
                                    <p class="mb-0">{{ $guest->postal_code ?: '-' }}</p>
                                </div>
                            </div>

                            <!-- Emergency Contact -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-phone me-2"></i>{{ __('guests.emergency_contact') }}</h6>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.emergency_contact_name') }}</label>
                                    <p class="mb-0">{{ $guest->emergency_contact_name ?: '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.emergency_contact_phone') }}</label>
                                    <p class="mb-0">{{ $guest->emergency_contact_phone ?: '-' }}</p>
                                </div>
                            </div>

                            <!-- Additional Notes -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-sticky-note me-2"></i>{{ __('guests.additional_notes') }}</h6>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.notes') }}</label>
                                    <p class="mb-0">{{ $guest->notes ?: '-' }}</p>
                                </div>
                            </div>

                            @if($guest->internal_notes)
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.internal_notes') }}</label>
                                    <p class="mb-0 text-muted">{{ $guest->internal_notes }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Blacklist Information -->
                            @if($guest->is_blacklisted)
                            <div class="col-12 mt-4">
                                <h6 class="text-danger mb-3"><i class="fas fa-ban me-2"></i>{{ __('guests.blacklist_information') }}</h6>
                            </div>

                            <div class="col-md-12">
                                <div class="alert alert-danger">
                                    <h6><i class="fas fa-exclamation-triangle me-2"></i>{{ __('guests.blacklisted_guest') }}</h6>
                                    @if($guest->blacklist_reason)
                                        <strong>{{ __('guests.reason') }}:</strong> {{ $guest->blacklist_reason }}
                                        <br>
                                    @endif
                                    <strong>{{ __('guests.blacklisted_since') }}:</strong> {{ $guest->blacklisted_at->format('d-m-Y H:i') }}
                                </div>
                            </div>
                            @endif

                            <!-- Statistics -->
                            <div class="col-12 mt-4">
                                <h6 class="text-primary mb-3"><i class="fas fa-chart-bar me-2"></i>{{ __('guests.statistics') }}</h6>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.total_visits') }}</label>
                                    <p class="mb-0">{{ $guest->total_visits ?? 0 }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.last_visit') }}</label>
                                    <p class="mb-0">{{ $guest->last_visit_at ? $guest->last_visit_at->format(config('init.datetime.display_format')) : '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label fw-bold">{{ __('guests.age') }}</label>
                                    <p class="mb-0">{{ $guest->age ?: '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Check-in History -->
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="fas fa-history me-2"></i>{{ __('guests.checkin_history') }}</h6>
                    </div>
                    <div class="card-body">
                        @if($guest->checkIns->count() > 0)
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('rooms.room_number') }}</th>
                                            <th>{{ __('rooms.room_type') }}</th>
                                            <th>{{ __('checkins.check_in_date') }}</th>
                                            <th>{{ __('checkins.check_out_date') }}</th>
                                            <th>{{ __('form.status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($guest->checkIns as $checkIn)
                                            <tr>
                                                <td>{{ $checkIn->room->room_number ?? '-' }}</td>
                                                <td>{{ $checkIn->room->roomType->localized_name ?? '-' }}</td>
                                                <td>{{ $checkIn->check_in_at ? $checkIn->check_in_at->format('d-m-Y H:i') : '-' }}</td>
                                                <td>{{ $checkIn->check_out_at ? $checkIn->check_out_at->format('d-m-Y H:i') : '-' }}</td>
                                                <td>{{ badge($checkIn->status) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-muted mb-0">{{ __('guests.no_checkin_history') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
