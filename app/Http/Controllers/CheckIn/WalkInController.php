<?php

namespace App\Http\Controllers\CheckIn;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\CheckInRoom;
use App\Models\Guest;
use App\Models\Room;
use App\Models\Floor;
use Illuminate\Http\Request;
use App\DataTables\CheckIns\WalkInDataTable;
use Illuminate\Support\Facades\Log;

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
            // ->whereHas('status', function($query) {
            //     $query->where('name_en', 'Available');
            // })
            ->active()
            ->get();

        // Load floors with rooms
        $floors = Floor::with([
            'rooms.roomType',
            'rooms.status',
            'rooms' => function($query) {
                $query->active()->orderBy('room_number');
            }
        ])->active()->orderBy('floor_number')->get();

        // Load room pricings separately
        $roomTypeIds = $floors->pluck('rooms')->flatten()->pluck('room_type_id')->unique()->filter();
        $roomPricings = \App\Models\RoomPricing::whereIn('room_type_id', $roomTypeIds)
            ->where('is_active', true)
            ->orderBy('effective_from', 'desc')
            ->get()
            ->groupBy('room_type_id');

        // Attach pricings to room types
        foreach ($floors as $floor) {
            foreach ($floor->rooms as $room) {
                if ($room->roomType && isset($roomPricings[$room->roomType->id])) {
                    $room->roomType->roomPricings = $roomPricings[$room->roomType->id];
                }
            }
        }

        // Define guest types
        $guestTypes = collect([
            (object)['value' => 'national', 'label' => 'National', 'icon' => 'fa-house'],
            (object)['value' => 'international', 'label' => 'International', 'icon' => 'fa-plane']
        ]);

        // Define billing types
        $billingTypes = collect([
            (object)['value' => 'night', 'label' => 'Nightly Rate']
        ]);

        if ($request->isMethod('post')) {
            $rules = [
                'room_ids' => 'required|array',
                'room_ids.*' => 'required|integer|exists:rooms,id',
                'guest_name' => 'required|string|max:255',
                'guest_email' => 'nullable|email',
                'guest_phone' => 'nullable|string|max:20',
                'guest_type' => 'required|in:national,international',
                'guest_national_id' => 'required_if:guest_type,national|string|max:20',
                'guest_passport' => 'required_if:guest_type,international|string|max:20',
                'guest_country' => 'required_if:guest_type,international|string|max:100',
                'billing_type' => 'required|in:night',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after_or_equal:check_in_date',
                'total_days' => 'required|integer|min:1',
                'total_guests' => 'required|integer|min:1',
                'total_amount' => 'required|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0|lte:total_amount',
                'notes' => 'nullable|string|max:1000',
            ];

            $request->validate($rules);

            $roomIds = $request->room_ids;

            if (empty($roomIds)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'At least one room must be selected.'
                ], 422);
            }

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

            // Find or create guest profile
            $guest = $this->findOrCreateGuest($request);

            // Create the check-in record (use first room as primary)
            $data = $request->only([
                'guest_name', 'guest_email', 'guest_phone',
                'guest_type', 'guest_national_id', 'guest_passport', 'guest_country',
                'billing_type', 'check_in_date', 'check_in_time', 'check_out_date', 'check_out_time',
                'total_guests', 'total_amount', 'notes'
            ]);

            $data['guest_id'] = $guest->id;
            $data['room_id'] = $roomIds[0]; // Primary room
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
                    }
                }

                CheckInRoom::create([
                    'check_in_id' => $checkIn->id,
                    'room_id' => $roomId,
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

        return view('check-ins.walkin.form', compact('form', 'rooms', 'floors', 'guestTypes', 'billingTypes'));
    }

    public function edit(Request $request, $id)
    {
        $form = CheckIn::with('checkInRooms.room.roomType')->findOrFail($id);
        $rooms = Room::with(['roomType', 'status'])->active()->get();

        // Load floors with rooms
        $floors = Floor::with([
            'rooms.roomType',
            'rooms.status',
            'rooms' => function($query) {
                $query->active()->orderBy('room_number');
            }
        ])->active()->orderBy('floor_number')->get();

        // Load room pricings separately
        $roomTypeIds = $floors->pluck('rooms')->flatten()->pluck('room_type_id')->unique()->filter();
        $roomPricings = \App\Models\RoomPricing::whereIn('room_type_id', $roomTypeIds)
            ->where('is_active', true)
            ->orderBy('effective_from', 'desc')
            ->get()
            ->groupBy('room_type_id');

        // Attach pricings to room types
        foreach ($floors as $floor) {
            foreach ($floor->rooms as $room) {
                if ($room->roomType && isset($roomPricings[$room->roomType->id])) {
                    $room->roomType->roomPricings = $roomPricings[$room->roomType->id];
                }
            }
        }

        // Define guest types
        $guestTypes = collect([
            (object)['value' => 'national', 'label' => 'National', 'icon' => 'fa-house'],
            (object)['value' => 'international', 'label' => 'International', 'icon' => 'fa-plane']
        ]);

        // Define billing types
        $billingTypes = collect([
            (object)['value' => 'night', 'label' => 'Nightly Rate']
        ]);

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
                'total_guests' => 'required|integer|min:1',
                'total_amount' => 'required|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0|lte:total_amount',
                'status' => 'required|in:confirmed,checked_in,checked_out,cancelled',
                'notes' => 'nullable|string|max:1000',
            ];

            $request->validate($rules);

            // Parse room IDs and guest data
            $roomIds = $request->room_ids; // Already an array from validation
            $roomGuests = json_decode($request->room_guests, true);

            // Ensure roomGuests is an array
            if (!is_array($roomGuests)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid room guest data format.'
                ], 422);
            }

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

            // Update the check-in record
            $data = $request->only([
                'guest_name', 'guest_email', 'guest_phone',
                'guest_type', 'guest_national_id', 'guest_passport', 'guest_country',
                'check_in_date', 'check_out_date',
                'total_guests', 'total_amount', 'paid_amount', 'status', 'notes'
            ]);

            $data['room_id'] = $roomIds[0]; // Primary room

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


        return view('check-ins.walkin.form', compact('form', 'rooms', 'floors', 'guestTypes', 'billingTypes'));
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

    public function getAvailableRooms(Request $request)
    {
        $request->validate([
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after_or_equal:check_in_date',
            'billing_type' => 'required|in:night',
        ]);

        // Get all active rooms with their types and statuses
        $rooms = Room::with(['roomType', 'status', 'floor'])
            ->active()
            ->get();

        // Filter out rooms that are not available for the selected dates
        $availableRooms = $rooms->filter(function($room) use ($request) {
            // Skip if room is not available status
            if (!$room->status || $room->status->name_en !== 'Available') {
                return false;
            }

            // Check for conflicting bookings
            $conflictingBookings = CheckIn::where('room_id', $room->id)
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

            return !$conflictingBookings;
        });        // Group rooms by floor
        $floors = $availableRooms->groupBy('floor_id')->map(function($floorRooms, $floorId) use ($request) {
            $floor = $floorRooms->first()->floor;
            return [
                'id' => $floorId,
                'name' => $floor ? $floor->localized_name : 'Unknown Floor',
                'rooms' => $floorRooms->map(function($room) use ($request) {
                    // Get pricing for the room
                    $nightlyPrice = null;
                    $hourlyPrice = null;

                    if ($room->roomType) {
                        $nightlyPrice = $room->roomType->roomPricings()
                            ->where('is_active', true)
                            ->where('pricing_type', 'night')
                            ->orderBy('effective_from', 'desc')
                            ->first();
                    }

                    return [
                        'id' => $room->id,
                        'number' => $room->room_number,
                        'type' => $room->roomType ? $room->roomType->name_en : 'Standard Room',
                        'type_kh' => $room->roomType ? $room->roomType->name_kh : '',
                        'max_guests' => $room->roomType ? $room->roomType->max_guests : 1,
                        'price_night' => $nightlyPrice ? $nightlyPrice->price : 0,
                        'status' => $room->status ? $room->status->name_en : 'Unknown',
                    ];
                })->sortBy('number')->values()
            ];
        })->values();

        return response()->json([
            'status' => 'success',
            'floors' => $floors,
            'total_rooms' => $availableRooms->count()
        ]);
    }

    /**
     * Find existing guest or create a new one
     */
    private function findOrCreateGuest(Request $request)
    {
        $guestData = [
            'email' => $request->guest_email,
            'phone' => $request->guest_phone,
            'national_id' => $request->guest_national_id,
            'passport' => $request->guest_passport,
        ];

        // Try to find existing guest by unique identifiers
        $guest = null;

        if (!empty($guestData['email'])) {
            $guest = Guest::where('email', $guestData['email'])->first();
        }

        if (!$guest && !empty($guestData['phone'])) {
            $guest = Guest::where('phone', $guestData['phone'])->first();
        }

        if (!$guest && !empty($guestData['national_id'])) {
            $guest = Guest::where('national_id', $guestData['national_id'])->first();
        }

        if (!$guest && !empty($guestData['passport'])) {
            $guest = Guest::where('passport', $guestData['passport'])->first();
        }

        if ($guest) {
            // Update existing guest's visit information
            $guest->update([
                'last_visit_at' => now(),
                'total_visits' => $guest->total_visits + 1,
            ]);
        } else {
            // Parse guest name into first and last name
            $nameParts = explode(' ', trim($request->guest_name), 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';

            // Create new guest
            $guest = Guest::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $request->guest_email,
                'phone' => $request->guest_phone,
                'national_id' => $request->guest_national_id,
                'passport' => $request->guest_passport,
                'guest_type' => $request->guest_type,
                'country' => $request->guest_country,
                'last_visit_at' => now(),
                'total_visits' => 1,
            ]);
        }

        return $guest;
    }
}
