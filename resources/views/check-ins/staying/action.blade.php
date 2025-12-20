<div class="d-inline-flex dropdown ms-2">
    <a href="#" class="text-body" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-bars"></i>
    </a>
    <div class="dropdown-menu dropdown-menu-end">
        @can('checkin.staying.edit')
            <a href="{{ route('checkin.staying.edit', $row->id) }}" class="dropdown-item">
                <i class="fa-light fa-pen-to-square me-2"></i> Edit
            </a>
        @endcan

        @if($row->status === 'checked_in')
            @can('checkin.staying.check-out')
                <a href="#" class="dropdown-item" onclick="checkOut({{ $row->id }})">
                    <i class="fa-light fa-sign-out-alt me-2"></i> Check-Out
                </a>
            @endcan
        @endif

        @can('checkin.staying.delete')
            <a href="#" class="dropdown-item" onclick="deleteRecord({{ $row->id }}, '{{ $row->booking_number }}')">
                <i class="fa-light fa-trash me-2"></i> Delete
            </a>
        @endcan
    </div>
</div>

<script>
function checkOut(id) {
    if (confirm('Are you sure you want to check out this guest?')) {
        fetch(`{{ url('checkin/staying') }}/${id}/check-out`, {
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
                }, 2000);
            } else {
                toastr.error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred');
        });
    }
}

function deleteRecord(id, name) {
    if (confirm('Are you sure you want to delete ' + name + '?')) {
        fetch(`{{ url('checkin/staying') }}/${id}/delete`, {
            method: 'DELETE',
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
                }, 2000);
            } else {
                toastr.error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred');
        });
    }
}
</script>
