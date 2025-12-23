<?php

namespace App\Http\Controllers\CheckIn;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Room;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\DataTables\CheckIns\StayingDataTable;

class StayingController extends Controller
{
    public function index(StayingDataTable $dataTable)
    {
        return $dataTable->render('check-ins.staying.index');
    }

    public function checkOut(Request $request, $id)
    {
        $checkIn = CheckIn::findOrFail($id);

        if ($checkIn->status !== 'checked_in') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot check-out this guest.'
            ], 422);
        }

        // Validate payment data
        $rules = [
            'paid_amount' => 'required|numeric|min:0|max:' . $checkIn->total_amount,
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ];

        $request->validate($rules);

        $checkIn->update([
            'status' => 'checked_out',
            'actual_check_out_at' => now(),
            'paid_amount' => $request->paid_amount,
            'notes' => $request->notes,
        ]);

        // Generate invoice
        $invoice = Invoice::create([
            'check_in_id' => $checkIn->id,
            'guest_id' => $checkIn->guest_id,
            'subtotal' => $checkIn->total_amount,
            'total_amount' => $checkIn->total_amount,
            'paid_amount' => $request->paid_amount,
            'balance_amount' => $checkIn->total_amount - $request->paid_amount,
            'payment_method' => $request->payment_method,
            'status' => ($request->paid_amount >= $checkIn->total_amount) ? 'paid' : 'partially_paid',
            'invoice_date' => now()->toDateString(),
            'due_date' => now()->addDays(30)->toDateString(),
            'notes' => 'Auto-generated invoice for check-out',
            'items' => [
                [
                    'description' => 'Room charges for ' . $checkIn->room->room_number,
                    'quantity' => 1,
                    'unit_price' => $checkIn->total_amount,
                    'total' => $checkIn->total_amount,
                ]
            ],
        ]);

        // Update room statuses to "Cleaning"
        $cleaningStatus = \App\Models\RoomStatus::where('name_en', 'Cleaning')->first();
        if ($cleaningStatus) {
            $roomIds = $checkIn->checkInRooms->pluck('room_id')->toArray();
            \App\Models\Room::whereIn('id', $roomIds)->update(['status_id' => $cleaningStatus->id]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Guest checked out and invoice generated successfully.',
            'redirect' => route('checkout.invoice.show', $invoice->id),
            'delay' => 2000
        ]);
    }
}
