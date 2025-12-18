<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTables\Rooms\RoomDataTable;

class RoomController extends Controller
{
    public function index(RoomDataTable $dataTable)
    {
        return $dataTable->render('rooms.rooms.index');
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

            Room::create($request->all());

            return redirect()->route('rooms.list.index')
                ->with('success', 'Room created successfully.');
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

            $form->update($request->all());

            return redirect()->route('rooms.list.index')
                ->with('success', 'Room updated successfully.');
        }

        return view('rooms.rooms.form', compact('form'));
    }

    public function delete($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('rooms.list.index')
            ->with('success', 'Room deleted successfully.');
    }
}
