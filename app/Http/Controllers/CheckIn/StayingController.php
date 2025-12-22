<?php

namespace App\Http\Controllers\CheckIn;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Room;
use Illuminate\Http\Request;
use App\DataTables\CheckIns\StayingDataTable;

class StayingController extends Controller
{
    public function index(StayingDataTable $dataTable)
    {
        return $dataTable->render('check-ins.staying.index');
    }

    public function edit(Request $request, $id)
    {
        $form = CheckIn::findOrFail($id);
        $rooms = Room::with(['roomType', 'status'])->active()->get();

        if ($request->isMethod('post')) {
            $rules = [
                'room_id' => 'required|exists:rooms,id',
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'nullable|email',
                'guest_phone' => 'nullable|string|max:20',
                'guest_type' => 'required|in:national,international',
                'guest_national_id' => 'required_if:guest_type,national|string|max:20',
                'guest_passport' => 'required_if:guest_type,international|string|max:20',
                'guest_country' => 'required_if:guest_type,international|string|max:100',
                'number_of_guests' => 'required|integer|min:1|max:10',
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
                'total_amount' => 'required|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0|lte:total_amount',
                'status' => 'required|in:confirmed,checked_in,checked_out,cancelled',
                'notes' => 'nullable|string|max:1000',
            ];

            $request->validate($rules);

            $data = $request->only([
                'room_id', 'guest_name', 'guest_email', 'guest_phone',
                'guest_type', 'guest_national_id', 'guest_passport', 'guest_country',
                'number_of_guests', 'check_in_date', 'check_out_date',
                'total_amount', 'paid_amount', 'status', 'notes'
            ]);

            $form->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Staying guest updated successfully.',
                'redirect' => route('checkin.staying.index'),
                'delay' => 2000
            ]);
        }

        return view('check-ins.staying.form', compact('form', 'rooms'));
    }

    public function delete($id)
    {
        $checkIn = CheckIn::findOrFail($id);
        $checkIn->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Staying guest deleted successfully.',
            'redirect' => route('checkin.staying.index'),
            'delay' => 2000
        ]);
    }

    public function checkOut(Request $request, $id)
    {
        $checkIn = CheckIn::findOrFail($id);

        if ($checkIn->status !== 'checked_in') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot check-out this guest.'
            ], 422);
        }

        // Validate payment data
        $rules = [
            'paid_amount' => 'required|numeric|min:0|max:' . $checkIn->total_amount,
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ];

        $request->validate($rules);

        $checkIn->update([
            'status' => 'checked_out',
            'actual_check_out_at' => now(),
            'paid_amount' => $request->paid_amount,
            'notes' => $request->notes,
        ]);

        // Update room statuses to "Cleaning"
        $cleaningStatus = \App\Models\RoomStatus::where('name_en', 'Cleaning')->first();
        if ($cleaningStatus) {
            $roomIds = $checkIn->checkInRooms->pluck('room_id')->toArray();
            \App\Models\Room::whereIn('id', $roomIds)->update(['status_id' => $cleaningStatus->id]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Guest checked out and payment processed successfully.',
            'redirect' => route('checkin.staying.index'),
            'delay' => 2000
        ]);
    }
}
