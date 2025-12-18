@extends('layouts.app')

@section('title', __('Room Pricing'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Room Pricing') }}</h3>
                    @can('rooms.pricing.form')
                        <div class="card-tools">
                            <a href="{{ route('rooms.pricing.add') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('Add Room Pricing') }}
                            </a>
                        </div>
                    @endcan
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <x-datatable-filter
                        :filters="[
                            ['name' => 'room_type_id', 'label' => __('Room Type'), 'type' => 'select', 'options' => \App\Models\RoomType::where('is_active', true)->get()->map(fn($type) => ['value' => $type->id, 'label' => $type->name])->toArray()],
                            ['name' => 'price_min', 'label' => __('Min Price'), 'type' => 'number', 'step' => '0.01'],
                            ['name' => 'price_max', 'label' => __('Max Price'), 'type' => 'number', 'step' => '0.01'],
                            ['name' => 'currency', 'label' => __('Currency'), 'type' => 'select', 'options' => [['value' => 'USD', 'label' => 'USD'], ['value' => 'EUR', 'label' => 'EUR'], ['value' => 'KHR', 'label' => 'KHR']]],
                            ['name' => 'effective_from', 'label' => __('Effective From'), 'type' => 'date'],
                            ['name' => 'effective_to', 'label' => __('Effective To'), 'type' => 'date'],
                            ['name' => 'is_active', 'label' => __('Status'), 'type' => 'multi-select', 'options' => [['value' => 1, 'label' => __('Active')], ['value' => 0, 'label' => __('Inactive')]]],
                            ['name' => 'created_from', 'label' => __('Created From'), 'type' => 'date'],
                            ['name' => 'created_to', 'label' => __('Created To'), 'type' => 'date'],
                        ]"
                        route="{{ route('rooms.pricing.index') }}"
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
