@can('rooms.pricing.edit')
    <a href="{{ route('rooms.pricing.edit', $row->id) }}" class="btn btn-warning btn-sm" title="{{ __('Edit') }}">
        <i class="fas fa-edit"></i>
    </a>
@endcan

@can('rooms.pricing.delete')
    <form action="{{ route('rooms.pricing.destroy', $row->id) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger btn-sm" title="{{ __('Delete') }}"
                onclick="return confirm('{{ __('Are you sure you want to delete this room pricing?') }}')">
            <i class="fas fa-trash"></i>
        </button>
    </form>
@endcan
