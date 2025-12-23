<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">របាយការណ៍ប្រចាំខែ</h3>
                        <div>
                            <a href="{{ route('reports.monthly.print', ['month' => $month]) }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fas fa-print"></i> បោះពុម្ព
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Month Filter -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <form method="GET" class="d-flex">
                                    <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm me-2" onchange="this.form.submit()">
                                    <button type="submit" class="btn btn-outline-primary btn-sm">{{ __('global.filter') }}</button>
                                </form>
                            </div>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">ចំនួនចូលស្នាក់សរុប</h5>
                                        <h3 class="mb-0">{{ $totalCheckIns }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">ចំនួនចាកចេញសរុប</h5>
                                        <h3 class="mb-0">{{ $totalCheckOuts }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">ចំណូលសរុប</h5>
                                        <h3 class="mb-0">${{ number_format($totalRevenue, 2) }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Daily Breakdown Table -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">ការបំបែកប្រចាំថ្ងៃ</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>កាលបរិច្ឆេទ</th>
                                                        <th>ចូលស្នាក់</th>
                                                        <th>ចាកចេញ</th>
                                                        <th>ចំណូល</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($dailyStats as $day)
                                                    <tr>
                                                        <td>{{ \Carbon\Carbon::parse($day['date'])->format('M j, Y') }}</td>
                                                        <td>{{ $day['check_ins'] }}</td>
                                                        <td>{{ $day['check_outs'] }}</td>
                                                        <td>${{ number_format($day['revenue'], 2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                                <tfoot class="table-dark">
                                                    <tr>
                                                        <th>សរុប</th>
                                                        <th>{{ $totalCheckIns }}</th>
                                                        <th>{{ $totalCheckOuts }}</th>
                                                        <th>${{ number_format($totalRevenue, 2) }}</th>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Check-ins -->
                        <div class="row mt-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">ចូលស្នាក់ថ្មីៗ</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>កាលបរិច្ឆេទ</th>
                                                        <th>ឈ្មោះភ្ញៀវ</th>
                                                        <th>បន្ទប់</th>
                                                        <th>ចំនួនទឹកប្រាក់</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($checkIns->take(10) as $checkIn)
                                                    <tr>
                                                        <td>{{ $checkIn->check_in_date ? $checkIn->check_in_date->format('M j') : 'N/A' }}</td>
                                                        <td>{{ $checkIn->guest_name }}</td>
                                                        <td>{{ $checkIn->room->room_number ?? 'N/A' }}</td>
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

                            <!-- Recent Check-outs -->
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">ចាកចេញថ្មីៗ</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>កាលបរិច្ឆេទ</th>
                                                        <th>ឈ្មោះភ្ញៀវ</th>
                                                        <th>បន្ទប់</th>
                                                        <th>ចំនួនទឹកប្រាក់</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($checkOuts->take(10) as $checkOut)
                                                    <tr>
                                                        <td>{{ $checkOut->actual_check_out_at ? \Carbon\Carbon::parse($checkOut->actual_check_out_at)->format('M j') : 'N/A' }}</td>
                                                        <td>{{ $checkOut->guest_name }}</td>
                                                        <td>{{ $checkOut->room->room_number ?? 'N/A' }}</td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
