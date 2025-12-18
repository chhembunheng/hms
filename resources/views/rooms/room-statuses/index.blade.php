@extends('layouts.app')

@section('title', __('Room Statuses'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Room Statuses') }}</h3>
                    @can('settings.room-statuses.add')
                        <div class="card-tools">
                            <a href="{{ route('rooms.status.add') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('Add Room Status') }}
                            </a>
                        </div>
                    @endcan
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <x-datatable-filter
                        :filters="[
                            ['name' => 'name', 'label' => __('Name'), 'type' => 'text'],
                            ['name' => 'description', 'label' => __('Description'), 'type' => 'text'],
                            ['name' => 'color', 'label' => __('Color'), 'type' => 'text'],
                            ['name' => 'is_active', 'label' => __('Status'), 'type' => 'multi-select', 'options' => [['value' => 1, 'label' => __('Active')], ['value' => 0, 'label' => __('Inactive')]]],
                            ['name' => 'created_from', 'label' => __('Created From'), 'type' => 'date'],
                            ['name' => 'created_to', 'label' => __('Created To'), 'type' => 'date'],
                        ]"
                        route="{{ route('rooms.status.index') }}"
                    />

                    <!-- DataTable -->
                    <div class="table-responsive">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{ $dataTable->scripts() }}
@endpush
