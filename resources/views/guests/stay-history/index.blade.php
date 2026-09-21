<x-page-index 
    :title="__('guests.stay_history_for') . ' ' . $guest->full_name" 
    icon="user-check">

    <x-slot:actions>
        <a href="{{ route('guests.list.show', $guest->id) }}" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 rounded-2">
            <i data-lucide="arrow-left" style="width: 14px; height: 14px;"></i>
            <span>{{ __('global.back') }}</span>
        </a>
    </x-slot:actions>

    <div class="table-responsive">
        @if($guest->checkIns->count() > 0)
            <table class="table table-hover align-middle mb-0 datatables-custom">
                <thead class="table-light">
                    <tr class="text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px; color: #64748b;">
                        <th class="ps-3">{{ __('checkins.check_in_date') }}</th>
                        <th>{{ __('checkins.check_out_date') }}</th>
                        <th>{{ __('rooms.room') }}</th>
                        <th>{{ __('rooms.room_type') }}</th>
                        <th>{{ __('rooms.floor') }}</th>
                        <th class="text-end">{{ __('checkins.total_amount') }}</th>
                        <th class="text-end pe-3">{{ __('checkins.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($guest->checkIns as $checkIn)
                        <tr>
                            <td class="ps-3 fw-medium text-dark">{{ $checkIn->check_in_date ? $checkIn->check_in_date->format('d-m-Y') : '-' }}</td>
                            <td class="text-muted">{{ $checkIn->actual_check_out_at ? $checkIn->actual_check_out_at->format('d-m-Y') : ($checkIn->expected_check_out_date ? $checkIn->expected_check_out_date->format('d-m-Y') : '-') }}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-2 px-2 py-1 fw-bold">
                                    {{ $checkIn->room ? $checkIn->room->room_number : '-' }}
                                </span>
                            </td>
                            <td>{{ $checkIn->room && $checkIn->room->roomType ? $checkIn->room->roomType->localized_name : '-' }}</td>
                            <td>{{ $checkIn->room && $checkIn->room->floor ? $checkIn->room->floor->localized_name : '-' }}</td>
                            <td class="text-end fw-bold text-dark">${{ number_format($checkIn->paid_amount, 2) }}</td>
                            <td class="text-end pe-3">
                                @if($checkIn->status === 'checked_in')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">{{ __('checkins.checked_in') }}</span>
                                @elseif($checkIn->status === 'checked_out')
                                    <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">{{ __('checkins.checked_out') }}</span>
                                @elseif($checkIn->status === 'cancelled')
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-2 py-1">{{ __('checkins.cancelled') }}</span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">{{ __('checkins.pending') }}</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="d-flex flex-column align-items-center py-5 text-muted">
                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                    <i data-lucide="history" style="width: 28px; height: 28px; stroke: #94a3b8;"></i>
                </div>
                <h6 class="fw-semibold text-secondary mb-1">{{ __('global.no_data') }}</h6>
                <p class="small text-muted mb-0">{{ __('guests.no_stay_history') }}</p>
            </div>
        @endif
    </div>
</x-page-index>
