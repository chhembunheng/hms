<?php

namespace App\Http\Controllers\Rooms;

use App\Http\Controllers\Controller;
use App\DataTables\Rooms\RoomPricingDataTable;
use App\Models\RoomPricing;
use Illuminate\Http\Request;

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
            $rules = [
                'room_type_id' => 'required|exists:room_types,id',
                'price' => 'required|numeric|min:0',
                'pricing_type' => 'required|in:night,3_hours',
                'currency' => 'required|string|max:3',
                'effective_from' => 'required|date',
                'effective_to' => 'nullable|date|after:effective_from',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            RoomPricing::create($request->all());

            return redirect()->route('rooms.pricing.index')
                ->with('success', __('rooms.room_pricing_created_successfully'));
        }

        return view('rooms.room-pricing.form', compact('form'));
    }

    public function edit(Request $request, $id)
    {
        $form = RoomPricing::findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'room_type_id' => 'required|exists:room_types,id',
                'price' => 'required|numeric|min:0',
                'pricing_type' => 'required|in:night,3_hours',
                'currency' => 'required|string|max:3',
                'effective_from' => 'required|date',
                'effective_to' => 'nullable|date|after:effective_from',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $form->update($request->all());

            return redirect()->route('rooms.pricing.index')
                ->with('success', __('rooms.room_pricing_updated_successfully'));
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



    public function delete($id)
    {
        try {
            $roomPricing = RoomPricing::findOrFail($id);
            $roomPricing->delete();

            return success(message: __('rooms.room_pricing_deleted_successfully'));
        } catch (\Exception $e) {
            return errors("Failed to delete room pricing: " . $e->getMessage());
        }
    }
}
