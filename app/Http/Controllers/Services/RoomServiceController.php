<?php

namespace App\Http\Controllers\Services;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Service;
use App\Models\CheckInService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomServiceController extends Controller
{
    public function index(Request $request)
    {
        $checkInId = $request->get('check_in_id');
        
        $query = CheckInService::with(['checkIn.room', 'service'])
            ->orderBy('created_at', 'desc');

        if (!empty($checkInId)) {
            $query->where('check_in_id', $checkInId);
        }

        $postedServices = $query->take(100)->get();

        // Active staying guests
        $stayingCheckIns = CheckIn::with(['room.roomType', 'services.service'])
            ->where('status', 'checked_in')
            ->orderBy('room_id')
            ->get();

        // Available active services grouped by category
        $availableServices = Service::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name_en')
            ->get();

        $totalPostedAmount = CheckInService::where('is_billed', false)->sum('total_price');

        return view('services.room-services.index', compact(
            'postedServices',
            'stayingCheckIns',
            'availableServices',
            'totalPostedAmount',
            'checkInId'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'check_in_id' => 'required|exists:check_ins,id',
            'service_id' => 'required|exists:services,id',
            'quantity' => 'required|numeric|min:0.1|max:999',
            'unit_price' => 'required|numeric|min:0',
            'service_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        $service = Service::findOrFail($validated['service_id']);
        $validated['unit_price'] = $request->unit_price ?: $service->price;
        $validated['total_price'] = round($validated['quantity'] * $validated['unit_price'], 2);
        $validated['service_date'] = parse_date_input($request->service_date) ?: Carbon::today()->format('Y-m-d');
        $validated['is_billed'] = false;

        CheckInService::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Service charged to room successfully.',
            'redirect' => route('services.room-services.index', ['check_in_id' => $validated['check_in_id']])
        ]);
    }

    public function delete($id)
    {
        $item = CheckInService::findOrFail($id);

        if ($item->is_billed) {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot remove a service that has already been billed on an invoice.'
            ], 422);
        }

        $item->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Room charge removed successfully.',
            'redirect' => route('services.room-services.index')
        ]);
    }
}
