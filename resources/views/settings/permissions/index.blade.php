<x-app-layout>
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Permissions</h1>
            <a href="{{ route('settings.permissions.add') }}" class="btn btn-primary">
                <i class="fas fa-circle-plus"></i> Add Permission
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <x-datatables :dataTable="$dataTable" />
            </div>
        </div>
    </div>
</x-app-layout>
