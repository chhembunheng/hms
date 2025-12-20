<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTables\Rooms\RoomDataTable;
use App\Models\RoomType;
use App\Models\RoomStatus;

class RoomController extends Controller
{
    public function index(RoomDataTable $dataTable)
    {
        $roomTypes = RoomType::active()->get();
        $roomStatuses = RoomStatus::active()->get();

        return $dataTable->render('rooms.rooms.index', compact('roomTypes', 'roomStatuses'));
    }

    public function add(Request $request)
    {
        $form = new Room();

        if ($request->isMethod('post')) {
            $rules = [
                'room_number' => 'required|string|max:255|unique:rooms',
                'floor' => 'nullable|integer',
                'room_type_id' => 'nullable|exists:room_types,id',
                'status_id' => 'nullable|exists:room_statuses,id',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $data = $request->only(['room_number', 'floor', 'room_type_id', 'status_id']);
            $data['is_active'] = $request->boolean('is_active', false);

            Room::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Room created successfully.',
                'redirect' => route('rooms.list.index'),
                'delay' => 2000
            ]);
        }

        return view('rooms.rooms.form', compact('form'));
    }

    public function edit(Request $request, $id)
    {
        $form = Room::findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'room_number' => 'required|string|max:255|unique:rooms,room_number,' . $id,
                'floor' => 'nullable|integer',
                'room_type_id' => 'nullable|exists:room_types,id',
                'status_id' => 'nullable|exists:room_statuses,id',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $data = $request->only(['room_number', 'floor', 'room_type_id', 'status_id']);
            $data['is_active'] = $request->boolean('is_active', false);

            $form->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Room updated successfully.',
                'redirect' => route('rooms.list.index'),
                'delay' => 2000
            ]);
        }

        return view('rooms.rooms.form', compact('form'));
    }

    public function delete($id)
    {
        try {
            $room = Room::findOrFail($id);

            // Check if room has active check-ins
            if ($room->checkIns()->active()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete room because it has active check-ins.'
                ]);
            }

            $room->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Room deleted successfully.',
                'redirect' => route('rooms.list.index'),
                'delay' => 2000
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete room: ' . $e->getMessage()
            ]);
        }
    }

    public function checkIn()
    {
        $rooms = Room::with(['roomType', 'status'])
            ->active()
            ->orderBy('floor')
            ->orderBy('room_number')
            ->get()
            ->groupBy('floor');

        return view('rooms.rooms.check-in', compact('rooms'));
    }

    public function processCheckIn(Request $request, $id)
    {
        $room = Room::findOrFail($id);

        // Add validation and check-in logic here
        // For now, just return success

        return response()->json([
            'status' => 'success',
            'message' => 'Check-in successful for room ' . $room->room_number,
            'redirect' => route('rooms.check-in'),
            'delay' => 2000
        ]);
    }
}
