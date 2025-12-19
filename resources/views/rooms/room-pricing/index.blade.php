<x-app-layout>
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('rooms.room_pricing') }}</h3>
                    @can('rooms.pricing.form')
                        <div class="card-tools">
                            <a href="{{ route('rooms.pricing.add') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('rooms.add_room_pricing') }}
                            </a>
                        </div>
                    @endcan
                </div>

                        <div class="card-body">
                            <!-- Filter Component -->
                            <x-datatable-filter>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('global.search') }}</label>
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('rooms.room_type') }}</label>
                                    <select name="room_type_id" class="form-select form-select-sm">
                                        <option value="">{{ __('rooms.all') }}</option>
                                        <!-- Add room type options -->
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('rooms.pricing_type') }}</label>
                                    <select name="pricing_type" class="form-select form-select-sm">
                                        <option value="">{{ __('rooms.all') }}</option>
                                        @foreach(\App\Models\RoomPricing::getPricingTypes() as $key => $value)
                                            <option value="{{ $key }}">{{ $value }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('rooms.currency') }}</label>
                                    <select name="currency" class="form-select form-select-sm">
                                        <option value="">{{ __('rooms.all') }}</option>
                                        @foreach(get_currencies() as $code => $name)
                                            <option value="{{ $code }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('rooms.active_status') }}</label>
                                    <select name="is_active[]" class="form-select form-select-sm multiple-select" multiple>
                                        <option value="1">{{ __('rooms.active') }}</option>
                                        <option value="0">{{ __('rooms.inactive') }}</option>
                                    </select>
                                </div>
                            </x-datatable-filter>

                            <!-- DataTable -->
                            <div class="table-responsive">
                                <x-datatables title="{{ __('rooms.room_pricing') }}" :data="$dataTable"> </x-datatables>
                            </div>
                        </div>
            </div>
        </div>
    </div>
</x-app-layout>
