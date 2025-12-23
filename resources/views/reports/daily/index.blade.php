<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">របាយការណ៍ប្រចាំថ្ងៃ</h3>
                        <div>
                            <a href="{{ route('reports.daily.print', ['date' => $date]) }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fas fa-print"></i> បោះពុម្ព
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Date Filter -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <form method="GET" class="d-flex">
                                    <input type="date" name="date" value="{{ $date }}" class="form-control form-control-sm me-2" onchange="this.form.submit()">
                                    <button type="submit" class="btn btn-outline-primary btn-sm">{{ __('global.filter') }}</button>
                                </form>
                            </div>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">ចំនួនចូលស្នាក់</h5>
                                        <h3 class="mb-0">{{ $totalCheckIns }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">ចំនួនចាកចេញ</h5>
                                        <h3 class="mb-0">{{ $totalCheckOuts }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">ចំណូលសរុប</h5>
                                        <h3 class="mb-0">${{ number_format($totalRevenue, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card bg-warning text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">ភ្ញៀវកំពុងស្នាក់</h5>
                                        <h3 class="mb-0">{{ $stayingGuests->count() }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Check-ins Table -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">ចូលស្នាក់ថ្ងៃនេះ</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>ឈ្មោះភ្ញៀវ</th>
                                                        <th>បន្ទប់</th>
                                                        <th>ពេលវេលា</th>
                                                        <th>ចំនួនទឹកប្រាក់</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($checkIns as $checkIn)
                                                    <tr>
                                                        <td>{{ $checkIn->guest_name }}</td>
                                                        <td>{{ $checkIn->room->room_number ?? 'N/A' }}</td>
                                                        <td>{{ $checkIn->check_in_time ? $checkIn->check_in_time->format('H:i') : 'N/A' }}</td>
                                                        <td>${{ number_format($checkIn->paid_amount, 2) }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">{{ __('global.no_data') }}</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Check-outs Table -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">ចាកចេញថ្ងៃនេះ</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>ឈ្មោះភ្ញៀវ</th>
                                                        <th>បន្ទប់</th>
                                                        <th>ពេលវេលា</th>
                                                        <th>ចំនួនទឹកប្រាក់</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($checkOuts as $checkOut)
                                                    <tr>
                                                        <td>{{ $checkOut->guest_name }}</td>
                                                        <td>{{ $checkOut->room->room_number ?? 'N/A' }}</td>
                                                        <td>{{ $checkOut->actual_check_out_at ? \Carbon\Carbon::parse($checkOut->actual_check_out_at)->format('H:i') : 'N/A' }}</td>
                                                        <td>${{ number_format($checkOut->paid_amount, 2) }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="4" class="text-center">{{ __('global.no_data') }}</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Staying Guests -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">ភ្ញៀវកំពុងស្នាក់បច្ចុប្បន្ន</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>ឈ្មោះភ្ញៀវ</th>
                                                        <th>បន្ទប់</th>
                                                        <th>កាលបរិច្ឆេទចូលស្នាក់</th>
                                                        <th>កាលបរិច្ឆេទចាកចេញ</th>
                                                        <th>ស្ថានភាព</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($stayingGuests as $guest)
                                                    <tr>
                                                        <td>{{ $guest->guest_name }}</td>
                                                        <td>{{ $guest->room->room_number ?? 'N/A' }}</td>
                                                        <td>{{ $guest->check_in_date ? $guest->check_in_date->format('Y-m-d') : 'N/A' }}</td>
                                                        <td>{{ $guest->check_out_date ? $guest->check_out_date->format('Y-m-d') : 'N/A' }}</td>
                                                        <td>
                                                            <span class="badge bg-success">បានចូលស្នាក់</span>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="5" class="text-center">{{ __('global.no_data') }}</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
