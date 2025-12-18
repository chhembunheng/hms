<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\DataTables\Rooms\RoomStatusDataTable;
use App\Models\RoomStatus;
use Illuminate\Http\Request;

class RoomStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RoomStatusDataTable $dataTable)
    {
        return $dataTable->render('rooms.room-statuses.index');
    }

    public function add(Request $request)
    {
        $form = new RoomStatus();

        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|string|max:255|unique:room_statuses',
                'color' => 'required|string|max:7',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            RoomStatus::create($request->all());

            return redirect()->route('rooms.status.index')
                ->with('success', 'Room status created successfully.');
        }

        return view('rooms.room-statuses.form', compact('form'));
    }

    public function edit(Request $request, $id)
    {
        $form = RoomStatus::findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|string|max:255|unique:room_statuses,name,' . $form->id,
                'color' => 'required|string|max:7',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $form->update($request->all());

            return redirect()->route('rooms.status.index')
                ->with('success', 'Room status updated successfully.');
        }

        return view('rooms.room-statuses.form', compact('form'));
    }

    public function delete($id)
    {
        $roomStatus = RoomStatus::findOrFail($id);
        $roomStatus->delete();

        return redirect()->route('rooms.status.index')
            ->with('success', 'Room status deleted successfully.');
    }
}
