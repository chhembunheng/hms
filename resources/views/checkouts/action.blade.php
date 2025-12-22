<div class="d-inline-flex">
    @can('checkout.edit')
        <a href="{{ route('checkout.edit', $row->id) }}" class="btn btn-sm btn-outline-primary me-1">
            <i class="fas fa-edit"></i>
        </a>
    @endcan
    @can('checkout.delete')
        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteRecord('{{ route('checkout.delete', $row->id) }}')">
            <i class="fas fa-trash"></i>
        </button>
    @endcan
</div>
