@can('check-ins.show')
    <a href="{{ route('check-ins.show', $row->id) }}" class="btn btn-info btn-sm" title="{{ __('global.view') }}">
        <i class="fas fa-eye"></i>
    </a>
@endcan

@can('check-ins.edit')
    <a href="{{ route('check-ins.edit', $row->id) }}" class="btn btn-warning btn-sm" title="{{ __('global.edit') }}">
        <i class="fas fa-edit"></i>
    </a>
@endcan

@if($row->status === 'confirmed')
    @can('check-ins.check-in')
        <button type="button" class="btn btn-success btn-sm" onclick="checkIn({{ $row->id }})" title="{{ __('rooms.check_in') }}">
            <i class="fas fa-sign-in-alt"></i>
        </button>
    @endcan
@endif

@if($row->status === 'checked_in')
    @can('check-ins.check-out')
        <button type="button" class="btn btn-primary btn-sm" onclick="checkOut({{ $row->id }})" title="{{ __('rooms.check_out') }}">
            <i class="fas fa-sign-out-alt"></i>
        </button>
    @endcan
@endif

@can('check-ins.destroy')
    <button type="button" class="btn btn-danger btn-sm" onclick="deleteRecord({{ $row->id }}, '{{ $row->booking_number }}')" title="{{ __('global.delete') }}">
        <i class="fas fa-trash"></i>
    </button>
@endcan

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

function deleteRecord(id, name) {
    if (confirm('{{ __('global.confirm_delete') }}'.replace(':name', name))) {
        fetch(`{{ url('check-ins') }}/${id}`, {
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
                }, data.delay || 2000);
            } else {
                toastr.error(data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('An error occurred while deleting.');
        });
    }
}
</script>