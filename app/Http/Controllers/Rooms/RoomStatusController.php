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
                'name_en' => 'required|string|max:255',
                'name_kh' => 'required|string|max:255',
                'color' => 'required|string|max:7',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $data = $request->only(['name_en', 'name_kh', 'color', 'description']);
            $data['is_active'] = $request->boolean('is_active', false);

            RoomStatus::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Room status created successfully.',
                'redirect' => route('rooms.status.index'),
                'delay' => 2000
            ]);
        }

        return view('rooms.room-statuses.form', compact('form'));
    }

    public function edit(Request $request, $id)
    {
        $form = RoomStatus::findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'name_en' => 'required|string|max:255',
                'name_kh' => 'required|string|max:255',
                'color' => 'required|string|max:7',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $data = $request->only(['name_en', 'name_kh', 'color', 'description']);
            $data['is_active'] = $request->boolean('is_active', false);

            $form->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Room status updated successfully.',
                'redirect' => route('rooms.status.index'),
                'delay' => 2000
            ]);
        }

        return view('rooms.room-statuses.form', compact('form'));
    }

    public function delete($id)
    {
        try {
            $roomStatus = RoomStatus::findOrFail($id);

            // Check if room status has associated rooms
            if ($roomStatus->rooms()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete room status because it has associated rooms.'
                ]);
            }

            $roomStatus->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Room status deleted successfully.',
                'redirect' => route('rooms.status.index'),
                'delay' => 2000
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete room status: ' . $e->getMessage()
            ]);
        }
    }
}
