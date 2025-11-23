<x-app-layout>
    @push('css')
        @vite(['resources/css/dashboard-enhanced.css'])
    @endpush
    <!-- Top Statistics Row -->
    <div class="row g-3 mb-4">
        <!-- Services Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-muted mb-0 fw-normal" style="font-size: 0.8125rem;">Services</h6>
                        </div>
                        <div class="bg-primary bg-opacity-10 rounded p-2">
                            <i class="fa-duotone fa-briefcase text-primary"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <h2 class="mb-0 fw-bold">{{ $statistics['total_services'] }}</h2>
                            <div class="text-success small mt-1">
                                <i class="fa-solid fa-arrow-up me-1"></i>
                                <span class="fw-medium">Active</span>
                                <span class="text-muted ms-1">offerings</span>
                            </div>
                        </div>
                        <div style="width: 60px; height: 35px;">
                            <canvas id="sparklineServices"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Partners Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-muted mb-0 fw-normal" style="font-size: 0.8125rem;">Partners</h6>
                        </div>
                        <div class="bg-success bg-opacity-10 rounded p-2">
                            <i class="fa-duotone fa-handshake text-success"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <h2 class="mb-0 fw-bold">{{ $statistics['total_partners'] }}</h2>
                            <div class="text-success small mt-1">
                                <i class="fa-solid fa-arrow-up me-1"></i>
                                <span class="fw-medium">Trusted</span>
                                <span class="text-muted ms-1">partnerships</span>
                            </div>
                        </div>
                        <div style="width: 60px; height: 35px;">
                            <canvas id="sparklinePartners"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-muted mb-0 fw-normal" style="font-size: 0.8125rem;">Clients</h6>
                        </div>
                        <div class="bg-info bg-opacity-10 rounded p-2">
                            <i class="fa-duotone fa-building text-info"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <h2 class="mb-0 fw-bold">{{ $statistics['total_clients'] }}</h2>
                            <div class="text-success small mt-1">
                                <i class="fa-solid fa-arrow-up me-1"></i>
                                <span class="fw-medium">Happy</span>
                                <span class="text-muted ms-1">customers</span>
                            </div>
                        </div>
                        <div style="width: 60px; height: 35px;">
                            <canvas id="sparklineClients"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Card -->
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <h6 class="text-muted mb-0 fw-normal" style="font-size: 0.8125rem;">Products</h6>
                        </div>
                        <div class="bg-warning bg-opacity-10 rounded p-2">
                            <i class="fa-duotone fa-box text-warning"></i>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between">
                        <div>
                            <h2 class="mb-0 fw-bold">{{ $statistics['total_products'] }}</h2>
                            <div class="text-success small mt-1">
                                <i class="fa-solid fa-arrow-up me-1"></i>
                                <span class="fw-medium">Available</span>
                                <span class="text-muted ms-1">products</span>
                            </div>
                        </div>
                        <div style="width: 60px; height: 35px;">
                            <canvas id="sparklineProducts"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Row -->
    <div class="row g-3">
        <!-- Left Column - Charts -->
        <div class="col-xl-8">
            <!-- Traffic Sources & Activity Chart -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom d-flex align-items-center">
                    <h5 class="mb-0 fw-semibold">Activity Overview</h5>
                    <div class="ms-auto d-flex gap-2">
                        <button class="btn btn-sm btn-light active">Week</button>
                        <button class="btn btn-sm btn-light">Month</button>
                        <button class="btn btn-sm btn-light">Year</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 text-center border-end">
                            <div class="mb-1">
                                <i class="fa-duotone fa-eye text-primary me-1"></i>
                                <span class="text-muted small">Page Views</span>
                            </div>
                            <h3 class="mb-0 fw-bold">2,349</h3>
                            <small class="text-success">
                                <i class="fa-solid fa-arrow-up"></i> +5.2%
                            </small>
                        </div>
                        <div class="col-md-4 text-center border-end">
                            <div class="mb-1">
                                <i class="fa-duotone fa-user-plus text-success me-1"></i>
                                <span class="text-muted small">New Sessions</span>
                            </div>
                            <h3 class="mb-0 fw-bold">08:20</h3>
                            <small class="text-muted">avg duration</small>
                        </div>
                        <div class="col-md-4 text-center">
                            <div class="mb-1">
                                <i class="fa-duotone fa-globe text-info me-1"></i>
                                <span class="text-muted small">Total Online</span>
                            </div>
                            <h3 class="mb-0 fw-bold">5,378</h3>
                            <small class="text-success">
                                <i class="fa-solid fa-arrow-up"></i> +8.1%
                            </small>
                        </div>
                    </div>
                    <div style="height: 280px;">
                        <canvas id="activityChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Visited Countries & Browser Usage Row -->
            <div class="row g-3">
                <!-- Visited Countries Chart -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm mb-3 h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-duotone fa-globe me-2"></i>
                                Visited Countries
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($visitedCountries->count() > 0)
                                <div style="height: 300px;">
                                    <canvas id="visitedCountriesChart"></canvas>
                                </div>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="fa-duotone fa-earth-americas fs-1 mb-3 d-block opacity-25"></i>
                                    <p class="mb-0">No visitor data available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Browser Usage Chart -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm mb-3 h-100">
                        <div class="card-header bg-white border-bottom">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fa-duotone fa-browser me-2"></i>
                                Browser Usage
                            </h5>
                        </div>
                        <div class="card-body">
                            @if ($browserUsage->count() > 0)
                                <div class="mb-3" style="height: 200px;">
                                    <canvas id="browserUsageChart"></canvas>
                                </div>
                                <div class="list-group list-group-flush">
                                    @foreach ($browserUsage as $browser)
                                        <div class="list-group-item border-0 px-0 py-1">
                                            <div class="d-flex justify-content-between align-items-center mb-1">
                                                <span class="small">{{ $browser['name'] }}</span>
                                                <span class="small fw-medium">{{ $browser['percentage'] }}%</span>
                                            </div>
                                            <div class="progress" style="height: 3px;">
                                                <div class="progress-bar" style="width: {{ $browser['percentage'] }}%; background-color: {{ $browser['color'] }}"></div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center text-muted py-5">
                                    <i class="fa-duotone fa-browser fs-1 mb-3 d-block opacity-25"></i>
                                    <p class="mb-0">No browser data available</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Widgets -->
        <div class="col-xl-4">
            <!-- System Status Card -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fa-duotone fa-server me-2"></i>
                        System Status
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Memory Usage</span>
                            <span class="fw-medium">{{ $systemStatus['memory_usage'] }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar {{ $systemStatus['memory_usage'] > 80 ? 'bg-danger' : ($systemStatus['memory_usage'] > 60 ? 'bg-warning' : 'bg-success') }}" role="progressbar" style="width: {{ $systemStatus['memory_usage'] }}%"></div>
                        </div>
                        <small class="text-muted">{{ $systemStatus['memory_used'] }} / {{ $systemStatus['memory_total'] }}</small>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Storage Usage</span>
                            <span class="fw-medium">{{ $systemStatus['storage_usage'] }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar {{ $systemStatus['storage_usage'] > 80 ? 'bg-danger' : ($systemStatus['storage_usage'] > 60 ? 'bg-warning' : 'bg-info') }}" role="progressbar" style="width: {{ $systemStatus['storage_usage'] }}%"></div>
                        </div>
                        <small class="text-muted">{{ $systemStatus['storage_used'] }} / {{ $systemStatus['storage_total'] }}</small>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Server Load</span>
                            <span class="fw-medium">{{ $systemStatus['server_load'] }}%</span>
                        </div>
                        <div class="progress" style="height: 6px;">
                            <div class="progress-bar {{ $systemStatus['server_load'] > 80 ? 'bg-danger' : ($systemStatus['server_load'] > 60 ? 'bg-warning' : 'bg-primary') }}" role="progressbar" style="width: {{ min($systemStatus['server_load'], 100) }}%"></div>
                        </div>
                        <small class="text-muted">Load Average: {{ $systemStatus['load_average'] }}</small>
                    </div>
                    <div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Database Size</span>
                            <span class="fw-medium">{{ $systemStatus['database_size'] }} MB</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Cache: {{ $systemStatus['cache_size'] }}</small>
                            @if ($systemStatus['reverb_enabled'])
                                <span class="badge bg-success">
                                    <i class="fa-duotone fa-broadcast-tower me-1"></i>
                                    Reverb Active
                                </span>
                            @endif
                        </div>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between text-muted small">
                        <span>Laravel {{ $systemStatus['laravel_version'] }}</span>
                        <span>PHP {{ $systemStatus['php_version'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Permissions by Menu -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold">Permissions by Menu</h5>
                </div>
                <div class="card-body">
                    @if ($permissionsByMenu->count() > 0)
                        <canvas id="permissionsByMenuChart" height="200"></canvas>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="fa-duotone fa-chart-bar fs-1 mb-3 d-block opacity-25"></i>
                            <p class="mb-0">No permission data available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0 fw-semibold">Quick Stats</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded p-2 me-2">
                                    <i class="fa-duotone fa-clock text-primary"></i>
                                </div>
                                <span>Avg. Response Time</span>
                            </div>
                            <span class="badge bg-primary rounded-pill">06:25:00</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-success bg-opacity-10 rounded p-2 me-2">
                                    <i class="fa-duotone fa-circle-check text-success"></i>
                                </div>
                                <span>Completed Tasks</span>
                            </div>
                            <span class="badge bg-success rounded-pill">87%</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-warning bg-opacity-10 rounded p-2 me-2">
                                    <i class="fa-duotone fa-hourglass-half text-warning"></i>
                                </div>
                                <span>Pending Tasks</span>
                            </div>
                            <span class="badge bg-warning rounded-pill">24</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="bg-danger bg-opacity-10 rounded p-2 me-2">
                                    <i class="fa-duotone fa-triangle-exclamation text-danger"></i>
                                </div>
                                <span>Critical Issues</span>
                            </div>
                            <span class="badge bg-danger rounded-pill">3</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Row -->
    <div class="row g-3 mt-3">
        <!-- Recent Users -->
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fa-duotone fa-users me-2 text-primary"></i>
                        Recent Users
                    </h5>
                    <a href="{{ route('settings.users.index') }}" class="ms-auto text-decoration-none small">
                        View All <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentUsers as $user)
                            <div class="list-group-item border-0 px-3 py-3">
                                <div class="d-flex align-items-start">
                                    <div class="position-relative me-3">
                                        <div class="bg-gradient rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                            <span class="text-white fw-bold">{{ strtoupper(substr($user['name'], 0, 2)) }}</span>
                                        </div>
                                        <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle" style="width: 12px; height: 12px;"></span>
                                    </div>
                                    <div class="flex-fill min-width-0">
                                        <h6 class="mb-0 text-truncate">{{ $user['name'] }}</h6>
                                        <small class="text-muted d-block text-truncate">{{ $user['email'] }}</small>
                                        <small class="text-muted">
                                            <i class="fa-regular fa-clock me-1"></i>{{ $user['created_at_ago'] }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">
                                <i class="fa-duotone fa-user-slash fs-1 mb-3 d-block opacity-25"></i>
                                <p class="mb-0">No users yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Teams -->
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fa-duotone fa-users-gear me-2 text-success"></i>
                        Recent Teams
                    </h5>
                    <a href="{{ route('frontends.teams.index') }}" class="ms-auto text-decoration-none small">
                        View All <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentTeams as $team)
                            <div class="list-group-item border-0 px-3 py-3">
                                <div class="d-flex align-items-start">
                                    <div class="rounded d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; flex-shrink: 0; overflow: hidden;">
                                        @if ($team['photo'])
                                            @php
                                                $img = webp_variants($team['photo'], 'xs', null, 80);
                                            @endphp
                                            <a href="{{ $img['fallback'] }}" data-bs-popup="lightbox">
                                                <img src="{{ $img['fallback'] }}" srcset="{{ $img['srcset'] }}" alt="{{ $team['name'] }}" class="rounded" loading="lazy" style="width: 44px; height: 44px; object-fit: cover;">
                                            </a>
                                        @else
                                            <div class="bg-success bg-opacity-10 rounded d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                                                <i class="fa-duotone fa-user text-success fs-5"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-fill min-width-0">
                                        <h6 class="mb-0 text-truncate">{{ $team['name'] }}</h6>
                                        <small class="text-muted d-block text-truncate">
                                            <i class="fa-duotone fa-briefcase me-1"></i>
                                            {{ $team['position'] }}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fa-regular fa-clock me-1"></i>{{ $team['created_at_ago'] }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">
                                <i class="fa-duotone fa-users-slash fs-1 mb-3 d-block opacity-25"></i>
                                <p class="mb-0">No team members yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Services -->
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom d-flex align-items-center">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fa-duotone fa-briefcase me-2 text-warning"></i>
                        Recent Services
                    </h5>
                    <a href="{{ route('frontends.services.index') }}" class="ms-auto text-decoration-none small">
                        View All <i class="fa-solid fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($recentServices as $service)
                            <div class="list-group-item border-0 px-3 py-3">
                                <div class="d-flex align-items-start">
                                    <div class="bg-warning bg-opacity-10 rounded d-flex align-items-center justify-content-center me-3" style="width: 44px; height: 44px; flex-shrink: 0;">
                                        @if ($service['icon'])
                                            <i class="{{ $service['icon'] }} text-warning fs-5"></i>
                                        @else
                                            <i class="fa-duotone fa-wrench text-warning fs-5"></i>
                                        @endif
                                    </div>
                                    <div class="flex-fill min-width-0">
                                        <h6 class="mb-0 text-truncate">{{ $service['name'] }}</h6>
                                        <small class="text-muted d-block text-truncate">
                                            <i class="fa-duotone fa-file-lines me-1"></i>
                                            {!! $service['description'] !!}
                                        </small>
                                        <small class="text-muted">
                                            <i class="fa-regular fa-clock me-1"></i>{{ $service['created_at_ago'] }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">
                                <i class="fa-duotone fa-briefcase fs-1 mb-3 d-block opacity-25"></i>
                                <p class="mb-0">No services yet</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Common chart options
                const chartColors = {
                    teal: '#26a69a',
                    indigo: '#5c6bc0',
                    warning: '#ffc107',
                    danger: '#f44336',
                    primary: '#2196f3',
                    success: '#4caf50',
                    info: '#00bcd4',
                    purple: '#9c27b0',
                    pink: '#e91e63',
                    orange: '#ff9800'
                };

                // Sparkline Charts Configuration
                const sparklineConfig = {
                    type: 'line',
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        },
                        scales: {
                            x: {
                                display: false
                            },
                            y: {
                                display: false
                            }
                        },
                        elements: {
                            line: {
                                borderWidth: 2,
                                tension: 0.4
                            },
                            point: {
                                radius: 0
                            }
                        }
                    }
                };

                // Sparkline - Services
                new Chart(document.getElementById('sparklineServices').getContext('2d'), {
                    ...sparklineConfig,
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                        datasets: [{
                            data: [12, 19, 15, 25, 22, 30, 28],
                            borderColor: chartColors.primary,
                            backgroundColor: 'transparent'
                        }]
                    }
                });

                // Sparkline - Partners
                new Chart(document.getElementById('sparklinePartners').getContext('2d'), {
                    ...sparklineConfig,
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            data: [8, 12, 10, 15, 14, 18, 16],
                            borderColor: chartColors.success,
                            backgroundColor: 'transparent'
                        }]
                    }
                });

                // Sparkline - Clients
                new Chart(document.getElementById('sparklineClients').getContext('2d'), {
                    ...sparklineConfig,
                    data: {
                        labels: ['W1', 'W2', 'W3', 'W4'],
                        datasets: [{
                            data: [20, 25, 30, 35],
                            borderColor: chartColors.info,
                            backgroundColor: 'transparent'
                        }]
                    }
                });

                // Sparkline - Products
                new Chart(document.getElementById('sparklineProducts').getContext('2d'), {
                    ...sparklineConfig,
                    data: {
                        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
                        datasets: [{
                            data: [30, 32, 35, 38, 42, 45, 48],
                            borderColor: chartColors.warning,
                            backgroundColor: 'transparent'
                        }]
                    }
                });

                // Activity Overview Chart (Area Chart)
                const activityCtx = document.getElementById('activityChart').getContext('2d');
                const activityGradient = activityCtx.createLinearGradient(0, 0, 0, 300);
                activityGradient.addColorStop(0, 'rgba(33, 150, 243, 0.3)');
                activityGradient.addColorStop(1, 'rgba(33, 150, 243, 0.01)');

                new Chart(activityCtx, {
                    type: 'line',
                    data: {
                        labels: ['00:00', '04:00', '08:00', '12:00', '16:00', '20:00', '00:00'],
                        datasets: [{
                            label: 'Page Views',
                            data: [300, 450, 800, 1200, 1500, 1800, 1300],
                            borderColor: chartColors.primary,
                            backgroundColor: activityGradient,
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointHoverRadius: 6,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: chartColors.primary,
                            pointBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                backgroundColor: 'rgba(0,0,0,0.8)',
                                padding: 12,
                                cornerRadius: 6
                            }
                        },
                        scales: {
                            x: {
                                grid: {
                                    display: false
                                }
                            },
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,0.05)',
                                    drawBorder: false
                                },
                                ticks: {
                                    callback: function(value) {
                                        return value >= 1000 ? (value / 1000) + 'k' : value;
                                    }
                                }
                            }
                        },
                        interaction: {
                            mode: 'nearest',
                            axis: 'x',
                            intersect: false
                        }
                    }
                });

                // Visited Countries Chart (Doughnut)
                @if ($visitedCountries->count() > 0)
                    new Chart(document.getElementById('visitedCountriesChart').getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: {!! json_encode($visitedCountries->pluck('name')) !!},
                            datasets: [{
                                data: {!! json_encode($visitedCountries->pluck('count')) !!},
                                backgroundColor: {!! json_encode($visitedCountries->pluck('color')) !!},
                                borderColor: '#fff',
                                borderWidth: 3,
                                hoverOffset: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '65%',
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 15,
                                        usePointStyle: true,
                                        font: {
                                            size: 12
                                        }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0,0,0,0.8)',
                                    padding: 12,
                                    cornerRadius: 6,
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((value / total) * 100).toFixed(1);
                                            return label + ': ' + value.toLocaleString() + ' visitors (' + percentage + '%)';
                                        }
                                    }
                                }
                            }
                        }
                    });
                @endif

                // Browser Usage Chart (Doughnut)
                @if ($browserUsage->count() > 0)
                    new Chart(document.getElementById('browserUsageChart').getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: {!! json_encode($browserUsage->pluck('name')) !!},
                            datasets: [{
                                data: {!! json_encode($browserUsage->pluck('count')) !!},
                                backgroundColor: {!! json_encode($browserUsage->pluck('color')) !!},
                                borderColor: '#fff',
                                borderWidth: 3,
                                hoverOffset: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '60%',
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0,0,0,0.8)',
                                    padding: 12,
                                    cornerRadius: 6,
                                    callbacks: {
                                        label: function(context) {
                                            const label = context.label || '';
                                            const value = context.parsed || 0;
                                            const percentage = {!! json_encode($browserUsage->pluck('percentage')) !!}[context.dataIndex];
                                            return label + ': ' + value.toLocaleString() + ' (' + percentage + '%)';
                                        }
                                    }
                                }
                            }
                        }
                    });
                @endif

                // Permissions by Menu Chart (Horizontal Bar)
                @if ($permissionsByMenu->count() > 0)
                    new Chart(document.getElementById('permissionsByMenuChart').getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: {!! json_encode($permissionsByMenu->pluck('name')) !!},
                            datasets: [{
                                label: 'Permissions',
                                data: {!! json_encode($permissionsByMenu->pluck('count')) !!},
                                backgroundColor: chartColors.primary,
                                borderRadius: 6,
                                borderSkipped: false,
                                barThickness: 20
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y',
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0,0,0,0.8)',
                                    padding: 12,
                                    cornerRadius: 6
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        stepSize: 1
                                    }
                                },
                                y: {
                                    grid: {
                                        display: false
                                    }
                                }
                            }
                        }
                    });
                @endif
            });
        </script>

        <style>
            /* Custom color utilities */
            .text-teal {
                color: #26a69a !important;
            }

            .bg-teal {
                background-color: #26a69a !important;
            }

            .text-indigo {
                color: #5c6bc0 !important;
            }

            .bg-indigo {
                background-color: #5c6bc0 !important;
            }

            /* Card hover effects */
            .card {
                transition: all 0.3s ease;
            }

            .card:hover {
                transform: translateY(-2px);
                box-shadow: 0 0.5rem 1.5rem rgba(0, 0, 0, 0.1) !important;
            }

            /* Progress bar animations */
            .progress-bar {
                transition: width 1s ease-in-out;
            }

            /* List item hover */
            .list-group-item {
                transition: background-color 0.2s ease;
            }

            .list-group-item:hover {
                background-color: rgba(0, 0, 0, 0.02);
            }

            /* Badge animations */
            .badge {
                transition: all 0.2s ease;
            }

            /* Responsive adjustments */
            @media (max-width: 768px) {
                .card-header h5 {
                    font-size: 0.95rem;
                }
            }
        </style>
    @endpush
</x-app-layout>
