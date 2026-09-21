<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\AirportTransfer;
use App\Models\CheckIn;
use App\Models\Guest;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AirportTransferController extends Controller
{
    public function index(Request $request)
    {
        $startDate = parse_date_input($request->get('start_date')) ?? Carbon::today()->format('Y-m-d');
        $endDate = parse_date_input($request->get('end_date')) ?? Carbon::today()->addDays(7)->format('Y-m-d');
        $type = $request->get('type', 'all'); // all, pickup, dropoff
        $status = $request->get('status', 'all');

        $query = AirportTransfer::with(['checkIn.room', 'guest'])
            ->whereDate('transfer_datetime', '>=', $startDate)
            ->whereDate('transfer_datetime', '<=', $endDate);

        if ($type !== 'all') {
            $query->where('transfer_type', $type);
        }

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $transfers = $query->orderBy('transfer_datetime', 'asc')->get();

        // Metrics
        $todayPickups = AirportTransfer::whereDate('transfer_datetime', Carbon::today())
            ->where('transfer_type', 'pickup')->count();
        $todayDropoffs = AirportTransfer::whereDate('transfer_datetime', Carbon::today())
            ->where('transfer_type', 'dropoff')->count();
        $pendingTransfers = AirportTransfer::where('status', 'scheduled')->count();
        $totalRevenue = AirportTransfer::where('status', 'completed')->sum('price');

        // Active staying guests for dropdown
        $stayingCheckIns = CheckIn::with('room')->where('status', 'checked_in')->get();

        return view('services.transfers.index', compact(
            'transfers',
            'startDate',
            'endDate',
            'type',
            'status',
            'todayPickups',
            'todayDropoffs',
            'pendingTransfers',
            'totalRevenue',
            'stayingCheckIns'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'nullable|string|max:50',
            'check_in_id' => 'nullable|exists:check_ins,id',
            'transfer_type' => 'required|in:pickup,dropoff',
            'flight_number' => 'nullable|string|max:50',
            'transfer_datetime' => 'required|date',
            'vehicle_type' => 'required|in:van,car,remork_tuktuk,suv',
            'pickup_location' => 'required|string|max:255',
            'dropoff_location' => 'nullable|string|max:255',
            'passenger_count' => 'required|integer|min:1|max:50',
            'luggage_count' => 'nullable|integer|min:0|max:50',
            'driver_name' => 'nullable|string|max:255',
            'driver_phone' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'is_charged_to_room' => 'nullable|boolean',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['is_charged_to_room'] = $request->has('is_charged_to_room');
        $validated['status'] = 'scheduled';

        if (!empty($validated['check_in_id'])) {
            $checkIn = CheckIn::find($validated['check_in_id']);
            if ($checkIn) {
                $validated['guest_id'] = $checkIn->guest_id;
                if (empty($validated['guest_name'])) {
                    $validated['guest_name'] = $checkIn->guest_name;
                }
            }
        }

        AirportTransfer::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Airport transfer scheduled successfully.',
            'redirect' => route('services.transfers.index')
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $transfer = AirportTransfer::findOrFail($id);
        $status = $request->input('status');

        if (!in_array($status, ['scheduled', 'in_progress', 'completed', 'cancelled'])) {
            return response()->json(['status' => 'error', 'message' => 'Invalid status.'], 422);
        }

        $transfer->update(['status' => $status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Transfer status updated to ' . ucfirst($status) . '.',
            'redirect' => route('services.transfers.index')
        ]);
    }

    public function delete($id)
    {
        $transfer = AirportTransfer::findOrFail($id);
        $transfer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Airport transfer deleted successfully.',
            'redirect' => route('services.transfers.index')
        ]);
    }
}
