<?php

namespace App\Http\Controllers\CheckIn;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\CheckInRoom;
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
                'room_ids' => 'required|array',
                'room_ids.*' => 'required|integer|exists:rooms,id',
                'room_guests' => 'required|json',
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'nullable|email',
                'guest_phone' => 'nullable|string|max:20',
                'guest_type' => 'required|in:national,international',
                'guest_national_id' => 'required_if:guest_type,national|string|max:20',
                'guest_passport' => 'required_if:guest_type,international|string|max:20',
                'guest_country' => 'required_if:guest_type,international|string|max:100',
                'billing_type' => 'required|in:night,3_hours',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_in_time' => 'required_if:billing_type,3_hours|date_format:H:i',
                'check_out_date' => 'required|date|after_or_equal:check_in_date',
                'check_out_time' => 'required_if:billing_type,3_hours|date_format:H:i',
                'total_days' => 'required|integer|min:1',
                'total_amount' => 'required|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0|lte:total_amount',
                'notes' => 'nullable|string|max:1000',
            ];

            $request->validate($rules);

            // Parse room IDs and guest data
            $roomIds = explode(',', $request->room_ids);
            $roomIds = array_map('intval', array_filter($roomIds));
            $roomGuests = json_decode($request->room_guests, true);

            if (empty($roomIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'At least one room must be selected.'
                ], 422);
            }

            // Validate that we have guest data for all rooms
            foreach ($roomIds as $roomId) {
                if (!isset($roomGuests[$roomId]) || $roomGuests[$roomId] < 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Guest count must be specified for each room.'
                    ], 422);
                }
            }

            // Check if all rooms are available for the selected dates
            $rooms = Room::whereIn('id', $roomIds)->get();
            if ($rooms->count() !== count($roomIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'One or more selected rooms not found.'
                ], 422);
            }

            foreach ($roomIds as $roomId) {
                $conflictingBookings = CheckIn::where('room_id', $roomId)
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
                    $room = $rooms->find($roomId);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Room ' . ($room ? $room->room_number : $roomId) . ' is not available for the selected dates.'
                    ], 422);
                }
            }

            // Calculate total guests
            $totalGuests = array_sum($roomGuests);

            // Create the check-in record (use first room as primary)
            $data = $request->only([
                'guest_name', 'guest_email', 'guest_phone',
                'guest_type', 'guest_national_id', 'guest_passport', 'guest_country',
                'billing_type', 'check_in_date', 'check_in_time', 'check_out_date', 'check_out_time',
                'total_amount', 'notes'
            ]);

            $data['room_id'] = $roomIds[0]; // Primary room
            $data['number_of_guests'] = $totalGuests;
            $data['paid_amount'] = $request->paid_amount ?? 0;
            $data['status'] = 'checked_in';
            $data['actual_check_in_at'] = now();

            $checkIn = CheckIn::create($data);

            // Create CheckInRoom records for all rooms
            foreach ($roomIds as $roomId) {
                $room = $rooms->find($roomId);
                $roomPrice = 0;

                if ($room && $room->roomType) {
                    $pricing = $room->roomType->roomPricings()
                        ->where('is_active', true)
                        ->where('pricing_type', $request->billing_type)
                        ->orderBy('effective_from', 'desc')
                        ->first();

                    if ($pricing) {
                        $roomPrice = $pricing->price;
                    } elseif ($request->billing_type === '3_hours') {
                        // Fallback: convert nightly price to 3-hour price (24/3 = 8 periods per day)
                        $nightlyPricing = $room->roomType->roomPricings()
                            ->where('is_active', true)
                            ->where('pricing_type', 'night')
                            ->orderBy('effective_from', 'desc')
                            ->first();
                        $roomPrice = $nightlyPricing ? $nightlyPricing->price / 8 : 0;
                    }
                }

                CheckInRoom::create([
                    'check_in_id' => $checkIn->id,
                    'room_id' => $roomId,
                    'number_of_guests' => $roomGuests[$roomId],
                    'room_price' => $roomPrice,
                ]);
            }

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
        $form = CheckIn::with('checkInRooms.room.roomType')->findOrFail($id);
        $rooms = Room::with(['roomType', 'status'])->active()->get();

        if ($request->isMethod('post')) {
            $rules = [
                'room_ids' => 'required|array',
                'room_ids.*' => 'required|integer|exists:rooms,id',
                'room_guests' => 'required|json',
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'nullable|email',
                'guest_phone' => 'nullable|string|max:20',
                'guest_type' => 'required|in:national,international',
                'guest_national_id' => 'required_if:guest_type,national|string|max:20',
                'guest_passport' => 'required_if:guest_type,international|string|max:20',
                'guest_country' => 'required_if:guest_type,international|string|max:100',
                'check_in_date' => 'required|date',
                'check_out_date' => 'required|date|after:check_in_date',
                'total_days' => 'required|integer|min:1',
                'total_amount' => 'required|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0|lte:total_amount',
                'status' => 'required|in:confirmed,checked_in,checked_out,cancelled',
                'notes' => 'nullable|string|max:1000',
            ];

            $request->validate($rules);

            // Parse room IDs and guest data
            $roomIds = $request->room_ids; // Already an array from validation
            $roomGuests = json_decode($request->room_guests, true);

            if (empty($roomIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'At least one room must be selected.'
                ], 422);
            }

            // Validate that we have guest data for all rooms
            foreach ($roomIds as $roomId) {
                if (!isset($roomGuests[$roomId]) || $roomGuests[$roomId] < 1) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Guest count must be specified for each room.'
                    ], 422);
                }
            }

            // Check if all rooms are available for the selected dates (excluding current check-in)
            $rooms = Room::whereIn('id', $roomIds)->get();
            if ($rooms->count() !== count($roomIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'One or more selected rooms not found.'
                ], 422);
            }

            foreach ($roomIds as $roomId) {
                $conflictingBookings = CheckIn::where('room_id', $roomId)
                    ->where('id', '!=', $form->id) // Exclude current check-in
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
                    $room = $rooms->find($roomId);
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Room ' . ($room ? $room->room_number : $roomId) . ' is not available for the selected dates.'
                    ], 422);
                }
            }

            // Calculate total guests
            $totalGuests = array_sum($roomGuests);

            // Update the check-in record
            $data = $request->only([
                'guest_name', 'guest_email', 'guest_phone',
                'guest_type', 'guest_national_id', 'guest_passport', 'guest_country',
                'check_in_date', 'check_out_date',
                'total_amount', 'paid_amount', 'status', 'notes'
            ]);

            $data['room_id'] = $roomIds[0]; // Primary room
            $data['number_of_guests'] = $totalGuests;

            $form->update($data);

            // Delete existing CheckInRoom records and create new ones
            $form->checkInRooms()->delete();

            foreach ($roomIds as $roomId) {
                $room = $rooms->find($roomId);
                $roomPrice = 0;

                if ($room && $room->roomType) {
                    $pricing = $room->roomType->roomPricings()
                        ->where('is_active', true)
                        ->where('pricing_type', 'night')
                        ->orderBy('effective_from', 'desc')
                        ->first();
                    $roomPrice = $pricing ? $pricing->price : 0;
                }

                CheckInRoom::create([
                    'check_in_id' => $form->id,
                    'room_id' => $roomId,
                    'number_of_guests' => $roomGuests[$roomId],
                    'room_price' => $roomPrice,
                ]);
            }

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
