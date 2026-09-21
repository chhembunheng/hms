<x-page-index 
    :title="__('reports.guest_report')" 
    icon="users">

    <x-slot:actions>
        <a href="{{ route('reports.guest.print', ['start_date' => format_date($startDate), 'end_date' => format_date($endDate)]) }}" target="_blank" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 rounded-2 px-3">
            <i data-lucide="printer" style="width: 14px; height: 14px;"></i>
            <span>{{ __('reports.print') }}</span>
        </a>
    </x-slot:actions>

    <x-slot:filters>
        <!-- Date Range Filter Form -->
        <form method="GET" action="{{ route('reports.guest.index') }}" class="row g-2 align-items-end mb-3 px-1">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('global.start_date') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="start_date" id="start_date" value="{{ format_date($startDate) }}" class="form-control form-control-sm rounded-start-2 pickadate" placeholder="dd-mm-yyyy" autocomplete="off">
                    <span class="input-group-text rounded-end-2"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('global.end_date') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="end_date" id="end_date" value="{{ format_date($endDate) }}" class="form-control form-control-sm rounded-start-2 pickadate" placeholder="dd-mm-yyyy" autocomplete="off">
                    <span class="input-group-text rounded-end-2"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                    <i data-lucide="filter" style="width: 13px; height: 13px;"></i>
                    <span>{{ __('global.filter') }}</span>
                </button>
                <a href="{{ route('reports.guest.index') }}" class="btn btn-sm btn-outline-secondary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                    <i data-lucide="rotate-ccw" style="width: 13px; height: 13px;"></i>
                    <span>{{ __('global.clear') }}</span>
                </a>
            </div>
        </form>

        <!-- KPI Metric Cards -->
        <div class="row g-2 px-1">
            <div class="col-md-4">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="users" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('guests.total_guests') ?? 'Total Guests' }}</div>
                        <div class="fw-bold text-primary fs-5">{{ $totalGuests }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="bookmark-check" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('Total Bookings') ?? 'Total Bookings' }}</div>
                        <div class="fw-bold text-success fs-5">{{ $totalBookings }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="dollar-sign" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('reports.total_revenue') }}</div>
                        <div class="fw-bold text-info fs-5">${{ number_format($totalRevenue, 2) }}</div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:filters>

    <!-- Guest Statistics Breakdowns -->
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="border rounded-2 overflow-hidden bg-white">
                <div class="py-2 px-3 border-bottom bg-light d-flex align-items-center gap-2">
                    <i data-lucide="globe" style="width: 16px; height: 16px;" class="text-primary"></i>
                    <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Nationalities') }}</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b;">
                                <th class="ps-3">{{ __('Country / Nationality') }}</th>
                                <th>{{ __('Qty') }}</th>
                                <th class="text-end pe-3">{{ __('reports.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($nationalityStats as $stat)
                                <tr>
                                    <td class="ps-3 fw-medium text-dark">{{ $stat['country'] ?: __('global.unknown') }}</td>
                                    <td>{{ $stat['count'] }}</td>
                                    <td class="text-end pe-3 fw-bold text-dark">${{ number_format($stat['revenue'], 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted small">{{ __('global.no_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="border rounded-2 overflow-hidden bg-white">
                <div class="py-2 px-3 border-bottom bg-light d-flex align-items-center gap-2">
                    <i data-lucide="user-check" style="width: 16px; height: 16px;" class="text-success"></i>
                    <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('rooms.guest_type') }}</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b;">
                                <th class="ps-3">{{ __('Type') }}</th>
                                <th>{{ __('Qty') }}</th>
                                <th class="text-end pe-3">{{ __('reports.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($guestTypeStats as $type => $stat)
                                <tr>
                                    <td class="ps-3 fw-medium text-dark text-capitalize">{{ $type ?: __('global.unknown') }}</td>
                                    <td>{{ $stat['count'] }}</td>
                                    <td class="text-end pe-3 fw-bold text-dark">${{ number_format($stat['revenue'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Guest List Table -->
    <div class="border rounded-2 overflow-hidden bg-white">
        <div class="py-2 px-3 border-bottom bg-light d-flex align-items-center gap-2">
            <i data-lucide="users" style="width: 16px; height: 16px;" class="text-info"></i>
            <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('guests.guests') }}</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatables-custom">
                <thead class="table-light">
                    <tr class="text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b;">
                        <th class="ps-3">{{ __('reports.guest_name') }}</th>
                        <th>{{ __('form.email') }}</th>
                        <th>{{ __('form.phone') }}</th>
                        <th>{{ __('Country / Nationality') }}</th>
                        <th>{{ __('Total Bookings') ?? 'Total Bookings' }}</th>
                        <th class="text-end pe-3">{{ __('reports.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($guests as $guest)
                        <tr>
                            <td class="ps-3 fw-semibold text-dark">{{ $guest->name }}</td>
                            <td class="text-muted small">{{ $guest->email ?: '—' }}</td>
                            <td class="text-muted small">{{ $guest->phone ?: '—' }}</td>
                            <td>
                                <span class="badge bg-light text-dark border rounded-pill px-2 py-1">
                                    {{ $guest->country ?: __('global.unknown') }}
                                </span>
                            </td>
                            <td>{{ $guest->checkIns->count() }}</td>
                            <td class="text-end pe-3 fw-bold text-dark">${{ number_format($guest->checkIns->sum('paid_amount'), 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted small">{{ __('global.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-page-index>
