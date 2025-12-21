<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\DataTables\Rooms\FloorDataTable;

class FloorController extends Controller
{
    public function index(FloorDataTable $dataTable)
    {
        return $dataTable->render('rooms.floors.index');
    }

    public function add(Request $request)
    {
        $form = new Floor();

        if ($request->isMethod('post')) {
            $rules = [
                'floor_number' => 'required|integer|unique:floors',
                'name_en' => 'required|string|max:255',
                'name_kh' => 'required|string|max:255',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $data = $request->only(['floor_number', 'name_en', 'name_kh']);
            $data['is_active'] = $request->boolean('is_active', false);

            Floor::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Floor created successfully.',
                'redirect' => route('rooms.floor.index'),
                'delay' => 2000
            ]);
        }

        return view('rooms.floors.form', compact('form'));
    }

    public function edit(Request $request, $id)
    {
        $form = Floor::findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'floor_number' => 'required|integer|unique:floors,floor_number,' . $id,
                'name_en' => 'required|string|max:255',
                'name_kh' => 'required|string|max:255',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $data = $request->only(['floor_number', 'name_en', 'name_kh']);
            $data['is_active'] = $request->boolean('is_active', false);

            $form->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Floor updated successfully.',
                'redirect' => route('rooms.floor.index'),
                'delay' => 2000
            ]);
        }

        return view('rooms.floors.form', compact('form'));
    }

    public function delete($id)
    {
        try {
            $floor = Floor::findOrFail($id);

            // Check if floor has rooms
            if ($floor->rooms()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete floor because it has rooms.'
                ]);
            }

            $floor->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Floor deleted successfully.',
                'redirect' => route('rooms.floor.index'),
                'delay' => 2000
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete floor: ' . $e->getMessage()
            ]);
        }
    }
}
