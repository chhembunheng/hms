<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\Guest;
use App\Models\CheckIn;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GuestReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get guests who checked in during the period
        $guests = Guest::whereHas('checkIns', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('check_in_date', [$startDate, $endDate]);
        })->with(['checkIns' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('check_in_date', [$startDate, $endDate])
                  ->with('room');
        }])->get();

        // Calculate guest statistics
        $totalGuests = $guests->count();
        $totalBookings = $guests->sum(function ($guest) {
            return $guest->checkIns->count();
        });
        $totalRevenue = $guests->sum(function ($guest) {
            return $guest->checkIns->sum('paid_amount');
        });

        // Guest nationality breakdown
        $nationalityStats = $guests->groupBy('country')->map(function ($group) {
            return [
                'country' => $group->first()->country,
                'count' => $group->count(),
                'revenue' => $group->sum(function ($guest) {
                    return $guest->checkIns->sum('paid_amount');
                }),
            ];
        })->sortByDesc('count')->values();

        // Guest type breakdown
        $guestTypeStats = [];
        foreach ($guests as $guest) {
            foreach ($guest->checkIns as $checkIn) {
                $type = $checkIn->guest_type ?? 'Regular';
                if (!isset($guestTypeStats[$type])) {
                    $guestTypeStats[$type] = ['count' => 0, 'revenue' => 0];
                }
                $guestTypeStats[$type]['count']++;
                $guestTypeStats[$type]['revenue'] += $checkIn->paid_amount;
            }
        }

        return view('reports.guest.index', compact(
            'startDate',
            'endDate',
            'guests',
            'totalGuests',
            'totalBookings',
            'totalRevenue',
            'nationalityStats',
            'guestTypeStats'
        ));
    }

    public function print(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Get guests who checked in during the period
        $guests = Guest::whereHas('checkIns', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('check_in_date', [$startDate, $endDate]);
        })->with(['checkIns' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('check_in_date', [$startDate, $endDate])
                  ->with('room');
        }])->get();

        // Calculate guest statistics
        $totalGuests = $guests->count();
        $totalBookings = $guests->sum(function ($guest) {
            return $guest->checkIns->count();
        });
        $totalRevenue = $guests->sum(function ($guest) {
            return $guest->checkIns->sum('paid_amount');
        });

        // Guest nationality breakdown
        $nationalityStats = $guests->groupBy('country')->map(function ($group) {
            return [
                'country' => $group->first()->country,
                'count' => $group->count(),
                'revenue' => $group->sum(function ($guest) {
                    return $guest->checkIns->sum('paid_amount');
                }),
            ];
        })->sortByDesc('count')->values();

        // Guest type breakdown
        $guestTypeStats = [];
        foreach ($guests as $guest) {
            foreach ($guest->checkIns as $checkIn) {
                $type = $checkIn->guest_type ?? 'Regular';
                if (!isset($guestTypeStats[$type])) {
                    $guestTypeStats[$type] = ['count' => 0, 'revenue' => 0];
                }
                $guestTypeStats[$type]['count']++;
                $guestTypeStats[$type]['revenue'] += $checkIn->paid_amount;
            }
        }

        return view('reports.guest.print', compact(
            'startDate',
            'endDate',
            'guests',
            'totalGuests',
            'totalBookings',
            'totalRevenue',
            'nationalityStats',
            'guestTypeStats'
        ));
    }
}
