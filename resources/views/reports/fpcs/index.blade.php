<x-page-index 
    :title="__('FPCS Foreigner Registration Report')" 
    icon="file-badge">

    <x-slot:actions>
        <a href="{{ route('reports.fpcs.export', request()->query()) }}" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 rounded-2 px-3">
            <i data-lucide="file-spreadsheet" style="width: 14px; height: 14px;"></i>
            <span>{{ __('Export FPCS (CSV)') }}</span>
        </a>
        <a href="{{ route('reports.fpcs.print', request()->query()) }}" target="_blank" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 rounded-2 px-3">
            <i data-lucide="printer" style="width: 14px; height: 14px;"></i>
            <span>{{ __('Print for Police') }}</span>
        </a>
    </x-slot:actions>

    <x-slot:filters>
        <!-- KPI Metric Row -->
        <div class="row g-2 mb-3 px-1">
            <div class="col-sm-6 col-xl-3">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="users" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('Total Foreign Guests') }}</div>
                        <div class="fw-bold text-primary fs-5">{{ number_format($totalForeigners) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-success-subtle text-success d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="bed" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('Currently In-House') }}</div>
                        <div class="fw-bold text-success fs-5">{{ number_format($currentlyInHouse) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-info-subtle text-info d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="globe" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('Nationalities') }}</div>
                        <div class="fw-bold text-info fs-5">{{ number_format($nationalitiesCount) }}</div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-3">
                <div class="d-flex align-items-center p-2 rounded-2 border bg-white">
                    <div class="rounded-circle bg-warning-subtle text-warning d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                        <i data-lucide="alert-triangle" style="width: 20px; height: 20px;"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold">{{ __('Missing Visa Info') }}</div>
                        <div class="fw-bold {{ $missingVisaCount > 0 ? 'text-danger' : 'text-muted' }} fs-5">
                            {{ number_format($missingVisaCount) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('reports.fpcs.index') }}" class="row g-2 align-items-end px-1">
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('From Date') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="start_date" class="form-control form-control-sm rounded-start-2 pickadate"
                           value="{{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }}" autocomplete="off">
                    <span class="input-group-text rounded-end-2"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('To Date') }}</label>
                <div class="input-group input-group-sm">
                    <input type="text" name="end_date" class="form-control form-control-sm rounded-start-2 pickadate"
                           value="{{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}" autocomplete="off">
                    <span class="input-group-text rounded-end-2"><i data-lucide="calendar" style="width: 14px; height: 14px;"></i></span>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-semibold text-muted mb-1">{{ __('Country / Nationality') }}</label>
                <select name="country" class="form-select form-select-sm rounded-2">
                    <option value="">{{ __('All Nationalities') }}</option>
                    @foreach($availableCountries as $c)
                        <option value="{{ $c }}" {{ request('country') == $c ? 'selected' : '' }}>{{ $c }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                    <i data-lucide="filter" style="width: 13px; height: 13px;"></i>
                    <span>{{ __('global.filter') }}</span>
                </button>
                <a href="{{ route('reports.fpcs.index') }}" class="btn btn-sm btn-outline-secondary rounded-2 px-3 d-inline-flex align-items-center gap-1">
                    <i data-lucide="rotate-ccw" style="width: 13px; height: 13px;"></i>
                    <span>{{ __('global.clear') }}</span>
                </a>
            </div>
        </form>
    </x-slot:filters>

    <!-- Table Card -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 datatables-custom">
            <thead class="table-light">
                <tr class="text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.5px; color: #64748b;">
                    <th class="text-center ps-3" style="width: 50px;">#</th>
                    <th>{{ __('Guest Name (Passport)') }}</th>
                    <th>{{ __('Nationality') }}</th>
                    <th>Passport No.</th>
                    <th>{{ __('Visa Details') }}</th>
                    <th>{{ __('Entry Date & Port') }}</th>
                    <th>{{ __('Room') }}</th>
                    <th>{{ __('Stay Dates') }}</th>
                    <th class="text-end pe-3">{{ __('Status') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $index => $item)
                    @php
                        $guest = $item->guest;
                        $passport = $item->guest_passport ?: ($guest?->passport ?? '-');
                        $visaNo = $item->guest_visa_number ?: ($guest?->visa_number ?? null);
                        $visaType = $item->guest_visa_type ?: ($guest?->visa_type ?? 'T');
                        $visaExp = $item->guest_visa_expiry_date ?: ($guest?->visa_expiry_date ?? null);
                        $entryDate = $item->guest_entry_date ?: ($guest?->entry_date ?? null);
                        $entryPort = $item->guest_entry_port ?: ($guest?->entry_port ?? 'SAI Airport');
                    @endphp
                    <tr>
                        <td class="text-center ps-3 text-muted">{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $item->guest_name }}</div>
                            <div class="small text-muted">
                                {{ $guest?->gender ? ucfirst($guest->gender) : '' }}
                                @if($guest?->date_of_birth)
                                    · DOB: {{ $guest->date_of_birth->format('d-m-Y') }}
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border rounded-pill px-2 py-1">
                                {{ $item->guest_country ?: ($guest?->country ?? 'Foreign') }}
                            </span>
                        </td>
                        <td>
                            <code class="fw-bold text-primary">{{ $passport }}</code>
                        </td>
                        <td>
                            @if($visaNo)
                                <div>
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">
                                        {{ $visaType }}: {{ $visaNo }}
                                    </span>
                                </div>
                                @if($visaExp)
                                    <small class="text-muted d-block mt-1">Exp: {{ \Carbon\Carbon::parse($visaExp)->format('d-m-Y') }}</small>
                                @endif
                            @else
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-2 py-1">
                                    <i data-lucide="alert-triangle" style="width: 12px; height: 12px;" class="me-1"></i>{{ __('Missing Visa') }}
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="small">
                                <i data-lucide="plane-landing" style="width: 12px; height: 12px;" class="text-muted me-1"></i>
                                {{ $entryPort }}
                            </div>
                            @if($entryDate)
                                <small class="text-muted">{{ \Carbon\Carbon::parse($entryDate)->format('d-m-Y') }}</small>
                            @endif
                        </td>
                        <td>
                            @if($item->room)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-2 px-2 py-1 fw-bold">{{ $item->room->room_number }}</span>
                                <small class="text-muted d-block">{{ $item->room->roomType?->name ?? '' }}</small>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="small fw-semibold text-dark">
                                In: {{ $item->check_in_date ? $item->check_in_date->format('d-m-Y') : '-' }}
                            </div>
                            <div class="small text-muted">
                                Out: {{ $item->check_out_date ? $item->check_out_date->format('d-m-Y') : '-' }}
                            </div>
                        </td>
                        <td class="text-end pe-3">
                            @if($item->status === 'checked_in')
                                <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-2 py-1">{{ __('Staying') }}</span>
                            @elseif($item->status === 'checked_out')
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle rounded-pill px-2 py-1">{{ __('Checked Out') }}</span>
                            @else
                                <span class="badge bg-light text-dark border rounded-pill px-2 py-1">{{ ucfirst($item->status) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <div class="d-flex flex-column align-items-center">
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                                    <i data-lucide="file-badge" style="width: 28px; height: 28px; stroke: #94a3b8;"></i>
                                </div>
                                <h6 class="fw-semibold text-secondary mb-1">No foreign guest records found for the selected period.</h6>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-page-index>
