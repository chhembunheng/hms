<?php

namespace App\Http\Controllers\Rooms;

use App\DataTables\Rooms\RoomTypeDataTable;
use App\Http\Controllers\Controller;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $request->validate([
                'name' => 'required|string|max:255|unique:room_types',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            try {
                DB::beginTransaction();

                RoomType::create([
                    'name' => $request->name,
                    'description' => $request->description,
                    'is_active' => $request->is_active ?? true,
                ]);

                DB::commit();

                return redirect()->route('rooms.type.index')
                    ->with('success', __('Room type created successfully.'));
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()
                    ->with('error', __('Failed to create room type. Please try again.'));
            }
        }

        return view('rooms.room-types.form', compact('form'));
    }

    public function edit(Request $request, $id)
    {
        $form = RoomType::findOrFail($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:255|unique:room_types,name,' . $form->id,
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            try {
                DB::beginTransaction();

                $form->update([
                    'name' => $request->name,
                    'description' => $request->description,
                    'is_active' => $request->is_active ?? true,
                ]);

                DB::commit();

                return redirect()->route('rooms.type.index')
                    ->with('success', __('Room type updated successfully.'));
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()
                    ->with('error', __('Failed to update room type. Please try again.'));
            }
        }

        return view('rooms.room-types.form', compact('form'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomType $roomType)
    {
        try {
            DB::beginTransaction();

            // Check if room type is being used
            if ($roomType->rooms()->exists() || $roomType->roomPricings()->exists()) {
                return back()->with('error', __('Cannot delete room type as it is being used by rooms or pricing.'));
            }

            $roomType->delete();

            DB::commit();

            return redirect()->route('rooms.type.index')
                ->with('success', __('Room type deleted successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('Failed to delete room type. Please try again.'));
        }
    }
}
