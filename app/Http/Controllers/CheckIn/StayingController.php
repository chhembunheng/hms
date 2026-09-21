<?php

namespace App\Http\Controllers\CheckIn;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\Room;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\DataTables\CheckIns\StayingDataTable;

class StayingController extends Controller
{
    public function index(StayingDataTable $dataTable)
    {
        return $dataTable->render('check-ins.staying.index');
    }

    public function checkOut(Request $request, $id)
    {
        $checkIn = CheckIn::with(['checkInRooms.room', 'room'])->findOrFail($id);

        if ($checkIn->status !== 'checked_in') {
            return response()->json([
                'status' => 'error',
                'message' => 'Cannot check-out this guest.'
            ], 422);
        }

        // Validate payment data
        $rules = [
            'paid_amount' => 'required|numeric|min:0',
            'payment_method' => 'nullable|string|max:50',
            'notes' => 'nullable|string|max:1000',
        ];

        $request->validate($rules);

        try {
            DB::beginTransaction();

            // Determine room description
            $roomNumbers = $checkIn->checkInRooms->map(fn($cr) => $cr->room?->room_number)->filter()->implode(', ');
            if (empty($roomNumbers) && $checkIn->room) {
                $roomNumbers = $checkIn->room->room_number;
            }

            // Unbilled Services (tours, laundry, spa, etc.)
            $unbilledServices = \App\Models\CheckInService::with('service')
                ->where('check_in_id', $checkIn->id)
                ->where('is_billed', false)
                ->get();

            // Unbilled Room-Charged Airport Transfers
            $unbilledTransfers = \App\Models\AirportTransfer::where('check_in_id', $checkIn->id)
                ->where('is_charged_to_room', true)
                ->where('status', '!=', 'cancelled')
                ->get();

            $grandTotal = (float) $checkIn->total_amount;
            $items = [
                [
                    'description' => 'Room charges for Room ' . ($roomNumbers ?: 'N/A') . ' (' . ucfirst(str_replace('_', ' ', $checkIn->billing_type ?? 'night')) . ')',
                    'quantity' => 1,
                    'unit_price' => (float) $checkIn->total_amount,
                    'total' => (float) $checkIn->total_amount,
                ]
            ];

            foreach ($unbilledServices as $svc) {
                $svcTotal = (float) $svc->total_price;
                $grandTotal += $svcTotal;
                $items[] = [
                    'description' => ($svc->service ? $svc->service->name_en : 'Service') . ' (' . $svc->quantity . ' ' . ($svc->service ? $svc->service->unit : 'item') . ')',
                    'quantity' => (float) $svc->quantity,
                    'unit_price' => (float) $svc->unit_price,
                    'total' => $svcTotal,
                ];
            }

            foreach ($unbilledTransfers as $tr) {
                $trTotal = (float) $tr->price;
                $grandTotal += $trTotal;
                $items[] = [
                    'description' => 'Airport ' . ucfirst($tr->transfer_type) . ' (' . ucfirst($tr->vehicle_type) . ' - ' . ($tr->flight_number ?: 'SAI') . ')',
                    'quantity' => 1,
                    'unit_price' => $trTotal,
                    'total' => $trTotal,
                ];
            }

            $checkIn->update([
                'status' => 'checked_out',
                'actual_check_out_at' => now(),
                'paid_amount' => $request->paid_amount,
                'notes' => $request->notes,
            ]);

            // Mark services as billed
            $unbilledServices->each->update(['is_billed' => true]);

            $rate = active_exchange_rate();
            $paymentMethod = $request->payment_method ?: 'cash_usd';

            // Generate invoice with all items
            $invoice = Invoice::create([
                'check_in_id' => $checkIn->id,
                'guest_id' => $checkIn->guest_id,
                'subtotal' => $grandTotal,
                'total_amount' => $grandTotal,
                'paid_amount' => $request->paid_amount,
                'balance_amount' => max(0, $grandTotal - $request->paid_amount),
                'payment_method' => $paymentMethod,
                'status' => ($request->paid_amount >= $grandTotal) ? 'paid' : 'partially_paid',
                'invoice_date' => now()->toDateString(),
                'due_date' => now()->addDays(30)->toDateString(),
                'notes' => 'Auto-generated invoice for check-out. Rate: 1 USD = ' . number_format($rate) . ' KHR',
                'items' => $items,
            ]);

            // Create Payment record if paid amount > 0
            if ($request->paid_amount > 0) {
                Payment::create([
                    'invoice_id' => $invoice->id,
                    'amount' => $request->paid_amount,
                    'payment_method' => $paymentMethod,
                    'payment_date' => now(),
                    'reference_number' => 'PAY-' . strtoupper(Str::random(8)),
                    'notes' => 'Checkout settlement payment (' . format_dual_currency($request->paid_amount) . ')',
                    'processed_by' => auth()->id(),
                ]);
            }

            // Update room statuses to "Cleaning"
            $cleaningStatus = \App\Models\RoomStatus::where('name_en', 'Cleaning')->first();
            if ($cleaningStatus) {
                $roomIds = $checkIn->checkInRooms->pluck('room_id')->filter()->toArray();
                if (empty($roomIds) && $checkIn->room_id) {
                    $roomIds = [$checkIn->room_id];
                }
                if (!empty($roomIds)) {
                    Room::whereIn('id', $roomIds)->update(['status_id' => $cleaningStatus->id]);
                }
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Guest checked out and invoice generated successfully.',
                'redirect' => route('checkout.invoice.show', $invoice->id),
                'delay' => 2000
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Check-out failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
