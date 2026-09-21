<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Guest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FPCSReportController extends Controller
{
    /**
     * Display FPCS Foreigner Registration Report.
     */
    public function index(Request $request)
    {
        $startDate = parse_date_input($request->get('start_date')) ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = parse_date_input($request->get('end_date')) ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $country = $request->get('country');
        $status = $request->get('status', 'all'); // all, staying, checked_out

        $query = CheckIn::with(['guest', 'room.roomType'])
            ->where(function ($q) {
                $q->where('guest_type', 'international')
                  ->orWhereNotNull('guest_passport');
            })
            ->whereBetween('check_in_date', [$startDate, $endDate]);

        if (!empty($country)) {
            $query->where(function ($q) use ($country) {
                $q->where('guest_country', $country)
                  ->orWhereHas('guest', fn($gq) => $gq->where('country', $country));
            });
        }

        if ($status === 'staying') {
            $query->where('status', 'checked_in');
        } elseif ($status === 'checked_out') {
            $query->where('status', 'checked_out');
        }

        $records = $query->orderBy('check_in_date', 'desc')->get();

        // Statistics
        $totalForeigners = $records->count();
        $currentlyInHouse = $records->where('status', 'checked_in')->count();
        $nationalitiesCount = $records->pluck('guest_country')->filter()->unique()->count();
        $missingVisaCount = $records->filter(function ($item) {
            $visa = $item->guest_visa_number ?: ($item->guest?->visa_number ?? null);
            return empty($visa);
        })->count();

        // Available countries list for filter
        $availableCountries = Guest::whereNotNull('country')
            ->where('guest_type', 'international')
            ->distinct()
            ->pluck('country')
            ->filter()
            ->sort()
            ->values();

        return view('reports.fpcs.index', compact(
            'records',
            'startDate',
            'endDate',
            'country',
            'status',
            'totalForeigners',
            'currentlyInHouse',
            'nationalitiesCount',
            'missingVisaCount',
            'availableCountries'
        ));
    }

    /**
     * Export FPCS Foreigner Registration Report as CSV formatted for Cambodia Immigration (GDI).
     */
    public function export(Request $request): StreamedResponse
    {
        $startDate = parse_date_input($request->get('start_date')) ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = parse_date_input($request->get('end_date')) ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $country = $request->get('country');

        $query = CheckIn::with(['guest', 'room'])
            ->where(function ($q) {
                $q->where('guest_type', 'international')
                  ->orWhereNotNull('guest_passport');
            })
            ->whereBetween('check_in_date', [$startDate, $endDate]);

        if (!empty($country)) {
            $query->where(function ($q) use ($country) {
                $q->where('guest_country', $country)
                  ->orWhereHas('guest', fn($gq) => $gq->where('country', $country));
            });
        }

        $records = $query->orderBy('check_in_date', 'asc')->get();

        $fileName = 'FPCS_Cambodia_Immigration_Report_' . Carbon::now()->format('Ymd_His') . '.csv';

        return response()->streamDownload(function () use ($records) {
            $output = fopen('php://output', 'w');
            
            // UTF-8 BOM for Excel Khmer/Unicode support
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

            // Standard FPCS GDI Columns
            fputcsv($output, [
                'No.',
                'Full Name (Passport)',
                'Gender',
                'Date of Birth',
                'Nationality / Country',
                'Passport Number',
                'Visa Number',
                'Visa Type',
                'Visa Expiry Date',
                'Entry Date (Cambodia)',
                'Port of Entry',
                'Check-In Date',
                'Check-Out Date',
                'Room Number',
                'Contact Phone',
                'Stay Status'
            ]);

            $index = 1;
            foreach ($records as $item) {
                $guest = $item->guest;
                fputcsv($output, [
                    $index++,
                    $item->guest_name ?: ($guest?->full_name ?? '-'),
                    $guest?->gender ? ucfirst($guest->gender) : 'Unspecified',
                    $guest?->date_of_birth ? $guest->date_of_birth->format('d-m-Y') : '-',
                    $item->guest_country ?: ($guest?->country ?? 'Foreign'),
                    $item->guest_passport ?: ($guest?->passport ?? '-'),
                    $item->guest_visa_number ?: ($guest?->visa_number ?? '-'),
                    $item->guest_visa_type ?: ($guest?->visa_type ?? 'T'),
                    $item->guest_visa_expiry_date ? $item->guest_visa_expiry_date->format('d-m-Y') : ($guest?->visa_expiry_date ? $guest->visa_expiry_date->format('d-m-Y') : '-'),
                    $item->guest_entry_date ? $item->guest_entry_date->format('d-m-Y') : ($guest?->entry_date ? $guest->entry_date->format('d-m-Y') : '-'),
                    $item->guest_entry_port ?: ($guest?->entry_port ?? 'SAI Airport'),
                    $item->check_in_date ? $item->check_in_date->format('d-m-Y') : '-',
                    $item->check_out_date ? $item->check_out_date->format('d-m-Y') : '-',
                    $item->room?->room_number ?? '-',
                    $item->guest_phone ?: ($guest?->phone ?? '-'),
                    ucfirst(str_replace('_', ' ', $item->status)),
                ]);
            }

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$fileName}\"",
        ]);
    }

    /**
     * Printable view for Siem Reap Police / Immigration submission.
     */
    public function print(Request $request)
    {
        $startDate = parse_date_input($request->get('start_date')) ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = parse_date_input($request->get('end_date')) ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $country = $request->get('country');

        $query = CheckIn::with(['guest', 'room.roomType'])
            ->where(function ($q) {
                $q->where('guest_type', 'international')
                  ->orWhereNotNull('guest_passport');
            })
            ->whereBetween('check_in_date', [$startDate, $endDate]);

        if (!empty($country)) {
            $query->where(function ($q) use ($country) {
                $q->where('guest_country', $country)
                  ->orWhereHas('guest', fn($gq) => $gq->where('country', $country));
            });
        }

        $records = $query->orderBy('check_in_date', 'asc')->get();

        return view('reports.fpcs.print', compact('records', 'startDate', 'endDate'));
    }
}
