<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Room Types') }}</h3>
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
                        <x-datatables title="{{ __('Rooms Types') }}" :data="$dataTable"></x-datatables>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
