<div class="btn-group" role="group">
    <a href="{{ route('frontends.' . $row->getTable() . '.edit', $row->id) }}" class="btn btn-sm btn-warning" title="{{ __('root.common.edit') }}">
        <i class="fa-solid fa-pen-to-square"></i>
    </a>
    <form action="{{ route('frontends.' . $row->getTable() . '.delete', $row->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ __('root.common.confirm_delete') }}');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" title="{{ __('root.common.delete') }}">
            <i class="fa-solid fa-trash"></i>
        </button>
    </form>
</div>
