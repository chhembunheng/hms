<?php

namespace App\Http\Controllers\CheckIn;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Room;
use Illuminate\Http\Request;
use App\DataTables\CheckIns\ExtendDataTable;

class ExtendController extends Controller
{
    public function index(ExtendDataTable $dataTable)
    {
        return $dataTable->render('check-ins.extend.index');
    }

    public function edit(Request $request, $id)
    {
        $form = CheckIn::findOrFail($id);
        $rooms = Room::with(['roomType', 'status'])->active()->get();

        if ($request->isMethod('post')) {
            $rules = [
                'check_out_date' => 'required|date|after:check_in_date',
                'total_amount' => 'required|numeric|min:0',
                'paid_amount' => 'nullable|numeric|min:0|lte:total_amount',
                'notes' => 'nullable|string|max:1000',
            ];

            $request->validate($rules);

            $data = $request->only([
                'check_out_date', 'total_amount', 'paid_amount', 'notes'
            ]);

            $form->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Stay extended successfully.',
                'redirect' => route('checkin.extend.index'),
                'delay' => 2000
            ]);
        }

        return view('check-ins.extend.form', compact('form', 'rooms'));
    }
}
