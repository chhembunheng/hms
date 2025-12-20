<x-app-layout>
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('rooms.room_list') }}</h3>
                    @can('rooms.list.form')
                        <div class="card-tools">
                            <a href="{{ route('rooms.check-in') }}" class="btn btn-success btn-sm me-2">
                                <i class="fas fa-sign-in-alt"></i> {{ __('rooms.check_in') }}
                            </a>
                            <a href="{{ route('rooms.list.add') }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-plus"></i> {{ __('rooms.add_room') }}
                            </a>
                        </div>
                    @endcan
                </div>

                        <div class="card-body">
                            <x-datatable-filter>
                                <div class="col-md-3">
                                    <label class="form-label">{{ __('global.search') }}</label>
                                    <input type="text" name="search" class="form-control form-control-sm" placeholder="{{ __('global.search') }}">
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('rooms.room_type') }}</label>
                                    <select name="room_type_id[]" class="form-select form-select-sm multiple-select" multiple>
                                        @foreach($roomTypes as $roomType)
                                            <option value="{{ $roomType->id }}">{{ $roomType->localized_name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label">{{ __('form.status') }}</label>
                                    <select name="status_id[]" class="form-select form-select-sm multiple-select" multiple>
                                        @foreach($roomStatuses as $status)
                                            <option value="{{ $status->id }}">{{ $status->localized_name }}</option>
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
                                <x-datatables title="{{ __('rooms.room_list') }}" :data="$dataTable"></x-datatables>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</x-app-layout>
