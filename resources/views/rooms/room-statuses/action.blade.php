@can('rooms.status.edit')
    <a href="{{ route('rooms.status.edit', $row->id) }}" class="btn btn-warning btn-sm" title="{{ __('Edit') }}">
        <i class="fas fa-edit"></i>
    </a>
@endcan

@can('rooms.status.delete')
    <form action="{{ route('rooms.status.destroy', $row->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" title="{{ __('Delete') }}"
                onclick="return confirm('{{ __('Are you sure you want to delete this room status?') }}')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
@endcan
