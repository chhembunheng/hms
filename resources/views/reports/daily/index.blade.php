<x-page-index 
    :title="__('reports.daily_report')" 
    icon="calendar">

    <x-slot:actions>
        <a href="{{ route('reports.daily.print', ['date' => format_date($date)]) }}" target="_blank" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 rounded-2 px-3">
            <i data-lucide="printer" style="width: 14px; height: 14px;"></i>
            <span>{{ __('reports.print') }}</span>
        </a>
    </x-slot:actions>

    <x-slot:filters>
        <!-- Date Filter Form -->
        <div class="row g-2 align-items-center mb-3 px-1">
            <div class="col-md-4">
                <form method="GET" class="d-flex align-items-center gap-2">
                    <div class="input-group input-group-sm">
                        <input type="text" name="date" value="{{ format_date($date) }}" class="form-control form-control-sm rounded-start-2 pickadate" placeholder="dd-mm-yyyy" autocomplete="off" onchange="this.form.submit()">
                        <span class="input-group-text rounded-end-2"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                        <i data-lucide="filter" style="width: 13px; height: 13px;"></i>
                        <span>{{ __('global.filter') }}</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- KPI Metric Cards -->
        <div class="row g-2 px-1">
            <div class="col-md-3">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="log-in" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('reports.total_check_ins') }}</div>
                        <div class="fw-bold text-primary fs-5">{{ $totalCheckIns }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="log-out" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('reports.total_check_outs') }}</div>
                        <div class="fw-bold text-success fs-5">{{ $totalCheckOuts }}</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
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
            <div class="col-md-3">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="users" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('reports.staying_guests') }}</div>
                        <div class="fw-bold text-warning fs-5">{{ $stayingGuests->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot:filters>

    <!-- Check-ins and Check-outs Section -->
    <div class="row g-3">
        <div class="col-md-6">
            <div class="border rounded-2 overflow-hidden bg-white">
                <div class="py-2 px-3 border-bottom bg-light d-flex align-items-center gap-2">
                    <i data-lucide="log-in" style="width: 16px; height: 16px;" class="text-primary"></i>
                    <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('checkins.today_check_in') ?? __('Today Check-ins') }}</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b;">
                                <th class="ps-3">{{ __('reports.guest_name') }}</th>
                                <th>{{ __('reports.room_number') }}</th>
                                <th>{{ __('Date & Time') }}</th>
                                <th class="text-end pe-3">{{ __('reports.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkIns as $checkIn)
                                <tr>
                                    <td class="ps-3 fw-medium text-dark">{{ $checkIn->guest_name }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-2 px-2 py-1">
                                            {{ $checkIn->room->room_number ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ $checkIn->check_in_time ? $checkIn->check_in_time->format('H:i') : 'N/A' }}</td>
                                    <td class="text-end pe-3 fw-bold text-dark">${{ number_format($checkIn->paid_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">{{ __('global.no_data') }}</td>
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
                    <i data-lucide="log-out" style="width: 16px; height: 16px;" class="text-success"></i>
                    <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('checkins.today_check_out') ?? __('Today Check-outs') }}</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b;">
                                <th class="ps-3">{{ __('reports.guest_name') }}</th>
                                <th>{{ __('reports.room_number') }}</th>
                                <th>{{ __('Date & Time') }}</th>
                                <th class="text-end pe-3">{{ __('reports.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkOuts as $checkOut)
                                <tr>
                                    <td class="ps-3 fw-medium text-dark">{{ $checkOut->guest_name }}</td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-2 px-2 py-1">
                                            {{ $checkOut->room->room_number ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="text-muted small">{{ $checkOut->actual_check_out_at ? \Carbon\Carbon::parse($checkOut->actual_check_out_at)->format('H:i') : 'N/A' }}</td>
                                    <td class="text-end pe-3 fw-bold text-dark">${{ number_format($checkOut->paid_amount, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">{{ __('global.no_data') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Currently Staying Guests -->
    <div class="border rounded-2 overflow-hidden bg-white mt-3">
        <div class="py-2 px-3 border-bottom bg-light d-flex align-items-center gap-2">
            <i data-lucide="bed-double" style="width: 16px; height: 16px;" class="text-info"></i>
            <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Currently In-House') }}</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b;">
                        <th class="ps-3">{{ __('reports.guest_name') }}</th>
                        <th>{{ __('reports.room_number') }}</th>
                        <th>{{ __('reports.check_in_date') }}</th>
                        <th>{{ __('reports.check_out_date') }}</th>
                        <th class="text-end pe-3">{{ __('reports.status') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stayingGuests as $guest)
                        <tr>
                            <td class="ps-3 fw-medium text-dark">{{ $guest->guest_name }}</td>
                            <td>
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-2 px-2 py-1">
                                    {{ $guest->room->room_number ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-muted small">{{ $guest->check_in_date ? $guest->check_in_date->format('d-m-Y') : 'N/A' }}</td>
                            <td class="text-muted small">{{ $guest->check_out_date ? $guest->check_out_date->format('d-m-Y') : 'N/A' }}</td>
                            <td class="text-end pe-3">
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                    {{ __('checkins.checked_in') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-muted small">{{ __('global.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-page-index>
