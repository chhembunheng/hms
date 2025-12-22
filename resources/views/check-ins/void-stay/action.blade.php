<div class="d-inline-flex dropdown ms-2">
    <a href="#" class="text-body" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">
        @can('checkin.void-stay.index')
            <a href="#" class="dropdown-item" onclick="viewDetails({{ $row->id }}, '{{ $row->booking_number }}')">
                <i class="fa-light fa-eye me-2"></i> {{ __('global.view') }}
            </a>
        @endcan
    </div>
</div>

<script>
function viewDetails(id, bookingNumber) {
    // For now, just show an alert with booking details
    // You can enhance this to show a modal with full details
    alert('Booking: ' + bookingNumber + '\nID: ' + id + '\n\nThis stay has been cancelled.');
}
</script>
