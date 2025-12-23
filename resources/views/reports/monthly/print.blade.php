<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('reports.monthly_report') }} - {{ $month }}</title>

    <!-- Bootstrap CSS for print styling -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            body { font-size: 12px; }
            .no-print { display: none; }
            .card { border: 1px solid #dee2e6; margin-bottom: 1rem; }
            .table { font-size: 11px; }
            .table th, .table td { padding: 0.25rem; }
        }
        body { font-size: 14px; }
        .print-header { text-align: center; margin-bottom: 2rem; border-bottom: 2px solid #000; padding-bottom: 1rem; }
        .summary-box { border: 1px solid #dee2e6; padding: 1rem; margin: 0.5rem; text-align: center; }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Header -->
        <div class="print-header">
            <h2>របាយការណ៍ប្រចាំខែ</h2>
            <h4>{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</h4>
            <p class="text-muted">បានបង្កើតនៅ: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>

        <!-- Summary -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="summary-box">
                    <h5>ចំនួនចូលស្នាក់សរុប</h5>
                    <h3>{{ $totalCheckIns }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <h5>ចំនួនចាកចេញសរុប</h5>
                    <h3>{{ $totalCheckOuts }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <h5>ចំណូលសរុប</h5>
                    <h3>${{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Daily Breakdown Table -->
        <div class="row">
            <div class="col-12">
                <h5 class="mb-3">ការបំបែកប្រចាំថ្ងៃ</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
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

        <!-- Recent Check-ins -->
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="mb-3">ចូលស្នាក់ថ្មីៗ</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>កាលបរិច្ឆេទ</th>
                                <th>ឈ្មោះភ្ញៀវ</th>
                                <th>បន្ទប់</th>
                                <th>ចំនួនទឹកប្រាក់</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkIns->take(20) as $checkIn)
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

        <!-- Recent Check-outs -->
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="mb-3">ចាកចេញថ្មីៗ</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>កាលបរិច្ឆេទ</th>
                                <th>ឈ្មោះភ្ញៀវ</th>
                                <th>បន្ទប់</th>
                                <th>ចំនួនទឹកប្រាក់</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkOuts->take(20) as $checkOut)
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

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
