<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('reports.daily_report') }} - {{ $date }}</title>

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
            <h2>របាយការណ៍ប្រចាំថ្ងៃ</h2>
            <h4>{{ \Carbon\Carbon::parse($date)->format('l, F j, Y') }}</h4>
            <p class="text-muted">បានបង្កើតនៅ: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>

        <!-- Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="summary-box">
                    <h5>ចំនួនចូលស្នាក់</h5>
                    <h3>{{ $totalCheckIns }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-box">
                    <h5>ចំនួនចាកចេញ</h5>
                    <h3>{{ $totalCheckOuts }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-box">
                    <h5>ចំណូលសរុប</h5>
                    <h3>${{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
            <div class="col-md-3">
                <div class="summary-box">
                    <h5>ភ្ញៀវកំពុងស្នាក់</h5>
                    <h3>{{ $stayingGuests->count() }}</h3>
                </div>
            </div>
        </div>

        <!-- Check-ins Table -->
        <div class="row">
            <div class="col-12">
                <h5 class="mb-3">ចូលស្នាក់ថ្ងៃនេះ</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ឈ្មោះភ្ញៀវ</th>
                                <th>បន្ទប់</th>
                                <th>ពេលវេលា</th>
                                <th>ចំនួនទឹកប្រាក់</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkIns as $checkIn)
                            <tr>
                                <td>{{ $checkIn->guest_name }}</td>
                                <td>{{ $checkIn->room->room_number ?? 'N/A' }}</td>
                                <td>{{ $checkIn->check_in_time ? $checkIn->check_in_time->format('H:i') : 'N/A' }}</td>
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

        <!-- Check-outs Table -->
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="mb-3">ចាកចេញថ្ងៃនេះ</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ឈ្មោះភ្ញៀវ</th>
                                <th>បន្ទប់</th>
                                <th>ពេលវេលា</th>
                                <th>ចំនួនទឹកប្រាក់</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($checkOuts as $checkOut)
                            <tr>
                                <td>{{ $checkOut->guest_name }}</td>
                                <td>{{ $checkOut->room->room_number ?? 'N/A' }}</td>
                                <td>{{ $checkOut->actual_check_out_at ? \Carbon\Carbon::parse($checkOut->actual_check_out_at)->format('H:i') : 'N/A' }}</td>
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

        <!-- Staying Guests -->
        <div class="row mt-4">
            <div class="col-12">
                <h5 class="mb-3">ភ្ញៀវកំពុងស្នាក់បច្ចុប្បន្ន</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>ឈ្មោះភ្ញៀវ</th>
                                <th>បន្ទប់</th>
                                <th>កាលបរិច្ឆេទចូលស្នាក់</th>
                                <th>កាលបរិច្ឆេទចាកចេញ</th>
                                <th>ស្ថានភាព</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($stayingGuests as $guest)
                            <tr>
                                <td>{{ $guest->guest_name }}</td>
                                <td>{{ $guest->room->room_number ?? 'N/A' }}</td>
                                <td>{{ $guest->check_in_date ? $guest->check_in_date->format('Y-m-d') : 'N/A' }}</td>
                                <td>{{ $guest->check_out_date ? $guest->check_out_date->format('Y-m-d') : 'N/A' }}</td>
                                <td>បានចូលស្នាក់</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center">{{ __('global.no_data') }}</td>
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
