<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display payment history.
     */
    public function history(Request $request)
    {
        $query = Payment::with(['invoice.guest', 'invoice.checkIn.room.roomType'])
            ->orderBy('payment_date', 'desc');

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }

        // Filter by amount range
        if ($request->filled('amount_from')) {
            $query->where('amount', '>=', $request->amount_from);
        }

        if ($request->filled('amount_to')) {
            $query->where('amount', '<=', $request->amount_to);
        }

        // Search by invoice number, reference number, or guest name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhereHas('invoice', function ($invoiceQuery) use ($search) {
                      $invoiceQuery->where('invoice_number', 'like', "%{$search}%")
                          ->orWhereHas('guest', function ($guestQuery) use ($search) {
                              $guestQuery->where('full_name', 'like', "%{$search}%");
                          });
                  });
            });
        }

        $payments = $query->paginate(15);

        // Calculate summary statistics
        $totalPayments = Payment::sum('amount');
        $totalPaymentsInPeriod = $query->get()->sum('amount');
        $paymentCount = $query->count();

        return view('payment.history.index', compact(
            'payments',
            'totalPayments',
            'totalPaymentsInPeriod',
            'paymentCount'
        ));
    }
}
