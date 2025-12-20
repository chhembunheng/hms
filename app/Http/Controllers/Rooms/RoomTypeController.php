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
                'name_en' => 'required|string|max:255',
                'name_kh' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $data = $request->only(['name_en', 'name_kh', 'description']);
            $data['is_active'] = $request->boolean('is_active', false);

            RoomType::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Room type created successfully.',
                'redirect' => route('rooms.type.index'),
                'delay' => 2000
            ]);
        }

        return view('rooms.room-types.form', compact('form'));
    }

    public function edit(Request $request, $id)
    {
        $form = RoomType::findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'name_en' => 'required|string|max:255',
                'name_kh' => 'nullable|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $data = $request->only(['name_en', 'name_kh', 'description']);
            $data['is_active'] = $request->boolean('is_active', false);

            $form->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Room type updated successfully.',
                'redirect' => route('rooms.type.index'),
                'delay' => 2000
            ]);
        }

        return view('rooms.room-types.form', compact('form'));
    }

    public function delete($id)
    {
        try {
            $roomType = RoomType::findOrFail($id);

            // Check if room type has associated rooms
            if ($roomType->rooms()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete room type because it has associated rooms.'
                ]);
            }

            // Check if room type has associated pricing
            if ($roomType->roomPricings()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete room type because it has associated pricing.'
                ]);
            }

            $roomType->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Room type deleted successfully.',
                'redirect' => route('rooms.type.index'),
                'delay' => 2000
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete room type: ' . $e->getMessage()
            ]);
        }
    }
}
