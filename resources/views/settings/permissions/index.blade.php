<x-app-layout>
    <div class="content">
        <!-- Info Banner for Permissions Page -->
        <div class="alert alert-info border-info shadow-sm mb-3" role="alert">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-shield-halved fa-2x me-3 text-info"></i>
                <div>
                    <h6 class="mb-1 fw-bold">{{ __('Permissions Management') }}</h6>
                    <small class="text-muted">{{ __('Define and manage access control permissions for roles and users') }}</small>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-success shadow-sm" role="alert">
                <i class="fa-solid fa-circle-check me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-danger shadow-sm" role="alert">
                <i class="fa-solid fa-circle-exclamation me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- DataTable with Permission-specific styling -->
        <x-datatables title="{{ __('global.list') }}" :data="$dataTable">
        </x-datatables>
    </div>
</x-app-layout>

@push('scripts')
    {!! $dataTable->scripts() !!}
@endpush
