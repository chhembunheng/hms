<div class="btn-group btn-group-sm" role="group">
    <a href="{{ route('settings.permissions.edit', $a->id) }}" class="btn btn-outline-primary" title="Edit">
        <i class="fas fa-pen-to-square"></i>
    </a>
    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" 
            onclick="deletePermission({{ $a->id }})" title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</div>

<script>
function deletePermission(id) {
    if (!confirm('Are you sure you want to delete this permission?')) {
        return;
    }
    
    fetch(`/settings/system/permissions/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error deleting permission');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error deleting permission');
    });
}
</script>
