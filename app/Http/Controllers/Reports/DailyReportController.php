<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DailyReportController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));

        // Get check-ins for the selected date
        $checkIns = CheckIn::whereDate('check_in_date', $date)
            ->with(['guest', 'room'])
            ->orderBy('check_in_time', 'desc')
            ->get();

        // Get check-outs for the selected date
        $checkOuts = CheckIn::whereDate('actual_check_out_at', $date)
            ->with(['guest', 'room'])
            ->orderBy('actual_check_out_at', 'desc')
            ->get();

        // Calculate totals
        $totalCheckIns = $checkIns->count();
        $totalCheckOuts = $checkOuts->count();
        $totalRevenue = $checkIns->sum('paid_amount') + $checkOuts->sum('paid_amount');

        // Current staying guests
        $stayingGuests = CheckIn::checkedIn()
            ->with(['guest', 'room'])
            ->get();

        return view('reports.daily.index', compact(
            'date',
            'checkIns',
            'checkOuts',
            'totalCheckIns',
            'totalCheckOuts',
            'totalRevenue',
            'stayingGuests'
        ));
    }

    public function print(Request $request)
    {
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));

        // Get check-ins for the selected date
        $checkIns = CheckIn::whereDate('check_in_date', $date)
            ->with(['guest', 'room'])
            ->orderBy('check_in_time', 'desc')
            ->get();

        // Get check-outs for the selected date
        $checkOuts = CheckIn::whereDate('actual_check_out_at', $date)
            ->with(['guest', 'room'])
            ->orderBy('actual_check_out_at', 'desc')
            ->get();

        // Calculate totals
        $totalCheckIns = $checkIns->count();
        $totalCheckOuts = $checkOuts->count();
        $totalRevenue = $checkIns->sum('paid_amount') + $checkOuts->sum('paid_amount');

        // Current staying guests
        $stayingGuests = CheckIn::checkedIn()
            ->with(['guest', 'room'])
            ->get();

        return view('reports.daily.print', compact(
            'date',
            'checkIns',
            'checkOuts',
            'totalCheckIns',
            'totalCheckOuts',
            'totalRevenue',
            'stayingGuests'
        ));
    }
}
