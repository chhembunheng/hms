@can('rooms.type.edit')
    <a href="{{ route('rooms.type.edit', $row->id) }}" class="btn btn-warning btn-sm" title="{{ __('Edit') }}">
        <i class="fas fa-edit"></i>
    </a>
@endcan

@can('rooms.type.delete')
    <form action="{{ route('rooms.type.destroy', $row->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" title="{{ __('Delete') }}"
                onclick="return confirm('{{ __('Are you sure you want to delete this room type?') }}')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
@endcan
