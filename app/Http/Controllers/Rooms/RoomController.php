<?php

namespace App\Http\Controllers\Rooms;

use App\Models\Room;
use App\Models\Floor;
use App\Models\RoomType;
use App\Models\RoomStatus;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DataTables\Rooms\RoomDataTable;

class RoomController extends Controller
{
    public function index(RoomDataTable $dataTable)
    {
        $roomTypes = RoomType::active()->get();
        $roomStatuses = RoomStatus::active()->get();
        $floors = Floor::active()->get();

        return $dataTable->render('rooms.rooms.index', compact('roomTypes', 'roomStatuses', 'floors'));
    }

    public function add(Request $request)
    {
        $form = new Room();

        if ($request->isMethod('post')) {
            $rules = [
                'room_number' => 'required|string|max:255|unique:rooms',
                'floor_id' => 'nullable|exists:floors,id',
                'room_type_id' => 'nullable|exists:room_types,id',
                'status_id' => 'nullable|exists:room_statuses,id',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $data = $request->only(['room_number', 'floor_id', 'room_type_id', 'status_id']);
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
                'floor_id' => 'nullable|exists:floors,id',
                'room_type_id' => 'nullable|exists:room_types,id',
                'status_id' => 'nullable|exists:room_statuses,id',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $data = $request->only(['room_number', 'floor_id', 'room_type_id', 'status_id']);
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
}
