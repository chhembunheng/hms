<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MonthlyReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // Get check-ins for the selected month
        $checkIns = CheckIn::whereBetween('check_in_date', [$startDate, $endDate])
            ->with(['guest', 'room'])
            ->orderBy('check_in_date', 'desc')
            ->get();

        // Get check-outs for the selected month
        $checkOuts = CheckIn::whereBetween('actual_check_out_at', [$startDate, $endDate])
            ->with(['guest', 'room'])
            ->orderBy('actual_check_out_at', 'desc')
            ->get();

        // Calculate monthly totals
        $totalCheckIns = $checkIns->count();
        $totalCheckOuts = $checkOuts->count();
        $totalRevenue = $checkIns->sum('paid_amount') + $checkOuts->sum('paid_amount');

        // Daily breakdown
        $dailyStats = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayCheckIns = $checkIns->where('check_in_date', $date->format('Y-m-d'))->count();
            $dayCheckOuts = $checkOuts->filter(function ($checkOut) use ($date) {
                return Carbon::parse($checkOut->actual_check_out_at)->format('Y-m-d') === $date->format('Y-m-d');
            })->count();
            $dayRevenue = $checkIns->where('check_in_date', $date->format('Y-m-d'))->sum('paid_amount') +
                         $checkOuts->filter(function ($checkOut) use ($date) {
                             return Carbon::parse($checkOut->actual_check_out_at)->format('Y-m-d') === $date->format('Y-m-d');
                         })->sum('paid_amount');

            $dailyStats[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('d'),
                'check_ins' => $dayCheckIns,
                'check_outs' => $dayCheckOuts,
                'revenue' => $dayRevenue,
            ];
        }

        return view('reports.monthly.index', compact(
            'month',
            'checkIns',
            'checkOuts',
            'totalCheckIns',
            'totalCheckOuts',
            'totalRevenue',
            'dailyStats'
        ));
    }

    public function print(Request $request)
    {
        $month = $request->get('month', Carbon::now()->format('Y-m'));
        $startDate = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
        $endDate = Carbon::createFromFormat('Y-m', $month)->endOfMonth();

        // Get check-ins for the selected month
        $checkIns = CheckIn::whereBetween('check_in_date', [$startDate, $endDate])
            ->with(['guest', 'room'])
            ->orderBy('check_in_date', 'desc')
            ->get();

        // Get check-outs for the selected month
        $checkOuts = CheckIn::whereBetween('actual_check_out_at', [$startDate, $endDate])
            ->with(['guest', 'room'])
            ->orderBy('actual_check_out_at', 'desc')
            ->get();

        // Calculate monthly totals
        $totalCheckIns = $checkIns->count();
        $totalCheckOuts = $checkOuts->count();
        $totalRevenue = $checkIns->sum('paid_amount') + $checkOuts->sum('paid_amount');

        // Daily breakdown
        $dailyStats = [];
        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $dayCheckIns = $checkIns->where('check_in_date', $date->format('Y-m-d'))->count();
            $dayCheckOuts = $checkOuts->filter(function ($checkOut) use ($date) {
                return Carbon::parse($checkOut->actual_check_out_at)->format('Y-m-d') === $date->format('Y-m-d');
            })->count();
            $dayRevenue = $checkIns->where('check_in_date', $date->format('Y-m-d'))->sum('paid_amount') +
                         $checkOuts->filter(function ($checkOut) use ($date) {
                             return Carbon::parse($checkOut->actual_check_out_at)->format('Y-m-d') === $date->format('Y-m-d');
                         })->sum('paid_amount');

            $dailyStats[] = [
                'date' => $date->format('Y-m-d'),
                'day' => $date->format('d'),
                'check_ins' => $dayCheckIns,
                'check_outs' => $dayCheckOuts,
                'revenue' => $dayRevenue,
            ];
        }

        return view('reports.monthly.print', compact(
            'month',
            'checkIns',
            'checkOuts',
            'totalCheckIns',
            'totalCheckOuts',
            'totalRevenue',
            'dailyStats'
        ));
    }
}
