<?php

namespace App\Http\Controllers\Rooms;

use App\DataTables\Rooms\RoomTypeDataTable;
use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RoomTypeDataTable $dataTable)
    {
        return $dataTable->render('rooms.room-types.index');
    }

    public function add(Request $request)
    {
        $form = new RoomType();

        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|string|max:255|unique:room_types',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            RoomType::create($request->all());

            return redirect()->route('rooms.type.index')
                ->with('success', 'Room type created successfully.');
        }

        return view('rooms.room-types.form', compact('form'));
    }

    public function edit(Request $request, $id)
    {
        $form = RoomType::findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'name' => 'required|string|max:255|unique:room_types,name,' . $form->id,
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $form->update($request->all());

            return redirect()->route('rooms.type.index')
                ->with('success', 'Room type updated successfully.');
        }

        return view('rooms.room-types.form', compact('form'));
    }

    public function delete($id)
    {
        try {
            $roomType = RoomType::findOrFail($id);
            $roomType->delete();

            return success(message: "Room type deleted successfully.");
        } catch (\Exception $e) {
            return errors("Failed to delete room type: " . $e->getMessage());
        }
    }
}
