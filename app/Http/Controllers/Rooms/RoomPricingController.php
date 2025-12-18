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
                'currency' => 'required|string|max:3',
                'effective_from' => 'required|date',
                'effective_to' => 'nullable|date|after:effective_from',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            RoomPricing::create($request->all());

            return redirect()->route('rooms.pricing.index')
                ->with('success', 'Room pricing created successfully.');
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
                'currency' => 'required|string|max:3',
                'effective_from' => 'required|date',
                'effective_to' => 'nullable|date|after:effective_from',
                'is_active' => 'nullable|boolean',
            ];

            $request->validate($rules);

            $form->update($request->all());

            return redirect()->route('rooms.pricing.index')
                ->with('success', 'Room pricing updated successfully.');
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
        $roomPricing = RoomPricing::findOrFail($id);
        $roomPricing->delete();

        return redirect()->route('rooms.pricing.index')
            ->with('success', 'Room pricing deleted successfully.');
    }
}
