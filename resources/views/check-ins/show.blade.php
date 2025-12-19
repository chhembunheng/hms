<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('rooms.booking_details') }} - {{ $checkIn->booking_number }}</h3>
                        <div class="card-tools">
                            @can('check-ins.edit')
                                <a href="{{ route('check-ins.edit', $checkIn->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> {{ __('global.edit') }}
                                </a>
                            @endcan
                            <a href="{{ route('check-ins.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-list"></i> {{ __('rooms.check_ins') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <!-- Guest Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">{{ __('rooms.guest_name') }}</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="150"><strong>{{ __('rooms.booking_number') }}:</strong></td>
                                        <td>{{ $checkIn->booking_number }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('rooms.guest_name') }}:</strong></td>
                                        <td>{{ $checkIn->guest_name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('rooms.guest_email') }}:</strong></td>
                                        <td>{{ $checkIn->guest_email ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('rooms.guest_phone') }}:</strong></td>
                                        <td>{{ $checkIn->guest_phone ?: '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('rooms.guest_type') }}:</strong></td>
                                        <td>{{ __('rooms.' . $checkIn->guest_type) }}</td>
                                    </tr>
                                    @if($checkIn->guest_type === 'national')
                                        <tr>
                                            <td><strong>{{ __('rooms.guest_national_id') }}:</strong></td>
                                            <td>{{ $checkIn->guest_national_id ?: '-' }}</td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td><strong>{{ __('rooms.guest_passport') }}:</strong></td>
                                            <td>{{ $checkIn->guest_passport ?: '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>{{ __('rooms.guest_country') }}:</strong></td>
                                            <td>{{ $checkIn->guest_country ?: '-' }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>{{ __('rooms.number_of_guests') }}:</strong></td>
                                        <td>{{ $checkIn->number_of_guests }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Booking Information -->
                            <div class="col-md-6">
                                <h5 class="mb-3">{{ __('rooms.booking_details') }}</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <td width="150"><strong>{{ __('rooms.room_number') }}:</strong></td>
                                        <td>{{ $checkIn->room->room_number ?? '-' }} ({{ $checkIn->room->floor ?? '-' }}F)</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('rooms.room_type') }}:</strong></td>
                                        <td>{{ $checkIn->room->roomType->name_en ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('rooms.check_in_date') }}:</strong></td>
                                        <td>{{ $checkIn->check_in_date?->format('M d, Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>{{ __('rooms.check_out_date') }}:</strong></td>
                                        <td>{{ $checkIn->check_out_date?->format('M d, Y') }}</td>
                                    </tr>
                                    @if($checkIn->actual_check_in_at)
                                        <tr>
                                            <td><strong>{{ __('rooms.actual_check_in_time') }}:</strong></td>
                                            <td>{{ $checkIn->actual_check_in_at?->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @endif
                                    @if($checkIn->actual_check_out_at)
                                        <tr>
                                            <td><strong>{{ __('rooms.actual_check_out_time') }}:</strong></td>
                                            <td>{{ $checkIn->actual_check_out_at?->format('M d, Y H:i') }}</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td><strong>{{ __('global.status') }}:</strong></td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'confirmed' => 'warning',
                                                    'checked_in' => 'success',
                                                    'checked_out' => 'info',
                                                    'cancelled' => 'danger'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $statusColors[$checkIn->status] ?? 'secondary' }}">
                                                {{ __('rooms.' . $checkIn->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5 class="mb-3">{{ __('rooms.payment_status') }}</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card {{ $checkIn->is_paid ? 'border-success' : 'border-warning' }}">
                                            <div class="card-body text-center">
                                                <h6>{{ __('rooms.total_amount') }}</h6>
                                                <h4 class="text-{{ $checkIn->is_paid ? 'success' : 'warning' }}">
                                                    ${{ number_format($checkIn->total_amount, 2) }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card border-info">
                                            <div class="card-body text-center">
                                                <h6>{{ __('rooms.paid_amount') }}</h6>
                                                <h4 class="text-info">
                                                    ${{ number_format($checkIn->paid_amount, 2) }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card {{ $checkIn->remaining_amount > 0 ? 'border-danger' : 'border-success' }}">
                                            <div class="card-body text-center">
                                                <h6>{{ __('rooms.remaining_amount') }}</h6>
                                                <h4 class="text-{{ $checkIn->remaining_amount > 0 ? 'danger' : 'success' }}">
                                                    ${{ number_format($checkIn->remaining_amount, 2) }}
                                                </h4>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($checkIn->notes)
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3">{{ __('global.notes') }}</h5>
                                    <div class="card">
                                        <div class="card-body">
                                            {{ $checkIn->notes }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="card-footer">
                        @if($checkIn->status === 'confirmed')
                            @can('check-ins.check-in')
                                <button type="button" class="btn btn-success me-2" onclick="checkIn({{ $checkIn->id }})">
                                    <i class="fas fa-sign-in-alt"></i> {{ __('rooms.check_in') }}
                                </button>
                            @endcan
                        @endif

                        @if($checkIn->status === 'checked_in')
                            @can('check-ins.check-out')
                                <button type="button" class="btn btn-primary me-2" onclick="checkOut({{ $checkIn->id }})">
                                    <i class="fas fa-sign-out-alt"></i> {{ __('rooms.check_out') }}
                                </button>
                            @endcan
                        @endif

                        @can('check-ins.edit')
                            <a href="{{ route('check-ins.edit', $checkIn->id) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit"></i> {{ __('global.edit') }}
                            </a>
                        @endcan

                        <a href="{{ route('check-ins.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('global.back') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkIn(id) {
            if (confirm('{{ __('global.confirm_action') }}')) {
                fetch(`{{ url('check-ins') }}/${id}/check-in`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        toastr.success(data.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, data.delay || 2000);
                    } else {
                        toastr.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('An error occurred during check-in.');
                });
            }
        }

        function checkOut(id) {
            if (confirm('{{ __('global.confirm_action') }}')) {
                fetch(`{{ url('check-ins') }}/${id}/check-out`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        toastr.success(data.message);
                        setTimeout(() => {
                            window.location.reload();
                        }, data.delay || 2000);
                    } else {
                        toastr.error(data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    toastr.error('An error occurred during check-out.');
                });
            }
        }
    </script>
</x-app-layout>