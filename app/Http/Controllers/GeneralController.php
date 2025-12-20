<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomPricing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GeneralController extends Controller
{
    /**
     * Get available rooms for select2.
     */
    public function getRooms(Request $request): JsonResponse
    {
        $search = $request->get('search');
        $page = $request->get('page', 1);
        $perPage = 20;
        $checkInDate = $request->get('check_in_date');
        $checkOutDate = $request->get('check_out_date');
        $billingType = $request->get('billing_type');

        $query = Room::with(['roomType', 'status'])
            ->where('is_active', true)
            ->whereHas('status', function ($q) {
                $q->where('name_en', 'available');
            });

        if ($search) {
            $query->where('room_number', 'like', '%' . $search . '%');
        }

        // Filter by availability if dates are provided
        if ($checkInDate && $checkOutDate) {
            if ($billingType === '3_hours' && $request->get('check_in_time') && $request->get('check_out_time')) {
                // For 3-hour bookings, check time slot conflicts
                $checkInTime = $request->get('check_in_time');
                $checkOutTime = $request->get('check_out_time');

                $query->whereDoesntHave('checkIns', function ($q) use ($checkInDate, $checkOutDate, $checkInTime, $checkOutTime) {
                    $q->whereIn('status', ['confirmed', 'checked_in'])
                      ->where('billing_type', '3_hours')
                      ->where(function ($query) use ($checkInDate, $checkOutDate, $checkInTime, $checkOutTime) {
                          // Same date time overlap
                          $query->where('check_in_date', $checkInDate)
                                ->where(function ($q) use ($checkInTime, $checkOutTime) {
                                    $q->whereBetween('check_in_time', [$checkInTime, $checkOutTime])
                                      ->orWhereBetween('check_out_time', [$checkInTime, $checkOutTime])
                                      ->orWhere(function ($subQ) use ($checkInTime, $checkOutTime) {
                                          $subQ->where('check_in_time', '<=', $checkInTime)
                                               ->where('check_out_time', '>=', $checkOutTime);
                                      });
                                });
                      });
                });
            } else {
                // For nightly bookings, check date conflicts
                $query->whereDoesntHave('checkIns', function ($q) use ($checkInDate, $checkOutDate) {
                    $q->whereIn('status', ['confirmed', 'checked_in'])
                      ->where(function ($query) use ($checkInDate, $checkOutDate) {
                          $query->whereBetween('check_in_date', [$checkInDate, $checkOutDate])
                                ->orWhereBetween('check_out_date', [$checkInDate, $checkOutDate])
                                ->orWhere(function ($q) use ($checkInDate, $checkOutDate) {
                                    $q->where('check_in_date', '<=', $checkInDate)
                                      ->where('check_out_date', '>=', $checkOutDate);
                                });
                      });
                });
            }
        }

        $rooms = $query->paginate($perPage, ['*'], 'page', $page);

        $results = $rooms->map(function ($room) use ($billingType) {
            $text = $room->room_number . ' - ' . ($room->roomType->name_en ?? 'N/A') . ' (' . $room->floor . 'F)';

            // Add pricing information if billing type is specified
            if ($billingType && $room->roomType) {
                $pricing = $room->roomType->roomPricings()
                    ->where('is_active', true)
                    ->where('pricing_type', $billingType)
                    ->orderBy('effective_from', 'desc')
                    ->first();

                $price = $pricing ? $pricing->price : 0;

                // If 3-hour pricing not found, convert from nightly price
                if (!$pricing && $billingType === '3_hours') {
                    $nightlyPricing = $room->roomType->roomPricings()
                        ->where('is_active', true)
                        ->where('pricing_type', 'night')
                        ->orderBy('effective_from', 'desc')
                        ->first();
                    $price = $nightlyPricing ? $nightlyPricing->price / 8 : 0; // 24 hours / 3 hours = 8 periods
                }

                if ($price > 0) {
                    $currency = $pricing ? $pricing->currency : 'USD';
                    $unit = $billingType === '3_hours' ? '3hrs' : 'night';
                    $text .= ' - ' . $currency . '$' . number_format($price, 2) . '/' . $unit;
                }
            }

            return [
                'id' => $room->id,
                'text' => $text,
            ];
        });

        return response()->json([
            'results' => $results,
            'pagination' => [
                'more' => $rooms->hasMorePages(),
            ],
        ]);
    }

    /**
     * Get room price and details.
     */
    public function getRoomDetails(Request $request): JsonResponse
    {
        $roomId = $request->get('room_id');
        $roomIds = $request->get('room_ids');
        $billingType = $request->get('billing_type', 'night');

        // Handle both single room and multiple rooms
        if ($roomIds) {
            $roomIdsArray = explode(',', $roomIds);
        } elseif ($roomId) {
            $roomIdsArray = [$roomId];
        } else {
            return response()->json(['error' => 'No room IDs provided'], 400);
        }

        $rooms = Room::with(['roomType.roomPricings' => function ($q) use ($billingType) {
            $q->where('is_active', true)
              ->where('pricing_type', $billingType)
              ->orderBy('effective_from', 'desc');
        }])->whereIn('id', $roomIdsArray)->get();

        if ($rooms->isEmpty()) {
            return response()->json(['error' => 'Rooms not found'], 404);
        }

        $roomDetails = $rooms->map(function ($room) use ($billingType) {
            $pricing = $room->roomType->roomPricings->first();
            $price = $pricing ? $pricing->price : 0;

            // If 3-hour pricing not found, convert from nightly price
            if (!$pricing && $billingType === '3_hours') {
                $nightlyPricing = $room->roomType->roomPricings()
                    ->where('is_active', true)
                    ->where('pricing_type', 'night')
                    ->orderBy('effective_from', 'desc')
                    ->first();
                $price = $nightlyPricing ? $nightlyPricing->price / 8 : 0; // 24 hours / 3 hours = 8 periods
            }

            $result = [
                'id' => $room->id,
                'room_number' => $room->room_number,
                'room_type' => $room->roomType->name_en ?? 'N/A',
                'price' => $price,
                'currency' => $pricing ? $pricing->currency : 'USD',
            ];

            // Add both price types for frontend calculation
            if ($billingType === '3_hours') {
                $result['price_3_hours'] = $price;
                // Also include nightly price for reference
                $nightlyPricing = $room->roomType->roomPricings()
                    ->where('is_active', true)
                    ->where('pricing_type', 'night')
                    ->orderBy('effective_from', 'desc')
                    ->first();
                $result['price'] = $nightlyPricing ? $nightlyPricing->price : 0;
            }

            return $result;
        });

        // For backward compatibility, if only one room was requested, return single room format
        if (count($roomIdsArray) === 1) {
            $singleRoom = $roomDetails->first();
            return response()->json([
                'price' => $singleRoom['price'],
                'price_3_hours' => $singleRoom['price_3_hours'] ?? null,
                'currency' => $singleRoom['currency'],
                'room_number' => $singleRoom['room_number'],
                'room_type' => $singleRoom['room_type'],
            ]);
        }

        return response()->json([
            'rooms' => $roomDetails,
            'total_price' => $roomDetails->sum('price'),
            'currency' => $roomDetails->first()['currency'] ?? 'USD',
        ]);
    }
}
