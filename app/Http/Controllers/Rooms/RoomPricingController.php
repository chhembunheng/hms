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
        $roomTypes = \App\Models\RoomType::active()->get();
        $pricingTypes = [
            'night' => 'Night',
            '3_hours' => '3 Hours',
        ];
        $currencies = get_currencies();

        return $dataTable->render('rooms.room-pricing.index', compact('roomTypes', 'pricingTypes', 'currencies'));
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

            $data = $request->only(['room_type_id', 'price', 'pricing_type', 'currency', 'effective_from', 'effective_to']);
            $data['is_active'] = $request->boolean('is_active', false);

            RoomPricing::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Room pricing created successfully.',
                'redirect' => route('rooms.pricing.index'),
                'delay' => 2000
            ]);
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

            $data = $request->only(['room_type_id', 'price', 'pricing_type', 'currency', 'effective_from', 'effective_to']);
            $data['is_active'] = $request->boolean('is_active', false);

            $form->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Room pricing updated successfully.',
                'redirect' => route('rooms.pricing.index'),
                'delay' => 2000
            ]);
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

            // Check if pricing is currently active or will be active in the future
            $now = now()->toDateString();
            if (($roomPricing->effective_from <= $now && ($roomPricing->effective_to === null || $roomPricing->effective_to >= $now))) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Cannot delete room pricing that is currently active or will be active in the future.'
                ]);
            }

            $roomPricing->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Room pricing deleted successfully.',
                'redirect' => route('rooms.pricing.index'),
                'delay' => 2000
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete room pricing: ' . $e->getMessage()
            ]);
        }
    }
}
