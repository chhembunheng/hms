<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">របាយការណ៍ភ្ញៀវ</h3>
                        <div>
                            <a href="{{ route('reports.guest.print', ['start_date' => $startDate, 'end_date' => $endDate]) }}" target="_blank" class="btn btn-primary btn-sm">
                                <i class="fas fa-print"></i> បោះពុម្ព
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Date Range Filter -->
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <form method="GET" class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.start_date') }}</label>
                                        <input type="date" name="start_date" value="{{ $startDate }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">{{ __('global.end_date') }}</label>
                                        <input type="date" name="end_date" value="{{ $endDate }}" class="form-control form-control-sm">
                                    </div>
                                    <div class="col-md-4 d-flex align-items-end">
                                        <button type="submit" class="btn btn-outline-primary btn-sm">{{ __('global.filter') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <!-- Summary Cards -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">ចំនួនភ្ញៀវសរុប</h5>
                                        <h3 class="mb-0">{{ $totalGuests }}</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h5 class="card-title">ចំនួនការកក់សរុប</h5>
                                        <h3 class="mb-0">{{ $totalBookings }}</h3>
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

                        <!-- Guest Statistics -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">ការបំបែកតាមសញ្ជាតិ</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>ប្រទេស</th>
                                                        <th>ចំនួន</th>
                                                        <th>ចំណូល</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($nationalityStats as $stat)
                                                    <tr>
                                                        <td>{{ $stat['country'] ?: __('global.unknown') }}</td>
                                                        <td>{{ $stat['count'] }}</td>
                                                        <td>${{ number_format($stat['revenue'], 2) }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center">{{ __('global.no_data') }}</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">ការបំបែកតាមប្រភេទភ្ញៀវ</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>ប្រភេទភ្ញៀវ</th>
                                                        <th>ចំនួន</th>
                                                        <th>ចំណូល</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($guestTypeStats as $type => $stat)
                                                    <tr>
                                                        <td>{{ $type ?: __('global.unknown') }}</td>
                                                        <td>{{ $stat['count'] }}</td>
                                                        <td>${{ number_format($stat['revenue'], 2) }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Guest List -->
                        <div class="row">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="card-title mb-0">បញ្ជីភ្ញៀវ</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <thead>
                                                    <tr>
                                                        <th>ឈ្មោះភ្ញៀវ</th>
                                                        <th>អ៊ីមែល</th>
                                                        <th>ទូរស័ព្ទ</th>
                                                        <th>ប្រទេស</th>
                                                        <th>ការកក់សរុប</th>
                                                        <th>ចំណាយសរុប</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($guests as $guest)
                                                    <tr>
                                                        <td>{{ $guest->name }}</td>
                                                        <td>{{ $guest->email }}</td>
                                                        <td>{{ $guest->phone }}</td>
                                                        <td>{{ $guest->country ?: __('global.unknown') }}</td>
                                                        <td>{{ $guest->checkIns->count() }}</td>
                                                        <td>${{ number_format($guest->checkIns->sum('paid_amount'), 2) }}</td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="6" class="text-center">{{ __('global.no_data') }}</td>
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
