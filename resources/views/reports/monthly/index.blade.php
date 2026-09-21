<x-page-index 
    :title="__('reports.monthly_report')" 
    icon="calendar-days">

    <x-slot:actions>
        <a href="{{ route('reports.monthly.print', ['month' => $month]) }}" target="_blank" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1 rounded-2 px-3">
            <i data-lucide="printer" style="width: 14px; height: 14px;"></i>
            <span>{{ __('reports.print') }}</span>
        </a>
    </x-slot:actions>

    <x-slot:filters>
        <!-- Month Filter Form -->
        <div class="row g-2 align-items-center mb-3 px-1">
            <div class="col-md-3">
                <form method="GET" class="d-flex align-items-center gap-2">
                    <input type="month" name="month" value="{{ $month }}" class="form-control form-control-sm rounded-2" onchange="this.form.submit()">
                    <button type="submit" class="btn btn-sm btn-primary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                        <i data-lucide="filter" style="width: 13px; height: 13px;"></i>
                        <span>{{ __('global.filter') }}</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- KPI Metric Cards -->
        <div class="row g-2 px-1">
            <div class="col-md-4">
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
            <div class="col-md-4">
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

    <!-- Daily Breakdown Table -->
    <div class="border rounded-2 overflow-hidden bg-white mb-3">
        <div class="py-2 px-3 border-bottom bg-light d-flex align-items-center gap-2">
            <i data-lucide="calendar" style="width: 16px; height: 16px;" class="text-primary"></i>
            <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Daily Breakdown') ?? 'Daily Breakdown' }}</h6>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatables-custom">
                <thead class="table-light">
                    <tr class="text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b;">
                        <th class="ps-3">{{ __('reports.date') }}</th>
                        <th>{{ __('reports.total_check_ins') }}</th>
                        <th>{{ __('reports.total_check_outs') }}</th>
                        <th class="text-end pe-3">{{ __('reports.total_revenue') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dailyStats as $day)
                        <tr>
                            <td class="ps-3 fw-medium text-dark">{{ \Carbon\Carbon::parse($day['date'])->format('d-m-Y') }}</td>
                            <td>{{ $day['check_ins'] }}</td>
                            <td>{{ $day['check_outs'] }}</td>
                            <td class="text-end pe-3 fw-bold text-dark">${{ number_format($day['revenue'], 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light border-top">
                    <tr class="fw-bold text-dark">
                        <th class="ps-3">{{ __('global.total') ?? 'Total' }}</th>
                        <th>{{ $totalCheckIns }}</th>
                        <th>{{ $totalCheckOuts }}</th>
                        <th class="text-end pe-3 text-success">${{ number_format($totalRevenue, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Recent Check-ins and Check-outs -->
    <div class="row g-3">
        <div class="col-md-6">
            <div class="border rounded-2 overflow-hidden bg-white">
                <div class="py-2 px-3 border-bottom bg-light d-flex align-items-center gap-2">
                    <i data-lucide="log-in" style="width: 16px; height: 16px;" class="text-primary"></i>
                    <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Recent Check-ins') ?? 'Recent Check-ins' }}</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b;">
                                <th class="ps-3">{{ __('reports.date') }}</th>
                                <th>{{ __('reports.guest_name') }}</th>
                                <th>{{ __('reports.room_number') }}</th>
                                <th class="text-end pe-3">{{ __('reports.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkIns->take(10) as $checkIn)
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $checkIn->check_in_date ? $checkIn->check_in_date->format('d-m-Y') : 'N/A' }}</td>
                                    <td class="fw-medium text-dark">{{ $checkIn->guest_name }}</td>
                                    <td>
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-2 px-2 py-1">
                                            {{ $checkIn->room->room_number ?? 'N/A' }}
                                        </span>
                                    </td>
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
                    <h6 class="mb-0 fw-bold text-dark fs-6">{{ __('Recent Check-outs') ?? 'Recent Check-outs' }}</h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b;">
                                <th class="ps-3">{{ __('reports.date') }}</th>
                                <th>{{ __('reports.guest_name') }}</th>
                                <th>{{ __('reports.room_number') }}</th>
                                <th class="text-end pe-3">{{ __('reports.amount') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkOuts->take(10) as $checkOut)
                                <tr>
                                    <td class="ps-3 text-muted small">{{ $checkOut->actual_check_out_at ? \Carbon\Carbon::parse($checkOut->actual_check_out_at)->format('d-m-Y') : 'N/A' }}</td>
                                    <td class="fw-medium text-dark">{{ $checkOut->guest_name }}</td>
                                    <td>
                                        <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-2 px-2 py-1">
                                            {{ $checkOut->room->room_number ?? 'N/A' }}
                                        </span>
                                    </td>
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
</x-page-index>
