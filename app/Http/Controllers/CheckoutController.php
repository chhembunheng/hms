<?php

namespace App\Http\Controllers;

use App\Models\CheckIn;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

    public function showInvoice($id)
    {
        $invoice = Invoice::with(['checkIn.room.roomType', 'guest'])->findOrFail($id);

        return view('checkout.invoice.show', compact('invoice'));
    }

    public function printInvoice($id)
    {
        $invoice = Invoice::with(['checkIn.room.roomType', 'guest'])->findOrFail($id);

        return view('checkout.invoice.print', compact('invoice'));
    }

    public function processPayment(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);

        if ($request->isMethod('post')) {
            $rules = [
                'payment_amount' => 'required|numeric|min:0.01|max:' . $invoice->balance_amount,
                'payment_method' => 'required|string|max:50',
                'payment_date' => 'required|date',
                'reference_number' => 'nullable|string|max:100',
                'notes' => 'nullable|string|max:1000',
            ];

            $request->validate($rules);

            try {
                DB::beginTransaction();

                $newPaidAmount = $invoice->paid_amount + $request->payment_amount;
                $newBalanceAmount = $invoice->total_amount - $newPaidAmount;

                // Update invoice
                $invoice->update([
                    'paid_amount' => $newPaidAmount,
                    'balance_amount' => $newBalanceAmount,
                    'payment_method' => $request->payment_method,
                    'status' => $newBalanceAmount <= 0 ? 'paid' : 'partially_paid',
                ]);

                // Create payment record
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $request->payment_amount,
                    'payment_method' => $request->payment_method,
                    'payment_date' => $request->payment_date,
                    'reference_number' => $request->reference_number,
                    'notes' => $request->notes,
                    'processed_by' => auth()->id(),
                ]);

                DB::commit();

                return response()->json([
                    'status' => 'success',
                    'message' => __('checkout.payment_processed_successfully'),
                    'redirect' => route('checkout.invoice.show', $invoice->id),
                    'delay' => 2000
                ]);
            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'status' => 'error',
                    'message' => __('checkout.payment_processing_failed') . ': ' . $e->getMessage()
                ]);
            }
        }

        return view('checkout.payment.form', compact('invoice'));
    }
}
