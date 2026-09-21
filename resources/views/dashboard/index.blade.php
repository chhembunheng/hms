<x-app-layout>
<div class="container-fluid py-3 px-3 px-xl-4 modern-dashboard-wrapper">

    <!-- 1. Executive Command Hero Banner -->
    <div class="dashboard-hero card border-0 shadow-sm mb-4 position-relative overflow-hidden">
        <div class="dashboard-hero-bg"></div>
        <div class="card-body p-4 position-relative z-1">
            <div class="row align-items-center g-3">
                <div class="col-lg-7">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="pulse-badge">
                            <span class="pulse-dot"></span>
                            {{ __('PMS CORE ACTIVE') }}
                        </span>
                        <span class="text-white-50 small">•</span>
                        <span class="text-white-50 small" id="live-time-display">
                            <i data-lucide="clock" style="width: 12px; height: 12px;" class="me-1"></i>
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
                        {{ $greeting }}, {{ Auth::user()?->name ?? 'Front Desk Manager' }}
                    </h2>
                    <p class="text-white-50 mb-0 small" style="max-width: 580px;">
                        {{ __('dashboard.live_hotel_operations') }} — {{ __('Manage real-time room inventory, guest registrations, and revenue telemetry from this command center.') }}
                    </p>
                </div>

                <div class="col-lg-5 text-lg-end">
                    <div class="d-flex flex-wrap align-items-center justify-content-lg-end gap-2">
                        <button type="button" class="btn btn-hero-glass btn-sm" onclick="window.location.reload()" title="{{ __('Refresh') }}">
                            <i data-lucide="rotate-cw" style="width: 14px; height: 14px;"></i>
                        </button>

                        <a href="{{ route('reports.daily.index') }}" class="btn btn-hero-glass btn-sm">
                            <i data-lucide="file-text" style="width: 14px; height: 14px;"></i>
                            <span>{{ __('Daily Report') }}</span>
                        </a>

                        <a href="{{ route('checkin.staying.index') }}" class="btn btn-hero-glass btn-sm">
                            <i data-lucide="bed-double" style="width: 14px; height: 14px;"></i>
                            <span>{{ __('Staying') }} ({{ $activeGuests }})</span>
                        </a>

                        <a href="{{ route('checkin.walkin.add') }}" class="btn btn-hero-primary btn-sm">
                            <i data-lucide="plus" style="width: 15px; height: 15px;"></i>
                            <span>{{ __('checkins.new_check_in') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. Modern 4-KPI Metric Cards Row -->
    <div class="row g-3 mb-4">
        <!-- KPI 1: Live Occupancy -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card card border-0 shadow-sm h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="kpi-label">{{ __('dashboard.occupancy_rate') }}</div>
                        <div class="kpi-icon-badge kpi-icon-blue">
                            <i data-lucide="bed-double" style="width: 18px; height: 18px;"></i>
                        </div>
                    </div>

                    <div class="d-flex align-items-baseline gap-2 mb-2">
                        <h3 class="kpi-value mb-0">{{ $occupancyRate }}<span class="fs-6 fw-semibold text-muted">%</span></h3>
                        <span class="badge bg-emerald-subtle text-emerald border border-emerald-subtle rounded-pill px-2 py-0.5" style="font-size: 0.72rem;">
                            {{ $availableRooms }} {{ __('Rooms Free') }}
                        </span>
                    </div>

                    <div class="progress kpi-progress mb-2">
                        <div class="progress-bar bg-gradient-blue" role="progressbar" style="width: {{ max(5, $occupancyRate) }}%;"></div>
                    </div>

                    <div class="d-flex justify-content-between kpi-subtext">
                        <span><strong>{{ $occupiedRooms }}</strong> / {{ $totalRooms }} {{ __('dashboard.occupied') }}</span>
                        <span>{{ $totalFloors }} {{ __('Floors') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- KPI 2: Front Desk Velocity -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card card border-0 shadow-sm h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="kpi-label">{{ __("Today's Flow") }}</div>
                        <div class="kpi-icon-badge kpi-icon-emerald">
                            <i data-lucide="arrow-left-right" style="width: 18px; height: 18px;"></i>
                        </div>
                    </div>

                    <div class="d-flex align-items-baseline gap-2 mb-2">
                        <h3 class="kpi-value mb-0">{{ $todayCheckIns }} <span class="fs-6 fw-normal text-muted">In</span></h3>
                        <span class="text-muted fw-bold">/</span>
                        <h4 class="kpi-value mb-0 text-secondary fs-4">{{ $todayCheckOuts }} <span class="fs-6 fw-normal text-muted">Out</span></h4>
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
                        <span><span class="dot-indicator bg-emerald"></span> {{ $todayCheckIns }} Check-Ins</span>
                        <span><span class="dot-indicator bg-warning"></span> {{ $todayCheckOuts }} Check-Outs</span>
                        <span class="fw-semibold text-dark">{{ $activeGuests }} In-House</span>
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
                            <i data-lucide="circle-dollar-sign" style="width: 18px; height: 18px;"></i>
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

        <!-- KPI 4: Monthly Gross Revenue -->
        <div class="col-xl-3 col-sm-6">
            <div class="kpi-card card border-0 shadow-sm h-100">
                <div class="card-body p-3.5">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div class="kpi-label">{{ __('Monthly Gross Revenue') }}</div>
                        <div class="kpi-icon-badge kpi-icon-purple">
                            <i data-lucide="trending-up" style="width: 18px; height: 18px;"></i>
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
                        <span>{{ __('Last Month') }}: ${{ number_format($lastMonthRevenue, 2) }}</span>
                        @if($lastMonthRevenue > 0 && $monthlyRevenue >= $lastMonthRevenue)
                            <span class="text-success fw-bold d-inline-flex align-items-center">
                                <i data-lucide="arrow-up-right" style="width: 12px; height: 12px;"></i>
                                +{{ number_format((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) }}%
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Visual Interactive Room Rack Matrix (Live Floorplan) -->
    <div class="card modern-section-card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom py-3 px-3 px-md-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="section-icon-badge">
                        <i data-lucide="layout-grid" style="width: 17px; height: 17px;"></i>
                    </div>
                    <div>
                        <h5 class="mb-0 fw-bold text-dark fs-6 d-flex align-items-center gap-2">
                            {{ __('Live Room Rack & Inventory Matrix') }}
                            <span class="badge bg-slate-100 text-secondary border rounded-pill px-2" style="font-size: 0.72rem;">
                                {{ $allRooms->count() }} {{ __('Rooms') }}
                            </span>
                        </h5>
                        <div class="text-muted small" style="font-size: 0.75rem;">
                            {{ __('Real-time visual room status. Click any available room to initiate an instant walk-in check-in.') }}
                        </div>
                    </div>
                </div>

                <!-- Status Filter Pills -->
                <div class="d-flex flex-wrap align-items-center gap-1.5" id="room-rack-filter-pills">
                    <button type="button" class="btn btn-filter-pill active" data-filter="all">
                        {{ __('All') }} ({{ $allRooms->count() }})
                    </button>
                    <button type="button" class="btn btn-filter-pill filter-available" data-filter="available">
                        <span class="status-dot bg-emerald"></span>
                        {{ __('Available') }} ({{ $availableRooms }})
                    </button>
                    <button type="button" class="btn btn-filter-pill filter-occupied" data-filter="occupied">
                        <span class="status-dot bg-blue"></span>
                        {{ __('Occupied') }} ({{ $occupiedRooms }})
                    </button>
                    <button type="button" class="btn btn-filter-pill filter-other" data-filter="other">
                        <span class="status-dot bg-amber"></span>
                        {{ __('Maintenance/Clean') }} ({{ max(0, $totalRooms - ($availableRooms + $occupiedRooms)) }})
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-3 p-md-4 bg-slate-50">
            <!-- Room Matrix Grid -->
            <div class="row g-2.5" id="room-rack-grid">
                @forelse($allRooms as $room)
                    @php
                        $statusSlug = strtolower(str_replace(' ', '_', $room->status?->name_en ?? 'available'));
                        $isAvailable = str_contains($statusSlug, 'available') || $statusSlug === 'ទំនេរ';
                        $isOccupied = str_contains($statusSlug, 'occupied') || $statusSlug === 'មានអ្នកស្នាក់នៅ';
                        $filterCategory = $isAvailable ? 'available' : ($isOccupied ? 'occupied' : 'other');
                        $activeStay = $room->checkIns->first();
                    @endphp

                    <div class="col-6 col-sm-4 col-md-3 col-lg-2 col-xl-custom room-tile-col" data-status="{{ $filterCategory }}">
                        <div class="room-rack-tile {{ $filterCategory }} position-relative">
                            <!-- Status Color Bar -->
                            <div class="room-tile-status-bar"></div>

                            <div class="p-2.5">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="room-number fw-bold text-dark">{{ $room->room_number }}</span>
                                    @if($isAvailable)
                                        <span class="badge badge-subtle-emerald rounded-pill px-1.5 py-0.5" style="font-size: 0.65rem;">
                                            <span class="d-inline-block rounded-circle bg-emerald me-1" style="width: 5px; height: 5px;"></span>
                                            {{ __('Free') }}
                                        </span>
                                    @elseif($isOccupied)
                                        <span class="badge badge-subtle-blue rounded-pill px-1.5 py-0.5" style="font-size: 0.65rem;">
                                            <span class="d-inline-block rounded-circle bg-primary me-1" style="width: 5px; height: 5px;"></span>
                                            {{ __('Stay') }}
                                        </span>
                                    @else
                                        <span class="badge badge-subtle-amber rounded-pill px-1.5 py-0.5" style="font-size: 0.65rem;">
                                            {{ $room->status?->name_en ?? 'Service' }}
                                        </span>
                                    @endif
                                </div>

                                <div class="room-type-label text-truncate text-muted mb-2" style="font-size: 0.72rem;">
                                    {{ $room->roomType?->localized_name ?? 'Standard' }}
                                </div>

                                <!-- Action / Guest Info -->
                                @if($isAvailable)
                                    <a href="{{ route('checkin.walkin.add', ['room_id' => $room->id]) }}" class="btn btn-room-action btn-room-available w-100 text-decoration-none">
                                        <i data-lucide="user-plus" style="width: 12px; height: 12px;"></i>
                                        <span>{{ __('Check In') }}</span>
                                    </a>
                                @elseif($isOccupied && $activeStay)
                                    <div class="guest-stay-chip text-truncate" title="{{ $activeStay->guest_name }}">
                                        <i data-lucide="user" style="width: 11px; height: 11px;" class="me-1"></i>
                                        <span>{{ $activeStay->guest_name }}</span>
                                    </div>
                                @else
                                    <a href="{{ route('rooms.list.index') }}" class="btn btn-room-action btn-room-secondary w-100 text-decoration-none">
                                        <span>{{ __('View') }}</span>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 py-4 text-center text-muted">
                        {{ __('No rooms configured yet.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 4. Deep Telemetry & Visual Analytics Section -->
    <div class="row g-3 mb-4">
        <!-- 6-Month Revenue Trajectory -->
        <div class="col-lg-8">
            <div class="card modern-section-card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="section-icon-badge">
                            <i data-lucide="line-chart" style="width: 17px; height: 17px;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Revenue Trajectory & Invoicing Flow') }}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ __('6-Month comparative financial telemetry') }}</small>
                        </div>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-indigo-subtle text-indigo border border-indigo-subtle rounded-pill px-2.5 py-1" style="font-size: 0.72rem;">
                            <i data-lucide="circle-dot" style="width: 10px; height: 10px;" class="me-1"></i>
                            {{ __('USD Currency') }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-3.5">
                    <div style="height: 270px; position: relative;">
                        <canvas id="modernRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Room Status & Health Breakdown -->
        <div class="col-lg-4">
            <div class="card modern-section-card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="section-icon-badge">
                            <i data-lucide="pie-chart" style="width: 17px; height: 17px;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Inventory Health') }}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $totalRooms }} {{ __('Total PMS Units') }}</small>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3.5 d-flex flex-column justify-content-between">
                    <div style="height: 180px; position: relative;" class="my-auto">
                        <canvas id="modernStatusChart"></canvas>
                        <!-- Center Donut Stat -->
                        <div class="chart-center-metric">
                            <div class="fs-4 fw-bold text-dark lh-1">{{ $totalRooms }}</div>
                            <small class="text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Rooms</small>
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

    <!-- 5. Front Desk Arrivals / Activity & Category Inventory -->
    <div class="row g-3">
        <!-- Front Desk Registrations Table -->
        <div class="col-lg-7">
            <div class="card modern-section-card border-0 shadow-sm h-100">
                <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <div class="section-icon-badge">
                            <i data-lucide="history" style="width: 17px; height: 17px;"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Recent Front Desk Registrations') }}</h6>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ __('Real-time guest check-ins and arrivals') }}</small>
                        </div>
                    </div>

                    <a href="{{ route('checkin.staying.index') }}" class="btn btn-outline-secondary btn-sm rounded-2 d-inline-flex align-items-center gap-1 px-2.5 py-1" style="font-size: 0.75rem;">
                        <span>{{ __('dashboard.view_all_checkins') }}</span>
                        <i data-lucide="chevron-right" style="width: 12px; height: 12px;"></i>
                    </a>
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
                                                    <i data-lucide="user" style="width: 13px; height: 13px;"></i>
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
                                                    <i data-lucide="inbox" style="width: 24px; height: 24px;" class="text-secondary opacity-60"></i>
                                                </div>
                                                <div class="fw-bold text-dark fs-6 mb-1">{{ __('No Check-Ins Logged Yet') }}</div>
                                                <p class="text-muted small mb-3" style="max-width: 320px;">{{ __('Your front desk queue is currently clear. Register new guest arrivals using the quick walk-in button.') }}</p>
                                                <a href="{{ route('checkin.walkin.add') }}" class="btn btn-primary btn-sm rounded-2 d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-medium">
                                                    <i data-lucide="plus" style="width: 14px; height: 14px;"></i>
                                                    <span>{{ __('checkins.new_check_in') }}</span>
                                                </a>
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

        <!-- Operations Hub & Room Category Inventory -->
        <div class="col-lg-5">
            <div class="d-flex flex-column gap-3 h-100">
                <!-- Quick Operations Hub -->
                <div class="card modern-section-card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom py-3 px-3 px-md-4">
                        <div class="d-flex align-items-center gap-2">
                            <div class="section-icon-badge">
                                <i data-lucide="zap" style="width: 17px; height: 17px;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Quick PMS Operations') }}</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ __('Instant 1-click front desk shortcuts') }}</small>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <a href="{{ route('checkin.walkin.add') }}" class="operation-shortcut-tile d-flex align-items-center gap-2.5 p-2.5 rounded-3 text-decoration-none">
                                    <div class="shortcut-icon-badge bg-blue-subtle text-primary">
                                        <i data-lucide="user-plus" style="width: 16px; height: 16px;"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <div class="fw-bold text-dark" style="font-size: 0.8125rem;">{{ __('New Walk-In') }}</div>
                                        <small class="text-muted d-block" style="font-size: 0.68rem;">{{ __('Fast guest check-in') }}</small>
                                    </div>
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="{{ route('checkin.staying.index') }}" class="operation-shortcut-tile d-flex align-items-center gap-2.5 p-2.5 rounded-3 text-decoration-none">
                                    <div class="shortcut-icon-badge bg-emerald-subtle text-emerald">
                                        <i data-lucide="bed-double" style="width: 16px; height: 16px;"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <div class="fw-bold text-dark" style="font-size: 0.8125rem;">{{ __('In-House') }}</div>
                                        <small class="text-muted d-block" style="font-size: 0.68rem;">{{ $activeGuests }} {{ __('staying now') }}</small>
                                    </div>
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="{{ route('billing.list.index') }}" class="operation-shortcut-tile d-flex align-items-center gap-2.5 p-2.5 rounded-3 text-decoration-none">
                                    <div class="shortcut-icon-badge bg-amber-subtle text-amber">
                                        <i data-lucide="receipt" style="width: 16px; height: 16px;"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <div class="fw-bold text-dark" style="font-size: 0.8125rem;">{{ __('Billing & Folio') }}</div>
                                        <small class="text-muted d-block" style="font-size: 0.68rem;">{{ __('Invoices & payments') }}</small>
                                    </div>
                                </a>
                            </div>

                            <div class="col-6">
                                <a href="{{ route('settings.system-configuration.index') }}" class="operation-shortcut-tile d-flex align-items-center gap-2.5 p-2.5 rounded-3 text-decoration-none">
                                    <div class="shortcut-icon-badge bg-purple-subtle text-purple">
                                        <i data-lucide="sliders" style="width: 16px; height: 16px;"></i>
                                    </div>
                                    <div class="text-truncate">
                                        <div class="fw-bold text-dark" style="font-size: 0.8125rem;">{{ __('System Config') }}</div>
                                        <small class="text-muted d-block" style="font-size: 0.68rem;">{{ __('Hotel parameters') }}</small>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Room Category Inventory -->
                <div class="card modern-section-card border-0 shadow-sm flex-grow-1">
                    <div class="card-header bg-white border-bottom py-3 px-3 px-md-4 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <div class="section-icon-badge">
                                <i data-lucide="layers" style="width: 17px; height: 17px;"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Room Category Inventory') }}</h6>
                                <small class="text-muted" style="font-size: 0.75rem;">{{ __('Distribution by room specification') }}</small>
                            </div>
                        </div>

                        <a href="{{ route('rooms.type.index') }}" class="btn btn-outline-secondary btn-sm rounded-2 d-inline-flex align-items-center gap-1 px-2.5 py-1" style="font-size: 0.75rem;">
                            <span>{{ __('Manage') }}</span>
                            <i data-lucide="chevron-right" style="width: 12px; height: 12px;"></i>
                        </a>
                    </div>

                    <div class="card-body p-3.5">
                        <div class="d-flex flex-column gap-3">
                            @if(isset($roomTypesList) && $roomTypesList->count() > 0)
                                @foreach($roomTypesList as $rt)
                                    @php
                                        $rtPct = $totalRooms > 0 ? round(($rt->rooms_count / $totalRooms) * 100) : 0;
                                    @endphp
                                    <div>
                                        <div class="d-flex justify-content-between align-items-center mb-1.5" style="font-size: 0.78rem;">
                                            <span class="fw-semibold text-dark">{{ $rt->localized_name }}</span>
                                            <span class="text-muted">
                                                <strong class="text-dark">{{ $rt->rooms_count }}</strong> {{ __('Rooms') }}
                                                <span class="badge bg-light text-secondary border rounded px-1.5 py-0.5 ms-1" style="font-size: 0.68rem;">{{ $rtPct }}%</span>
                                            </span>
                                        </div>
                                        <div class="progress" style="height: 6px; background-color: #f1f5f9; border-radius: 4px;">
                                            <div class="progress-bar bg-gradient-blue" style="width: {{ max(4, $rtPct) }}%;"></div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center text-muted py-3 small">
                                    {{ __('No room categories configured.') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Modern Chart.js 4 UMD Engine -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    if (typeof window.initLucideIcons === 'function') {
        window.initLucideIcons();
    }

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
            timeEl.innerHTML = `<i data-lucide="clock" style="width: 12px; height: 12px;" class="me-1"></i> ${formatted}`;
            if (typeof lucide !== 'undefined') lucide.createIcons({ root: timeEl });
        }, 1000);
    }

    // Room Rack Filter Tabs
    const filterButtons = document.querySelectorAll('#room-rack-filter-pills button');
    const roomCols = document.querySelectorAll('.room-tile-col');

    filterButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            filterButtons.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            const targetFilter = this.getAttribute('data-filter');

            roomCols.forEach(col => {
                if (targetFilter === 'all' || col.getAttribute('data-status') === targetFilter) {
                    col.style.display = 'block';
                } else {
                    col.style.display = 'none';
                }
            });
        });
    });

    // 1. Modern Revenue Trend Chart
    const revenueCanvas = document.getElementById('modernRevenueChart');
    if (revenueCanvas) {
        const trendData = @json($monthlyRevenueTrend);
        const labels = trendData.map(i => i.month);
        const revenues = trendData.map(i => i.revenue);

        const ctx = revenueCanvas.getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 260);
        gradient.addColorStop(0, 'rgba(25, 63, 143, 0.25)');
        gradient.addColorStop(0.7, 'rgba(25, 63, 143, 0.04)');
        gradient.addColorStop(1, 'rgba(25, 63, 143, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Gross Revenue',
                    data: revenues,
                    borderColor: '#193f8f',
                    borderWidth: 2.8,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.38,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#193f8f',
                    pointBorderWidth: 2.5,
                    pointRadius: 4.5,
                    pointHoverRadius: 7,
                    pointHoverBackgroundColor: '#193f8f',
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

    // 2. Modern Status Doughnut Chart
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
});
</script>

<style>
/* Modern Dashboard Styling Engine */
.modern-dashboard-wrapper {
    background-color: #f8fafc;
    min-height: 100vh;
}

/* 1. Hero Command Banner */
.dashboard-hero {
    background: linear-gradient(135deg, #091a3c 0%, #15387d 55%, #1d4ed8 100%);
    border-radius: 12px;
}

.dashboard-hero-bg {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: radial-gradient(circle at 85% 15%, rgba(255, 255, 255, 0.12) 0%, transparent 40%),
                      radial-gradient(circle at 20% 90%, rgba(59, 130, 246, 0.15) 0%, transparent 35%);
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
    animation: pulseGlow 1.8s infinite;
}

@keyframes pulseGlow {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
    70% { box-shadow: 0 0 0 6px rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
}

.btn-hero-glass {
    background: rgba(255, 255, 255, 0.12);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #ffffff;
    backdrop-filter: blur(8px);
    border-radius: 8px;
    padding: 0.4rem 0.8rem;
    font-size: 0.8125rem;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    transition: all 0.18s ease;
}

.btn-hero-glass:hover {
    background: rgba(255, 255, 255, 0.22);
    color: #ffffff;
    border-color: rgba(255, 255, 255, 0.35);
    transform: translateY(-1px);
}

.btn-hero-primary {
    background: #ffffff;
    color: #193f8f;
    border: 1px solid #ffffff;
    border-radius: 8px;
    padding: 0.4rem 1rem;
    font-size: 0.8125rem;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 0.375rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    transition: all 0.18s ease;
}

.btn-hero-primary:hover {
    background: #f1f5f9;
    color: #112d6a;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

/* 2. KPI Cards */
.kpi-card {
    background: #ffffff;
    border-radius: 10px;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid #eef2f6 !important;
}

.kpi-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(15, 23, 42, 0.08) !important;
}

.kpi-label {
    font-size: 0.78rem;
    font-weight: 600;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.kpi-value {
    font-size: 1.65rem;
    font-weight: 800;
    color: #0f172a;
    line-height: 1.15;
    letter-spacing: -0.5px;
}

.kpi-progress {
    height: 5px;
    background-color: #f1f5f9;
    border-radius: 4px;
}

.kpi-subtext {
    font-size: 0.75rem;
    color: #64748b;
}

.kpi-icon-badge {
    width: 36px;
    height: 36px;
    border-radius: 9px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.kpi-icon-blue { background-color: #eff6ff; color: #2563eb; }
.kpi-icon-emerald { background-color: #ecfdf5; color: #059669; }
.kpi-icon-green { background-color: #f0fdf4; color: #16a34a; }
.kpi-icon-purple { background-color: #faf5ff; color: #7c3aed; }

.bg-gradient-blue { background: linear-gradient(90deg, #193f8f, #3b82f6); }
.bg-gradient-purple { background: linear-gradient(90deg, #7c3aed, #a855f7); }
.bg-emerald { background-color: #10b981; }
.text-emerald { color: #059669 !important; }
.bg-emerald-subtle { background-color: #ecfdf5 !important; }
.border-emerald-subtle { border-color: #a7f3d0 !important; }

.text-indigo { color: #4338ca !important; }
.bg-indigo-subtle { background-color: #e0e7ff !important; }
.border-indigo-subtle { border-color: #c7d2fe !important; }

.text-purple { color: #7c3aed !important; }
.bg-purple-subtle { background-color: #faf5ff !important; }
.border-purple-subtle { border-color: #e9d5ff !important; }

.dot-indicator {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
    margin-right: 2px;
}

/* 3. Room Rack Matrix Tiles */
.modern-section-card {
    background: #ffffff;
    border-radius: 11px;
    border: 1px solid #eef2f6 !important;
}

.section-icon-badge {
    width: 34px;
    height: 34px;
    background-color: #edf3fc;
    color: #193f8f;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.btn-filter-pill {
    padding: 0.28rem 0.75rem;
    font-size: 0.75rem;
    font-weight: 600;
    color: #64748b;
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 9999px;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    transition: all 0.15s ease;
}

.btn-filter-pill:hover {
    background-color: #f1f5f9;
    color: #0f172a;
}

.btn-filter-pill.active {
    background-color: #193f8f;
    color: #ffffff;
    border-color: #193f8f;
    box-shadow: 0 2px 6px rgba(25, 63, 143, 0.25);
}

.btn-filter-pill .status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
}

.col-xl-custom {
    flex: 0 0 auto;
    width: 14.285%;
}

@media (max-width: 1399px) {
    .col-xl-custom { width: 16.666%; }
}
@media (max-width: 1199px) {
    .col-xl-custom { width: 20%; }
}
@media (max-width: 991px) {
    .col-xl-custom { width: 25%; }
}
@media (max-width: 767px) {
    .col-xl-custom { width: 33.333%; }
}
@media (max-width: 575px) {
    .col-xl-custom { width: 50%; }
}

.room-rack-tile {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 9px;
    overflow: hidden;
    transition: transform 0.18s ease, box-shadow 0.18s ease, border-color 0.18s ease;
}

.room-rack-tile:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(15, 23, 42, 0.08);
}

.room-rack-tile.available {
    border-color: #d1fae5;
}

.room-rack-tile.available .room-tile-status-bar {
    height: 3px;
    background: #10b981;
}

.room-rack-tile.occupied {
    border-color: #dbeafe;
}

.room-rack-tile.occupied .room-tile-status-bar {
    height: 3px;
    background: #3b82f6;
}

.room-rack-tile.other .room-tile-status-bar {
    height: 3px;
    background: #f59e0b;
}

.room-number {
    font-size: 0.95rem;
    font-feature-settings: "tnum";
}

.btn-room-action {
    padding: 0.22rem 0.5rem;
    font-size: 0.7rem;
    font-weight: 600;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.3rem;
    transition: all 0.15s ease;
}

.btn-room-available {
    background-color: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.btn-room-available:hover {
    background-color: #10b981;
    color: #ffffff;
    border-color: #10b981;
}

.btn-room-secondary {
    background-color: #f8fafc;
    color: #475569;
    border: 1px solid #e2e8f0;
}

.btn-room-secondary:hover {
    background-color: #f1f5f9;
    color: #0f172a;
}

.guest-stay-chip {
    background-color: #eff6ff;
    color: #1e40af;
    border: 1px solid #bfdbfe;
    border-radius: 6px;
    padding: 0.2rem 0.4rem;
    font-size: 0.68rem;
    font-weight: 600;
    display: flex;
    align-items: center;
}

.badge-subtle-emerald {
    background-color: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.badge-subtle-blue {
    background-color: #eff6ff;
    color: #2563eb;
    border: 1px solid #bfdbfe;
}

.badge-subtle-amber {
    background-color: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
}

/* 4. Telemetry & Charts */
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

/* 5. Modern Table & Shortcuts */
.modern-table thead th {
    background-color: #f8fafc;
    color: #475569;
    font-size: 0.72rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 1px solid #e2e8f0;
    padding: 0.65rem 0.75rem;
}

.modern-table tbody td {
    padding: 0.65rem 0.75rem;
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

.operation-shortcut-tile {
    background-color: #f8fafc;
    border: 1px solid #e2e8f0;
    transition: all 0.18s ease;
}

.operation-shortcut-tile:hover {
    background-color: #ffffff;
    border-color: #cbd5e1;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.06);
}

.shortcut-icon-badge {
    width: 32px;
    height: 32px;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.bg-blue-subtle { background-color: #eff6ff; }
.bg-amber-subtle { background-color: #fffbeb; }
.text-amber { color: #d97706 !important; }
</style>
</x-app-layout>
