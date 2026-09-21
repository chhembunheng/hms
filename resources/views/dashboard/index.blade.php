<x-app-layout>
<div class="container-fluid py-3 px-3 px-xl-4 modern-dashboard-wrapper">

    <!-- 1. Executive Hotel Telemetry Header (Clean & High Contrast) -->
    <div class="dashboard-hero card border-0 shadow-sm mb-4 position-relative overflow-hidden">
        <div class="dashboard-hero-bg"></div>
        <div class="card-body p-4 position-relative z-1">
            <div class="row align-items-center g-3">
                <div class="col-lg-8">
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                        <span class="pulse-badge">
                            <span class="pulse-dot"></span>
                            {{ __('PMS CORE ACTIVE') }}
                        </span>
                        <span class="text-white-50 small">•</span>
                        <span class="hero-live-clock" id="live-time-display">
                            <i class="fa-solid fa-clock me-1 text-info"></i>
                            {{ now()->setTimezone('Asia/Phnom_Penh')->format('l, d M Y — H:i:s') }} (GMT+7)
                        </span>
                    </div>

                    @php
                        $currentHour = now()->hour;
                        $greeting = $currentHour < 12 
                            ? __('dashboard.good_morning') 
                            : ($currentHour < 18 ? __('dashboard.good_afternoon') : __('dashboard.good_evening'));
                    @endphp

                    <h2 class="text-white fw-bold mb-1 fs-3">
                        {{ $greeting }}, {{ Auth::user()?->name ?? 'Hotel Executive' }}
                    </h2>
                    <p class="hero-subtitle mb-0 small">
                        {{ __('Hotel Operations & Analytical Intelligence — Real-time telemetry, room inventory status, guest traffic flow, and financial trajectories.') }}
                    </p>
                </div>

                <div class="col-lg-4 text-lg-end">
                    <div class="d-flex flex-wrap align-items-center justify-content-lg-end gap-2">
                        <div class="hero-date-chip">
                            <i class="fa-solid fa-calendar-days text-info me-1"></i>
                            <span>{{ now()->format('M Y') }}</span>
                        </div>
                        <button type="button" class="btn btn-hero-glass btn-sm" onclick="window.location.reload()" title="{{ __('Refresh Telemetry') }}">
                            <i class="fa-solid fa-rotate-right me-1"></i>
                            <span>{{ __('Sync') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. 4 Modern Hotel Executive KPI Cards -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Live Occupancy -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card card border-0 shadow-sm h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="kpi-label">{{ __('dashboard.occupancy_rate') }}</div>
                        <div class="kpi-icon-badge kpi-icon-blue">
                            <i class="fa-solid fa-bed"></i>
                        </div>
                    </div>

                    <div class="d-flex align-items-baseline gap-2 mb-2">
                        <h3 class="kpi-value mb-0">{{ $occupancyRate }}<span class="fs-6 fw-semibold text-muted">%</span></h3>
                        <span class="badge bg-emerald-subtle text-emerald border border-emerald-subtle rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">
                            {{ $availableRooms }} {{ __('Free') }}
                        </span>
                    </div>

                    <div class="progress kpi-progress mb-2">
                        <div class="progress-bar bg-gradient-blue" role="progressbar" style="width: {{ max(5, $occupancyRate) }}%;"></div>
                    </div>

                    <div class="d-flex justify-content-between kpi-subtext">
                        <span><strong>{{ $occupiedRooms }}</strong> / {{ $totalRooms }} {{ __('dashboard.occupied') }}</span>
                        <span>{{ $totalFloors }} {{ __('rooms.floors') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Today's Guest Flow -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card card border-0 shadow-sm h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="kpi-label">{{ __("Today's Flow") }}</div>
                        <div class="kpi-icon-badge kpi-icon-emerald">
                            <i class="fa-solid fa-right-left"></i>
                        </div>
                    </div>

                    <div class="d-flex align-items-baseline gap-2 mb-2">
                        <h3 class="kpi-value mb-0 text-dark">{{ $todayCheckIns }} <span class="fs-6 fw-normal text-muted">{{ __('In') }}</span></h3>
                        <span class="text-muted fw-bold">/</span>
                        <h4 class="kpi-value mb-0 text-secondary fs-4">{{ $todayCheckOuts }} <span class="fs-6 fw-normal text-muted">{{ __('Out') }}</span></h4>
                    </div>

                    @php
                        $flowTotal = max(1, $todayCheckIns + $todayCheckOuts);
                        $inPct = round(($todayCheckIns / $flowTotal) * 100);
                    @endphp
                    <div class="progress kpi-progress mb-2">
                        <div class="progress-bar bg-emerald" role="progressbar" style="width: {{ $inPct }}%;"></div>
                        <div class="progress-bar bg-warning" role="progressbar" style="width: {{ 100 - $inPct }}%;"></div>
                    </div>

                    <div class="d-flex justify-content-between kpi-subtext">
                        <span><span class="dot-indicator bg-emerald"></span> {{ $todayCheckIns }} {{ __('Arrivals') }}</span>
                        <span><span class="dot-indicator bg-warning"></span> {{ $todayCheckOuts }} {{ __('Departures') }}</span>
                        <span class="fw-semibold text-dark">{{ $activeGuests }} {{ __('In-House') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 3: Today's Revenue -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card card border-0 shadow-sm h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="kpi-label">{{ __("Today's Collections") }}</div>
                        <div class="kpi-icon-badge kpi-icon-green">
                            <i class="fa-solid fa-dollar-sign"></i>
                        </div>
                    </div>

                    <div class="d-flex align-items-baseline gap-2 mb-2">
                        <h3 class="kpi-value mb-0 text-success">${{ number_format($todayRevenue, 2) }}</h3>
                        <span class="badge bg-light text-secondary border rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">USD</span>
                    </div>

                    @php
                        $todayGoalRatio = $monthlyRevenue > 0 ? min(100, round(($todayRevenue / $monthlyRevenue) * 100)) : ($todayRevenue > 0 ? 100 : 0);
                    @endphp
                    <div class="progress kpi-progress mb-2">
                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ max(4, $todayGoalRatio) }}%;"></div>
                    </div>

                    <div class="d-flex justify-content-between kpi-subtext">
                        <span>{{ __('dashboard.cashflow_today') }}</span>
                        <span class="fw-medium text-success">{{ __('Direct Cash/Card') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 4: Monthly Gross & ADR -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card card border-0 shadow-sm h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="kpi-label">{{ __('Monthly Gross & ADR') }}</div>
                        <div class="kpi-icon-badge kpi-icon-purple">
                            <i class="fa-solid fa-chart-line"></i>
                        </div>
                    </div>

                    <div class="d-flex align-items-baseline gap-2 mb-2">
                        <h3 class="kpi-value mb-0 text-indigo">${{ number_format($monthlyRevenue, 2) }}</h3>
                        <span class="badge bg-purple-subtle text-purple border border-purple-subtle rounded-pill px-2 py-0.5" style="font-size: 0.7rem;">
                            {{ $monthlyCheckIns }} {{ __('Folios') }}
                        </span>
                    </div>

                    @php
                        $monthGrowth = $lastMonthRevenue > 0 ? min(100, round(($monthlyRevenue / $lastMonthRevenue) * 100)) : ($monthlyRevenue > 0 ? 100 : 0);
                    @endphp
                    <div class="progress kpi-progress mb-2">
                        <div class="progress-bar bg-gradient-purple" role="progressbar" style="width: {{ max(5, $monthGrowth) }}%;"></div>
                    </div>

                    <div class="d-flex justify-content-between kpi-subtext">
                        <span>ADR: <strong>${{ number_format($adr, 2) }}</strong></span>
                        <span>{{ __('Last Month') }}: ${{ number_format($lastMonthRevenue, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Primary Visual Analytics: 2 Large Telemetry Charts -->
    <div class="row g-3 mb-4">
        <!-- Chart 1: 6-Month Revenue & Financial Trajectory (Spline Area) -->
        <div class="col-lg-8">
            <div class="card modern-section-card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="section-icon-badge bg-blue-subtle">
                            <i class="fa-solid fa-chart-area text-primary"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Financial & Revenue Trajectory') }}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ __('6-Month comparative monthly gross collections') }}</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-indigo-subtle text-indigo border border-indigo-subtle rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">
                            <i class="fa-solid fa-circle-dot me-1" style="font-size: 9px;"></i>
                            {{ __('USD Currency') }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-3.5">
                    <div style="height: 280px; position: relative;">
                        <canvas id="modernRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart 2: Room Status & Inventory Health (Donut / Pie Chart) -->
        <div class="col-lg-4">
            <div class="card modern-section-card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="section-icon-badge bg-emerald-subtle">
                            <i class="fa-solid fa-chart-pie text-emerald"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Room Status Distribution') }}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $totalRooms }} {{ __('Total Inventory Units') }}</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                    <div style="height: 180px; position: relative;" class="my-auto">
                        <canvas id="modernStatusChart"></canvas>
                        <!-- Center Donut Stat -->
                        <div class="chart-center-metric">
                            <div class="fs-4 fw-bold text-dark lh-1">{{ $totalRooms }}</div>
                            <small class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">{{ __('rooms.rooms') }}</small>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-top">
                        <div class="row g-2">
                            @foreach($roomStatuses as $status => $count)
                                @php
                                    $statusColor = \App\Http\Controllers\DashboardController::getStatusColor($status);
                                    $pct = $totalRooms > 0 ? round(($count / $totalRooms) * 100) : 0;
                                @endphp
                                <div class="col-6">
                                    <div class="status-legend-item">
                                        <div class="d-flex align-items-center gap-1.5 text-truncate">
                                            <span class="legend-color-dot" style="background-color: {{ $statusColor }};"></span>
                                            <span class="fw-medium text-dark text-truncate" style="font-size: 0.75rem;">{{ $roomStatusLabels[$status] ?? $status }}</span>
                                        </div>
                                        <span class="fw-bold text-dark ms-1" style="font-size: 0.78rem;">{{ $count }} <small class="text-muted fw-normal" style="font-size: 0.68rem;">({{ $pct }}%)</small></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. Secondary Analytics Row: 7-Day Flow Bar Chart & Room Category Distribution Donut -->
    <div class="row g-3 mb-4">
        <!-- Chart 3: 7-Day Front Desk Guest Flow (Dual Bar Chart) -->
        <div class="col-lg-7">
            <div class="card modern-section-card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="section-icon-badge bg-amber-subtle">
                            <i class="fa-solid fa-chart-column text-amber"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('7-Day Front Desk Traffic Flow') }}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ __('Daily guest check-ins vs check-outs velocity') }}</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <span class="d-inline-flex align-items-center gap-1.5" style="font-size: 0.75rem;">
                            <span class="legend-color-dot bg-emerald"></span>
                            <span class="text-muted">{{ __('Arrivals') }}</span>
                        </span>
                        <span class="d-inline-flex align-items-center gap-1.5" style="font-size: 0.75rem;">
                            <span class="legend-color-dot bg-warning"></span>
                            <span class="text-muted">{{ __('Departures') }}</span>
                        </span>
                    </div>
                </div>

                <div class="card-body p-3.5">
                    <div style="height: 250px; position: relative;">
                        <canvas id="weeklyFlowChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart 4: Room Category Inventory & Revenue (Pie / Donut Chart) -->
        <div class="col-lg-5">
            <div class="card modern-section-card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="section-icon-badge bg-purple-subtle">
                            <i class="fa-solid fa-layer-group text-purple"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Room Category Distribution') }}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ __('Room inventory capacity by specification') }}</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                    <div style="height: 180px; position: relative;" class="my-auto">
                        <canvas id="categoryDistributionChart"></canvas>
                        <div class="chart-center-metric">
                            <div class="fs-5 fw-bold text-dark lh-1">{{ $roomTypesList->count() }}</div>
                            <small class="text-muted text-uppercase" style="font-size: 0.62rem; letter-spacing: 0.5px;">{{ __('Types') }}</small>
                        </div>
                    </div>

                    <div class="mt-3 pt-3 border-top">
                        <div class="row g-2">
                            @php
                                $categoryPalette = ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#64748b'];
                            @endphp
                            @foreach($roomTypesList as $idx => $rt)
                                @php
                                    $cColor = $categoryPalette[$idx % count($categoryPalette)];
                                    $rtPct = $totalRooms > 0 ? round(($rt->rooms_count / $totalRooms) * 100) : 0;
                                @endphp
                                <div class="col-6">
                                    <div class="status-legend-item">
                                        <div class="d-flex align-items-center gap-1.5 text-truncate">
                                            <span class="legend-color-dot" style="background-color: {{ $cColor }};"></span>
                                            <span class="fw-medium text-dark text-truncate" style="font-size: 0.72rem;">{{ $rt->localized_name }}</span>
                                        </div>
                                        <span class="fw-bold text-dark ms-1" style="font-size: 0.75rem;">{{ $rt->rooms_count }} <small class="text-muted fw-normal" style="font-size: 0.65rem;">({{ $rtPct }}%)</small></span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. Recent Front Desk Registrations Table (Clean Audit View) -->
    <div class="card modern-section-card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-2.5">
                <div class="section-icon-badge bg-slate-100">
                    <i class="fa-solid fa-clock-rotate-left text-secondary"></i>
                </div>
                <div>
                    <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Recent Front Desk Registrations') }}</h6>
                    <small class="text-muted" style="font-size: 0.75rem;">{{ __('Real-time guest arrivals and stay records') }}</small>
                </div>
            </div>

            <span class="badge bg-slate-100 text-secondary border rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">
                {{ $recentCheckIns->count() }} {{ __('Recent Logs') }}
            </span>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 modern-table">
                    <thead>
                        <tr>
                            <th class="ps-3">{{ __('Guest & Booking') }}</th>
                            <th>{{ __('Room') }}</th>
                            <th>{{ __('Dates') }}</th>
                            <th class="text-end">{{ __('Paid') }}</th>
                            <th class="pe-3 text-center">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentCheckIns as $checkIn)
                            <tr>
                                <td class="ps-3 py-2.5">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="avatar-circle">
                                            <i class="fa-solid fa-user text-secondary" style="font-size: 12px;"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $checkIn->guest_name }}</div>
                                            <span class="booking-tag">{{ $checkIn->booking_number ?? ('#' . $checkIn->id) }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-2.5">
                                    <span class="badge bg-light text-dark border fw-bold px-2 py-1">
                                        {{ $checkIn->room?->room_number ?? 'N/A' }}
                                    </span>
                                    @if($checkIn->room?->roomType)
                                        <div class="text-muted" style="font-size: 0.7rem;">{{ $checkIn->room->roomType->localized_name }}</div>
                                    @endif
                                </td>
                                <td class="py-2.5">
                                    <div class="text-dark" style="font-size: 0.78rem;">{{ $checkIn->check_in_date ? $checkIn->check_in_date->format('d M Y') : 'N/A' }}</div>
                                    <div class="text-muted" style="font-size: 0.7rem;">Out: {{ $checkIn->check_out_date ? $checkIn->check_out_date->format('d M Y') : 'N/A' }}</div>
                                </td>
                                <td class="py-2.5 text-end">
                                    <span class="fw-bold text-dark" style="font-size: 0.8125rem;">${{ number_format($checkIn->paid_amount ?? 0, 2) }}</span>
                                </td>
                                <td class="pe-3 py-2.5 text-center">
                                    @if($checkIn->status === 'checked_in')
                                        <span class="badge badge-subtle-emerald rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                            {{ __('Staying') }}
                                        </span>
                                    @elseif($checkIn->status === 'checked_out')
                                        <span class="badge bg-secondary-subtle text-secondary border rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                            {{ __('Checked Out') }}
                                        </span>
                                    @else
                                        <span class="badge bg-light text-secondary border rounded-pill px-2 py-1" style="font-size: 0.7rem;">
                                            {{ ucfirst(str_replace('_', ' ', $checkIn->status ?? 'Active')) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-5 text-center">
                                    <div class="d-flex flex-column align-items-center justify-content-center py-2">
                                        <div class="empty-icon-circle mb-2">
                                            <i class="fa-solid fa-inbox fa-2x text-secondary opacity-60"></i>
                                        </div>
                                        <div class="fw-bold text-dark fs-6 mb-1">{{ __('No Check-Ins Logged Yet') }}</div>
                                        <p class="text-muted small mb-0" style="max-width: 320px;">{{ __('Your front desk log is currently clear.') }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Modern Chart.js 4 UMD Engine -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Live clock ticker
    const timeEl = document.getElementById('live-time-display');
    if (timeEl) {
        setInterval(() => {
            const now = new Date();
            const formatted = now.toLocaleDateString('en-US', { 
                weekday: 'long', 
                day: '2-digit', 
                month: 'short', 
                year: 'numeric' 
            }) + ' — ' + now.toLocaleTimeString('en-US', { hour12: false }) + ' (GMT+7)';
            timeEl.innerHTML = `<i class="fa-solid fa-clock me-1 text-info" style="font-size: 12px;"></i> ${formatted}`;
        }, 1000);
    }

    // Chart.js Global Font Config
    Chart.defaults.font.family = "'Ubuntu', system-ui, -apple-system, sans-serif";

    // 1. Modern Revenue Trend Chart (Spline Area)
    const revenueCanvas = document.getElementById('modernRevenueChart');
    if (revenueCanvas) {
        const trendData = @json($monthlyRevenueTrend);
        const labels = trendData.map(i => i.month);
        const revenues = trendData.map(i => i.revenue);

        const ctx = revenueCanvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 280);
        gradient.addColorStop(0, 'rgba(37, 99, 235, 0.25)');
        gradient.addColorStop(0.7, 'rgba(37, 99, 235, 0.04)');
        gradient.addColorStop(1, 'rgba(37, 99, 235, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Gross Revenue ($)',
                    data: revenues,
                    borderColor: '#2563eb',
                    borderWidth: 2.8,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.38,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#2563eb',
                    pointBorderWidth: 2.5,
                    pointRadius: 4.5,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#2563eb',
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 2.5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 11 },
                        padding: 12,
                        cornerRadius: 8,
                        boxPadding: 4,
                        callbacks: {
                            label: function(ctx) {
                                return ` Revenue: $${Number(ctx.raw).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#64748b',
                            font: { size: 11, weight: '500' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: { size: 11 },
                            callback: function(v) {
                                return '$' + Number(v).toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Room Status Doughnut Chart
    const statusCanvas = document.getElementById('modernStatusChart');
    if (statusCanvas) {
        const roomStatusData = @json($roomStatuses);
        const roomStatusLabels = @json($roomStatusLabels);
        const keys = Object.keys(roomStatusData);
        const values = Object.values(roomStatusData);

        const statusPalette = {
            'Available': '#10b981',
            'Occupied': '#3b82f6',
            'Cleaning': '#f59e0b',
            'Maintenance': '#64748b',
            'Out of Order': '#ef4444',
            'Reserved': '#8b5cf6',
            'ទំនេរ': '#10b981',
            'មានអ្នកស្នាក់នៅ': '#3b82f6',
            'កំពុងសម្អាត': '#f59e0b',
            'កំពុងជួសជុល': '#64748b',
            'មិនអាចប្រើបាន': '#ef4444'
        };

        const bgColors = keys.map(k => statusPalette[k] || '#94a3b8');

        new Chart(statusCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: Object.values(roomStatusLabels),
                datasets: [{
                    data: values.length ? values : [1],
                    backgroundColor: values.length ? bgColors : ['#e2e8f0'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '74%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 11 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(ctx) {
                                return ` ${ctx.label}: ${ctx.raw} units`;
                            }
                        }
                    }
                }
            }
        });
    }

    // 3. 7-Day Front Desk Traffic Flow (Grouped Bar Chart)
    const flowCanvas = document.getElementById('weeklyFlowChart');
    if (flowCanvas) {
        const weeklyData = @json($weeklyFlow);
        const flowDays = weeklyData.map(i => i.day);
        const checkInsData = weeklyData.map(i => i.check_ins);
        const checkOutsData = weeklyData.map(i => i.check_outs);

        new Chart(flowCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: flowDays,
                datasets: [
                    {
                        label: 'Check-Ins (Arrivals)',
                        data: checkInsData,
                        backgroundColor: '#10b981',
                        borderRadius: 6,
                        borderSkipped: false,
                        maxBarThickness: 24,
                    },
                    {
                        label: 'Check-Outs (Departures)',
                        data: checkOutsData,
                        backgroundColor: '#f59e0b',
                        borderRadius: 6,
                        borderSkipped: false,
                        maxBarThickness: 24,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 11 },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: {
                            color: '#64748b',
                            font: { size: 11, weight: '500' }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#64748b',
                            font: { size: 11 }
                        },
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false
                        }
                    }
                }
            }
        });
    }

    // 4. Room Category Distribution Chart (Donut Chart)
    const categoryCanvas = document.getElementById('categoryDistributionChart');
    if (categoryCanvas) {
        @php
            $rtNames = $roomTypesList->pluck('localized_name')->toArray();
            $rtCounts = $roomTypesList->pluck('rooms_count')->toArray();
        @endphp
        const catLabels = @json($rtNames);
        const catCounts = @json($rtCounts);
        const catPalette = ['#2563eb', '#10b981', '#f59e0b', '#8b5cf6', '#ec4899', '#06b6d4', '#64748b'];

        new Chart(categoryCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catCounts.length ? catCounts : [1],
                    backgroundColor: catCounts.length ? catPalette.slice(0, catCounts.length) : ['#e2e8f0'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '72%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 11 },
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(ctx) {
                                return ` ${ctx.label}: ${ctx.raw} rooms`;
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>

<style>
/* Modern Executive Hotel Dashboard Styling */
.modern-dashboard-wrapper {
    background-color: #f8fafc;
    min-height: 100vh;
}

/* 1. Hero Command Banner */
.dashboard-hero {
    background: linear-gradient(135deg, #091a3c 0%, #0f2c69 55%, #1d4ed8 100%);
    border-radius: 12px;
}

.dashboard-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: radial-gradient(circle at 85% 15%, rgba(255, 255, 255, 0.15) 0%, transparent 40%),
                      radial-gradient(circle at 20% 90%, rgba(59, 130, 246, 0.20) 0%, transparent 35%);
    pointer-events: none;
}

.pulse-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    padding: 0.2rem 0.6rem;
    background: rgba(16, 185, 129, 0.2);
    border: 1px solid rgba(16, 185, 129, 0.4);
    border-radius: 9999px;
    color: #6ee7b7;
    font-size: 0.68rem;
    font-weight: 700;
    letter-spacing: 0.5px;
}

.pulse-dot {
    width: 6px;
    height: 6px;
    background-color: #10b981;
    border-radius: 50%;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.4);
    animation: pulseGlow 2s infinite;
}

@keyframes pulseGlow {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.hero-live-clock {
    color: #e2e8f0;
    font-weight: 500;
}

.hero-subtitle {
    color: #cbd5e1 !important;
    max-width: 680px;
    line-height: 1.5;
}

.hero-date-chip {
    display: inline-flex;
    align-items: center;
    gap: 0.4rem;
    padding: 0.35rem 0.75rem;
    background: rgba(255, 255, 255, 0.15);
    border: 1px solid rgba(255, 255, 255, 0.25);
    border-radius: 8px;
    color: #ffffff;
    font-size: 0.78rem;
    font-weight: 600;
}

.btn-hero-glass {
    background: rgba(255, 255, 255, 0.15);
    color: #ffffff;
    border: 1px solid rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(8px);
    transition: all 0.2s ease;
    border-radius: 8px;
    padding: 0.35rem 0.85rem;
    font-weight: 500;
    font-size: 0.78rem;
    display: inline-flex;
    align-items: center;
}

.btn-hero-glass:hover {
    background: rgba(255, 255, 255, 0.28);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.4);
}

/* 2. KPI Cards */
.kpi-card {
    border-radius: 12px;
    background-color: #ffffff;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(15, 23, 42, 0.08) !important;
}

.kpi-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.kpi-value {
    font-size: 1.625rem;
    font-weight: 700;
    color: #0f172a;
    letter-spacing: -0.5px;
}

.kpi-icon-badge {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.kpi-icon-badge i {
    font-size: 17px;
}

.kpi-icon-blue { background-color: #eff6ff; color: #1d4ed8; }
.kpi-icon-emerald { background-color: #ecfdf5; color: #047857; }
.kpi-icon-green { background-color: #f0fdf4; color: #15803d; }
.kpi-icon-purple { background-color: #faf5ff; color: #6d28d9; }

.kpi-progress {
    height: 5px;
    background-color: #f1f5f9;
    border-radius: 9999px;
    overflow: hidden;
}

.bg-gradient-blue { background: linear-gradient(90deg, #3b82f6 0%, #1d4ed8 100%); }
.bg-gradient-purple { background: linear-gradient(90deg, #8b5cf6 0%, #6d28d9 100%); }
.bg-emerald { background-color: #10b981 !important; }
.bg-blue { background-color: #3b82f6 !important; }
.bg-amber { background-color: #f59e0b !important; }

.kpi-subtext {
    font-size: 0.75rem;
    color: #64748b;
}

.dot-indicator {
    display: inline-block;
    width: 6px;
    height: 6px;
    border-radius: 50%;
    margin-right: 2px;
}

/* 3. Section Cards */
.modern-section-card {
    border-radius: 12px;
    background-color: #ffffff;
    overflow: hidden;
}

.section-icon-badge {
    width: 34px;
    height: 34px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.section-icon-badge i {
    font-size: 15px;
}

/* 4. Chart Elements */
.chart-center-metric {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    pointer-events: none;
}

.status-legend-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.35rem 0.5rem;
    border-radius: 6px;
    background-color: #f8fafc;
    border: 1px solid #f1f5f9;
}

.legend-color-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* 5. Modern Table */
.modern-table thead th {
    background-color: #f8fafc;
    color: #475569;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
    padding: 0.75rem 1rem;
}

.modern-table tbody td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f1f5f9;
}

.avatar-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background-color: #f1f5f9;
    color: #475569;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.booking-tag {
    font-size: 0.68rem;
    color: #64748b;
    font-family: monospace;
}

.empty-icon-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background-color: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-blue-subtle { background-color: #eff6ff; }
.bg-emerald-subtle { background-color: #ecfdf5; }
.text-emerald { color: #059669 !important; }
.border-emerald-subtle { border-color: #a7f3d0 !important; }

.bg-amber-subtle { background-color: #fffbeb; }
.text-amber { color: #d97706 !important; }

.bg-purple-subtle { background-color: #faf5ff; }
.text-purple { color: #7c3aed !important; }
.border-purple-subtle { border-color: #e9d5ff !important; }

.bg-indigo-subtle { background-color: #eef2ff; }
.text-indigo { color: #4338ca !important; }
.border-indigo-subtle { border-color: #c7d2fe !important; }

.bg-slate-50 { background-color: #f8fafc; }
.bg-slate-100 { background-color: #f1f5f9; }
</style>
</x-app-layout>
