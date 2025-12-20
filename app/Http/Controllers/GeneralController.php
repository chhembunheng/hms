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

        $query = Room::with(['roomType', 'status'])
            ->where('is_active', true)
            ->whereHas('status', function ($q) {
                $q->where('name_en', 'available');
            });

        if ($search) {
            $query->where('room_number', 'like', '%' . $search . '%');
        }

        $rooms = $query->paginate($perPage, ['*'], 'page', $page);

        $results = $rooms->map(function ($room) {
            return [
                'id' => $room->id,
                'text' => $room->room_number . ' - ' . ($room->roomType->name_en ?? 'N/A') . ' (' . $room->floor . 'F)',
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

        $room = Room::with(['roomType.roomPricings' => function ($q) {
            $q->where('is_active', true)
              ->where('pricing_type', 'night') // Assuming per night for walk-in
              ->orderBy('effective_from', 'desc');
        }])->find($roomId);

        if (!$room) {
            return response()->json(['error' => 'Room not found'], 404);
        }

        $pricing = $room->roomType->roomPricings->first();
        $price = $pricing ? $pricing->price : 0;

        return response()->json([
            'price' => $price,
            'currency' => $pricing ? $pricing->currency : 'USD',
        ]);
    }
}
