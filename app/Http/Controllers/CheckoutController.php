<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use Illuminate\Http\Request;
use App\DataTables\CheckoutDataTable;

class CheckoutController extends Controller
{
    public function index(CheckoutDataTable $dataTable)
    {
        return $dataTable->render('checkouts.index');
    }

    public function edit(Request $request, $id)
    {
        $checkout = CheckIn::where('status', 'checked_out')->findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'paid_amount' => 'required|numeric|min:0|max:' . $checkout->total_amount,
                'actual_check_out_at' => 'nullable|date',
                'notes' => 'nullable|string|max:1000',
            ];

            $request->validate($rules);

            $data = $request->only(['paid_amount', 'actual_check_out_at', 'notes']);

            $checkout->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Checkout updated successfully.',
                'redirect' => route('checkout.index'),
                'delay' => 2000
            ]);
        }

        return view('checkouts.form', compact('checkout'));
    }

    public function delete($id)
    {
        try {
            $checkout = CheckIn::where('status', 'checked_out')->findOrFail($id);

            // Optional: Add checks if needed

            $checkout->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Checkout deleted successfully.',
                'redirect' => route('checkout.index'),
                'delay' => 2000
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete checkout: ' . $e->getMessage()
            ]);
        }
    }
}
