@extends('layouts.app')

@section('title', __('Room Types'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Room Types') }}</h3>
                    @can('rooms.type.form')
                        <div class="card-tools">
                            <a href="{{ route('rooms.type.add') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('Add Room Type') }}
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
                            ['name' => 'is_active', 'label' => __('Status'), 'type' => 'multi-select', 'options' => [['value' => 1, 'label' => __('Active')], ['value' => 0, 'label' => __('Inactive')]]],
                            ['name' => 'created_from', 'label' => __('Created From'), 'type' => 'date'],
                            ['name' => 'created_to', 'label' => __('Created To'), 'type' => 'date'],
                        ]"
                        route="{{ route('rooms.type.index') }}"
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
