<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\DataTables\Rooms\RoomPricingDataTable;
use App\Models\RoomPricing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomPricingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(RoomPricingDataTable $dataTable)
    {
        return $dataTable->render('rooms.room-pricing.index');
    }

    public function add(Request $request)
    {
        $form = new RoomPricing();

        if ($request->isMethod('post')) {
            $request->validate([
                'room_type_id' => 'required|exists:room_types,id',
                'price' => 'required|numeric|min:0',
                'currency' => 'required|string|max:3',
                'effective_from' => 'required|date',
                'effective_to' => 'nullable|date|after:effective_from',
                'is_active' => 'boolean',
            ]);

            try {
                DB::beginTransaction();

                RoomPricing::create([
                    'room_type_id' => $request->room_type_id,
                    'price' => $request->price,
                    'currency' => $request->currency,
                    'effective_from' => $request->effective_from,
                    'effective_to' => $request->effective_to,
                    'is_active' => $request->is_active ?? true,
                ]);

                DB::commit();

                return redirect()->route('rooms.pricing.index')
                    ->with('success', __('Room pricing created successfully.'));
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()
                    ->with('error', __('Failed to create room pricing. Please try again.'));
            }
        }

        return view('rooms.room-pricing.form', compact('form'));
    }

    public function edit(Request $request, $id)
    {
        $form = RoomPricing::findOrFail($id);

        if ($request->isMethod('post')) {
            $request->validate([
                'room_type_id' => 'required|exists:room_types,id',
                'price' => 'required|numeric|min:0',
                'currency' => 'required|string|max:3',
                'effective_from' => 'required|date',
                'effective_to' => 'nullable|date|after:effective_from',
                'is_active' => 'boolean',
            ]);

            try {
                DB::beginTransaction();

                $form->update([
                    'room_type_id' => $request->room_type_id,
                    'price' => $request->price,
                    'currency' => $request->currency,
                    'effective_from' => $request->effective_from,
                    'effective_to' => $request->effective_to,
                    'is_active' => $request->is_active ?? true,
                ]);

                DB::commit();

                return redirect()->route('rooms.pricing.index')
                    ->with('success', __('Room pricing updated successfully.'));
            } catch (\Exception $e) {
                DB::rollBack();
                return back()->withInput()
                    ->with('error', __('Failed to update room pricing. Please try again.'));
            }
        }

        return view('rooms.room-pricing.form', compact('form'));
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomPricing $roomPricing)
    {
        //
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomPricing $roomPricing)
    {
        try {
            DB::beginTransaction();

            $roomPricing->delete();

            DB::commit();

            return redirect()->route('rooms.pricing.index')
                ->with('success', __('Room pricing deleted successfully.'));
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', __('Failed to delete room pricing. Please try again.'));
        }
    }
}
