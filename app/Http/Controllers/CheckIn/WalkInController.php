<?php

namespace App\Http\Controllers\CheckIn;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Room;
use Illuminate\Http\Request;
use App\DataTables\CheckIns\WalkInDataTable;

class WalkInController extends Controller
{
    public function index(WalkInDataTable $dataTable)
    {
        return $dataTable->render('check-ins.walkin.index');
    }

    public function add(Request $request)
    {
        $form = new CheckIn();
        $rooms = Room::with(['roomType', 'status'])
            ->whereHas('status', function($query) {
                $query->where('name_en', 'Available');
            })
            ->active()
            ->get();

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
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'total_amount' => 'required|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0|lte:total_amount',
                'notes' => 'nullable|string|max:1000',
            ];

            $request->validate($rules);

            // Check if room is available for the selected dates
            $room = Room::findOrFail($request->room_id);
            $conflictingBookings = CheckIn::where('room_id', $request->room_id)
                ->whereIn('status', ['confirmed', 'checked_in'])
                ->where(function($query) use ($request) {
                    $query->whereBetween('check_in_date', [$request->check_in_date, $request->check_out_date])
                          ->orWhereBetween('check_out_date', [$request->check_in_date, $request->check_out_date])
                          ->orWhere(function($q) use ($request) {
                              $q->where('check_in_date', '<=', $request->check_in_date)
                                ->where('check_out_date', '>=', $request->check_out_date);
                          });
                })
                ->exists();

            if ($conflictingBookings) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Room is not available for the selected dates.'
                ], 422);
            }

            $data = $request->only([
                'room_id', 'guest_name', 'guest_email', 'guest_phone',
                'guest_type', 'guest_national_id', 'guest_passport', 'guest_country',
                'number_of_guests', 'check_in_date', 'check_out_date',
                'total_amount', 'notes'
            ]);

            $data['paid_amount'] = $request->paid_amount ?? 0;
            $data['status'] = 'checked_in'; // Walk-in is directly checked in
            $data['actual_check_in_at'] = now();

            CheckIn::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Walk-in check-in created successfully.',
                'redirect' => route('checkin.walkin.index'),
                'delay' => 2000
            ]);
        }

        return view('check-ins.walkin.form', compact('form', 'rooms'));
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
                'message' => 'Walk-in check-in updated successfully.',
                'redirect' => route('checkin.walkin.index'),
                'delay' => 2000
            ]);
        }

        return view('check-ins.walkin.form', compact('form', 'rooms'));
    }

    public function delete($id)
    {
        $checkIn = CheckIn::findOrFail($id);
        $checkIn->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Walk-in check-in deleted successfully.',
            'redirect' => route('checkin.walkin.index'),
            'delay' => 2000
        ]);
    }
}
