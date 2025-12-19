<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('rooms.room_layout') }}</h3>
                        <div class="card-tools">
                            <a href="{{ route('rooms.list.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-list"></i> {{ __('rooms.room_list') }}
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <p class="text-muted">{{ __('rooms.select_room_to_check_in') }}</p>

                        @if($rooms->isEmpty())
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> {{ __('rooms.no_rooms_available') }}
                            </div>
                        @else
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" id="floorTabs" role="tablist">
                                @foreach($rooms as $floor => $floorRooms)
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                                id="floor-{{ $floor }}-tab"
                                                data-bs-toggle="tab"
                                                data-bs-target="#floor-{{ $floor }}"
                                                type="button"
                                                role="tab"
                                                aria-controls="floor-{{ $floor }}"
                                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                            {{ __('rooms.floor') }} {{ $floor }}
                                            <span class="badge bg-primary ms-1">{{ $floorRooms->count() }}</span>
                                        </button>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Tab content -->
                            <div class="tab-content mt-4" id="floorTabsContent">
                                @foreach($rooms as $floor => $floorRooms)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                         id="floor-{{ $floor }}"
                                         role="tabpanel"
                                         aria-labelledby="floor-{{ $floor }}-tab">

                                        <div class="row">
                                            @foreach($floorRooms as $room)
                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="card room-card {{ $room->status->name_en == 'Available' ? 'border-success' : ($room->status->name_en == 'Occupied' ? 'border-danger' : 'border-warning') }}"
                                                         style="cursor: pointer;"
                                                         onclick="selectRoom({{ $room->id }}, '{{ $room->room_number }}')">
                                                        <div class="card-body text-center">
                                                            <div class="room-icon mb-2">
                                                                <i class="fas fa-bed fa-2x {{ $room->status->name_en == 'Available' ? 'text-success' : ($room->status->name_en == 'Occupied' ? 'text-danger' : 'text-warning') }}"></i>
                                                            </div>
                                                            <h5 class="card-title mb-1">{{ $room->room_number }}</h5>
                                                            <p class="card-text small text-muted mb-2">
                                                                {{ $room->roomType->name_en ?? 'N/A' }}
                                                            </p>
                                                            <span class="badge
                                                                {{ $room->status->name_en == 'Available' ? 'bg-success' :
                                                                   ($room->status->name_en == 'Occupied' ? 'bg-danger' : 'bg-warning') }}">
                                                                {{ __('rooms.' . strtolower($room->status->name_en)) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Check-in Modal -->
    <div class="modal fade" id="checkInModal" tabindex="-1" aria-labelledby="checkInModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkInModalLabel">{{ __('rooms.check_in') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('rooms.room_selected') }}: <strong id="selectedRoomNumber"></strong></p>
                    <p>{{ __('global.confirm_action') }}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('rooms.cancel') }}</button>
                    <button type="button" class="btn btn-primary" id="confirmCheckIn">{{ __('rooms.check_in') }}</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedRoomId = null;
        let selectedRoomNumber = null;

        function selectRoom(roomId, roomNumber) {
            selectedRoomId = roomId;
            selectedRoomNumber = roomNumber;
            document.getElementById('selectedRoomNumber').textContent = roomNumber;

            const modal = new bootstrap.Modal(document.getElementById('checkInModal'));
            modal.show();
        }

        document.getElementById('confirmCheckIn').addEventListener('click', function() {
            if (selectedRoomId) {
                // Send AJAX request to check-in
                fetch(`{{ url('rooms') }}/${selectedRoomId}/check-in`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('checkInModal'));
                        modal.hide();

                        // Show success message
                        toastr.success(data.message);

                        // Redirect after delay
                        setTimeout(() => {
                            window.location.href = data.redirect;
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
        });
    </script>

    <style>
        .room-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .room-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .room-icon {
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
    </style>
</x-app-layout>
