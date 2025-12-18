<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\DataTables\Rooms\RoomStatusDataTable;
use App\Models\RoomStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $request->validate([
                'name' => 'required|string|max:255|unique:room_statuses',
                'color' => 'required|string|max:7',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            try {
                DB::beginTransaction();

                RoomStatus::create([
                    'name' => $request->name,
                    'color' => $request->color,
                    'description' => $request->description,
                    'is_active' => $request->is_active ?? true,
                ]);

                DB::commit();

                return redirect()->route('rooms.status.index')
                    ->with('success', __('Room status created successfully.'));
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()
                    ->with('error', __('Failed to create room status. Please try again.'));
            }
        }

        return view('rooms.room-statuses.form', compact('form'));
    }

    public function edit(Request $request, $id)
    {
        $form = RoomStatus::findOrFail($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'name' => 'required|string|max:255|unique:room_statuses,name,' . $form->id,
                'color' => 'required|string|max:7',
                'description' => 'nullable|string',
                'is_active' => 'boolean',
            ]);

            try {
                DB::beginTransaction();

                $form->update([
                    'name' => $request->name,
                    'color' => $request->color,
                    'description' => $request->description,
                    'is_active' => $request->is_active ?? true,
                ]);

                DB::commit();

                return redirect()->route('rooms.status.index')
                    ->with('success', __('Room status updated successfully.'));
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()
                    ->with('error', __('Failed to update room status. Please try again.'));
            }
        }

        return view('rooms.room-statuses.form', compact('form'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomStatus $roomStatus)
    {
        try {
            DB::beginTransaction();

            // Check if room status is being used
            if ($roomStatus->rooms()->exists()) {
                return back()->with('error', __('Cannot delete room status as it is being used by rooms.'));
            }

            $roomStatus->delete();

            DB::commit();

            return redirect()->route('rooms.status.index')
                ->with('success', __('Room status deleted successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('Failed to delete room status. Please try again.'));
        }
    }
}
