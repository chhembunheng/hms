<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('reports.guest_report') }} - {{ $startDate }} to {{ $endDate }}</title>

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
            <h2>របាយការណ៍ភ្ញៀវ</h2>
            <h4>{{ \Carbon\Carbon::parse($startDate)->format('M j, Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('M j, Y') }}</h4>
            <p class="text-muted">បានបង្កើតនៅ: {{ now()->format('Y-m-d H:i:s') }}</p>
        </div>

        <!-- Summary -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="summary-box">
                    <h5>ចំនួនភ្ញៀវសរុប</h5>
                    <h3>{{ $totalGuests }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <h5>ចំនួនការកក់សរុប</h5>
                    <h3>{{ $totalBookings }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="summary-box">
                    <h5>ចំណូលសរុប</h5>
                    <h3>${{ number_format($totalRevenue, 2) }}</h3>
                </div>
            </div>
        </div>

        <!-- Guest Statistics -->
        <div class="row mb-4">
            <div class="col-md-6">
                <h5 class="mb-3">ការបំបែកតាមសញ្ជាតិ</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
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

            <div class="col-md-6">
                <h5 class="mb-3">ការបំបែកតាមប្រភេទភ្ញៀវ</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
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

        <!-- Guest List -->
        <div class="row">
            <div class="col-12">
                <h5 class="mb-3">បញ្ជីភ្ញៀវ</h5>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-dark">
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

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
