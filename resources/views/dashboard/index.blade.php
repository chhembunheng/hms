<x-app-layout>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('dashboard.welcome_message') }}</h3>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ __('dashboard.welcome_description') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <!-- Room Statistics -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    {{ __('dashboard.total_rooms') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRooms }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-bed fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Occupancy Rate -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    {{ __('dashboard.occupancy_rate') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $occupancyRate }}%</div>
                                <div class="text-xs text-muted">{{ $occupiedRooms }}/{{ $totalRooms }} {{ __('dashboard.occupied') }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-chart-pie fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Check-ins -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    {{ __('dashboard.today_check_ins') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $todayCheckIns }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-sign-in-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Revenue -->
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    {{ __('dashboard.today_revenue') }}
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">${{ number_format($todayRevenue, 2) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Monthly Statistics -->
        <div class="row mb-4">
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('dashboard.monthly_overview') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="text-center">
                                    <h4 class="text-primary">{{ $monthlyCheckIns }}</h4>
                                    <p class="text-muted mb-0">{{ __('dashboard.monthly_check_ins') }}</p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="text-center">
                                    <h4 class="text-success">${{ number_format($monthlyRevenue, 2) }}</h4>
                                    <p class="text-muted mb-0">{{ __('dashboard.monthly_revenue') }}</p>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <small class="text-muted">{{ __('dashboard.last_month') }}</small>
                                <div class="text-primary">{{ $lastMonthCheckIns }}</div>
                            </div>
                            <div class="col-sm-6">
                                <small class="text-muted">{{ __('dashboard.last_month') }}</small>
                                <div class="text-success">${{ number_format($lastMonthRevenue, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('dashboard.room_status') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-pie pt-4">
                            <canvas id="roomStatusChart"></canvas>
                        </div>
                        <hr>
                        <div class="mt-4 text-center small">
                            @foreach($roomStatuses as $status => $count)
                            <span class="mr-2">
                                <i class="fas fa-circle" style="color: {{ \App\Http\Controllers\DashboardController::getStatusColor($status) }}"></i> {{ $status }}: {{ $count }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row mb-4">
            <div class="col-xl-8 col-lg-7">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('dashboard.revenue_trend') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-area">
                            <canvas id="revenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-5">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('dashboard.revenue_by_room_type') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-bar">
                            <canvas id="roomTypeRevenueChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('dashboard.recent_check_ins') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>{{ __('guests.guest_name') }}</th>
                                        <th>{{ __('rooms.room') }}</th>
                                        <th>{{ __('global.date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentCheckIns as $checkIn)
                                    <tr>
                                        <td>{{ $checkIn->guest_name }}</td>
                                        <td>{{ $checkIn->room->room_number ?? 'N/A' }}</td>
                                        <td>{{ $checkIn->check_in_date ? $checkIn->check_in_date->format('M j') : 'N/A' }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">{{ __('global.no_data') }}</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('dashboard.quick_actions') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('checkin.walkin.add') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> {{ __('checkins.new_check_in') }}
                            </a>
                            <a href="{{ route('checkin.staying.index') }}" class="btn btn-success">
                                <i class="fas fa-bed"></i> {{ __('checkins.staying_guests') }}
                            </a>
                            <a href="{{ route('guests.list.add') }}" class="btn btn-info">
                                <i class="fas fa-user-plus"></i> {{ __('guests.add_guest') }}
                            </a>
                            <a href="{{ route('reports.daily.index') }}" class="btn btn-warning">
                                <i class="fas fa-chart-line"></i> {{ __('reports.daily_report') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Status color mapping
        const statusColors = {
            'Available': '#28a745',
            'Occupied': '#dc3545',
            'Cleaning': '#ffc107',
            'Maintenance': '#6c757d',
            'Out of Order': '#dc3545',
            'ទំនេរ': '#28a745',
            'មានអ្នកស្នាក់នៅ': '#dc3545',
            'កំពុងសម្អាត': '#ffc107',
            'កំពុងជួសជុល': '#6c757d',
            'មិនអាចប្រើបាន': '#dc3545'
        };

        // Room Status Pie Chart
        const roomStatusCtx = document.getElementById('roomStatusChart').getContext('2d');
        const roomStatusData = @json($roomStatuses);
        const roomStatusLabels = @json($roomStatusLabels);
        const roomStatusValues = Object.values(roomStatusData);

        new Chart(roomStatusCtx, {
            type: 'doughnut',
            data: {
                labels: Object.values(roomStatusLabels),
                datasets: [{
                    data: roomStatusValues,
                    backgroundColor: Object.keys(roomStatusData).map(status => statusColors[status] || '#858796'),
                    hoverBackgroundColor: Object.keys(roomStatusData).map(status => statusColors[status] || '#858796'),
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        });

        // Revenue Trend Line Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueData = @json($monthlyRevenueTrend);

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.map(item => item.month),
                datasets: [{
                    label: '{{ __('global.revenue') }}',
                    lineTension: 0.3,
                    backgroundColor: "rgba(78, 115, 223, 0.05)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    pointRadius: 3,
                    pointBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointBorderColor: "rgba(78, 115, 223, 1)",
                    pointHoverRadius: 3,
                    pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                    pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                    pointHitRadius: 10,
                    pointBorderWidth: 2,
                    data: revenueData.map(item => item.revenue),
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        time: {
                            unit: 'date'
                        },
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 7
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return '$' + value.toLocaleString();
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    intersect: false,
                    mode: 'index',
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': $' + tooltipItem.yLabel.toLocaleString();
                        }
                    }
                }
            }
        });

        // Room Type Revenue Bar Chart
        const roomTypeRevenueCtx = document.getElementById('roomTypeRevenueChart').getContext('2d');
        const roomTypeRevenueData = @json($revenueByRoomType);

        new Chart(roomTypeRevenueCtx, {
            type: 'bar',
            data: {
                labels: roomTypeRevenueData.map(item => item.name),
                datasets: [{
                    label: '{{ __('global.revenue') }}',
                    backgroundColor: "rgba(78, 115, 223, 0.8)",
                    borderColor: "rgba(78, 115, 223, 1)",
                    data: roomTypeRevenueData.map(item => item.revenue),
                }],
            },
            options: {
                maintainAspectRatio: false,
                layout: {
                    padding: {
                        left: 10,
                        right: 25,
                        top: 25,
                        bottom: 0
                    }
                },
                scales: {
                    xAxes: [{
                        gridLines: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            maxTicksLimit: 6
                        },
                    }],
                    yAxes: [{
                        ticks: {
                            maxTicksLimit: 5,
                            padding: 10,
                            callback: function(value, index, values) {
                                return '$' + value.toLocaleString();
                            }
                        },
                        gridLines: {
                            color: "rgb(234, 236, 244)",
                            zeroLineColor: "rgb(234, 236, 244)",
                            drawBorder: false,
                            borderDash: [2],
                            zeroLineBorderDash: [2]
                        }
                    }],
                },
                legend: {
                    display: false
                },
                tooltips: {
                    titleMarginBottom: 10,
                    titleFontColor: '#6e707e',
                    titleFontSize: 14,
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                    callbacks: {
                        label: function(tooltipItem, chart) {
                            var datasetLabel = chart.datasets[tooltipItem.datasetIndex].label || '';
                            return datasetLabel + ': $' + tooltipItem.yLabel.toLocaleString();
                        }
                    }
                },
            }
        });
    </script>

    @push('styles')
    <style>
        .border-left-primary { border-left: 0.25rem solid #4e73df !important; }
        .border-left-success { border-left: 0.25rem solid #1cc88a !important; }
        .border-left-info { border-left: 0.25rem solid #36b9cc !important; }
        .border-left-warning { border-left: 0.25rem solid #f6c23e !important; }
        .text-primary { color: #5a5c69 !important; }
        .text-muted { color: #858796 !important; }
        .chart-area, .chart-bar, .chart-pie { position: relative; height: 20rem; width: 100%; }
    </style>
    @endpush
</x-app-layout>
