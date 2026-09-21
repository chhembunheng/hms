<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FPCS Foreigner Registration Report - Siem Reap Police</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-size: 12px; color: #212529; }
        @media print {
            .no-print { display: none !important; }
            body { font-size: 11px; }
            @page { size: landscape; margin: 12mm; }
        }
        .header-title { font-size: 16px; font-weight: bold; }
        table th, table td { padding: 5px 8px !important; }
    </style>
</head>
<body class="p-4">
    <div class="no-print mb-3 text-end">
        <button onclick="window.print()" class="btn btn-primary btn-sm">Print Report</button>
        <button onclick="window.close()" class="btn btn-secondary btn-sm">Close</button>
    </div>

    <div class="text-center mb-4">
        <h5 class="mb-1 text-uppercase fw-bold">KINGDOM OF CAMBODIA</h5>
        <div class="text-muted small">Nation · Religion · King</div>
        <hr class="w-25 mx-auto my-2">
        <h4 class="header-title mt-3 text-uppercase">FOREIGN GUEST DAILY REGISTRATION REPORT (FPCS)</h4>
        <div class="small text-muted">
            Submission to: Siem Reap Provincial Police Commissariat · Immigration Bureau
        </div>
        <div class="small fw-semibold mt-1">
            Period: {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}
        </div>
    </div>

    <table class="table table-bordered table-sm align-middle">
        <thead class="table-light text-center">
            <tr>
                <th style="width: 30px;">#</th>
                <th>Full Name</th>
                <th>Sex</th>
                <th>DOB</th>
                <th>Country</th>
                <th>Passport No.</th>
                <th>Visa No.</th>
                <th>Visa Type</th>
                <th>Visa Expiry</th>
                <th>Entry Date</th>
                <th>Port</th>
                <th>Room</th>
                <th>Check-In</th>
                <th>Check-Out</th>
            </tr>
        </thead>
        <tbody>
            @forelse($records as $index => $item)
                @php
                    $guest = $item->guest;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="fw-semibold">{{ $item->guest_name }}</td>
                    <td class="text-center">{{ $guest?->gender ? strtoupper(substr($guest->gender, 0, 1)) : '-' }}</td>
                    <td class="text-center">{{ $guest?->date_of_birth ? $guest->date_of_birth->format('d-m-Y') : '-' }}</td>
                    <td>{{ $item->guest_country ?: ($guest?->country ?? '-') }}</td>
                    <td class="fw-bold">{{ $item->guest_passport ?: ($guest?->passport ?? '-') }}</td>
                    <td>{{ $item->guest_visa_number ?: ($guest?->visa_number ?? '-') }}</td>
                    <td class="text-center">{{ $item->guest_visa_type ?: ($guest?->visa_type ?? 'T') }}</td>
                    <td class="text-center">{{ $item->guest_visa_expiry_date ? $item->guest_visa_expiry_date->format('d-m-Y') : ($guest?->visa_expiry_date ? $guest->visa_expiry_date->format('d-m-Y') : '-') }}</td>
                    <td class="text-center">{{ $item->guest_entry_date ? $item->guest_entry_date->format('d-m-Y') : ($guest?->entry_date ? $guest->entry_date->format('d-m-Y') : '-') }}</td>
                    <td>{{ $item->guest_entry_port ?: ($guest?->entry_port ?? 'SAI') }}</td>
                    <td class="text-center fw-bold">{{ $item->room?->room_number ?? '-' }}</td>
                    <td class="text-center">{{ $item->check_in_date ? $item->check_in_date->format('d-m-Y') : '-' }}</td>
                    <td class="text-center">{{ $item->check_out_date ? $item->check_out_date->format('d-m-Y') : '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center py-3">No foreign guests found for this period.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="row mt-5 pt-3">
        <div class="col-6 text-center">
            <div>Hotel Receptionist / Duty Manager</div>
            <div class="text-muted small mt-5">(Signature & Name)</div>
        </div>
        <div class="col-6 text-center">
            <div>Acknowledged by Tourist Police Officer</div>
            <div class="text-muted small mt-5">(Signature & Official Stamp)</div>
        </div>
    </div>
</body>
</html>
