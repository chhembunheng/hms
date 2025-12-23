<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillingController extends Controller
{
    /**
     * Display a listing of payment invoices.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['guest', 'checkIn.room.roomType'])
            ->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }

        // Search by invoice number or guest name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('guest', function ($guestQuery) use ($search) {
                      $guestQuery->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->paginate(15);

        return view('billing.list.index', compact('invoices'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit(Request $request, Invoice $invoice)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'status' => 'required|in:paid,unpaid,partially_paid,overdue',
                'notes' => 'nullable|string|max:1000',
            ]);

            try {
                DB::beginTransaction();

                $invoice->update([
                    'status' => $request->status,
                    'notes' => $request->notes,
                ]);

                DB::commit();

                return redirect()->route('billing.list.index')
                    ->with('success', __('billing.invoice_updated_successfully'));
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Invoice update failed: ' . $e->getMessage());

                return back()->withInput()
                    ->with('error', __('billing.invoice_update_failed'));
            }
        }

        return view('billing.list.form', compact('invoice'));
    }

    /**
     * Remove the specified invoice.
     */
    public function delete(Invoice $invoice)
    {
        try {
            // Only allow deletion of unpaid invoices
            if ($invoice->paid_amount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => __('billing.cannot_delete_paid_invoice')
                ], 422);
            }

            $invoice->delete();

            return response()->json([
                'success' => true,
                'message' => __('billing.invoice_deleted_successfully')
            ]);
        } catch (\Exception $e) {
            Log::error('Invoice deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('billing.invoice_deletion_failed')
            ], 500);
        }
    }

    /**
     * Display invoice history.
     */
    public function history(Request $request)
    {
        $query = Invoice::with(['guest', 'checkIn.room.roomType'])
            ->onlyTrashed()
            ->orderBy('deleted_at', 'desc');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('deleted_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('deleted_at', '<=', $request->date_to);
        }

        // Search by invoice number or guest name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('guest', function ($guestQuery) use ($search) {
                      $guestQuery->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->paginate(15);

        return view('billing.history.index', compact('invoices'));
    }

    /**
     * Display cancelled invoices.
     */
    public function voidInvoices(Request $request)
    {
        $query = Invoice::with(['guest', 'checkIn.room.roomType'])
            ->where('status', 'cancelled')
            ->orderBy('updated_at', 'desc');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('updated_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('updated_at', '<=', $request->date_to);
        }

        // Search by invoice number or guest name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('guest', function ($guestQuery) use ($search) {
                      $guestQuery->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->paginate(15);

        return view('billing.void.index', compact('invoices'));
    }

    /**
     * Show the form for editing void invoice.
     */
    public function editVoid(Request $request, Invoice $invoice)
    {
        // Ensure invoice is cancelled
        if ($invoice->status !== 'cancelled') {
            abort(404);
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'notes' => 'nullable|string|max:1000',
            ]);

            try {
                $invoice->update([
                    'notes' => $request->notes,
                ]);

                return redirect()->route('billing.void.index')
                    ->with('success', __('billing.void_invoice_updated_successfully'));
            } catch (\Exception $e) {
                Log::error('Void invoice update failed: ' . $e->getMessage());

                return back()->withInput()
                    ->with('error', __('billing.void_invoice_update_failed'));
            }
        }

        return view('billing.void.edit', compact('invoice'));
    }

    /**
     * Remove the specified void invoice.
     */
    public function deleteVoid(Invoice $invoice)
    {
        // Ensure invoice is cancelled
        if ($invoice->status !== 'cancelled') {
            return response()->json([
                'success' => false,
                'message' => __('billing.invalid_operation')
            ], 422);
        }

        try {
            $invoice->delete();

            return response()->json([
                'success' => true,
                'message' => __('billing.void_invoice_deleted_successfully')
            ]);
        } catch (\Exception $e) {
            Log::error('Void invoice deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('billing.void_invoice_deletion_failed')
            ], 500);
        }
    }

    /**
     * Display pending cancel invoices.
     */
    public function pendingCancelInvoices(Request $request)
    {
        $query = Invoice::with(['guest', 'checkIn.room.roomType'])
            ->where('status', 'pending_cancellation')
            ->orderBy('updated_at', 'desc');

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('updated_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('updated_at', '<=', $request->date_to);
        }

        // Search by invoice number or guest name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('invoice_number', 'like', "%{$search}%")
                  ->orWhereHas('guest', function ($guestQuery) use ($search) {
                      $guestQuery->where('full_name', 'like', "%{$search}%");
                  });
            });
        }

        $invoices = $query->paginate(15);

        return view('billing.pending-cancel-invoice.index', compact('invoices'));
    }

    /**
     * Show the form for editing pending cancel invoice.
     */
    public function editPendingCancel(Request $request, Invoice $invoice)
    {
        // Ensure invoice is pending cancellation
        if ($invoice->status !== 'pending_cancellation') {
            abort(404);
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'action' => 'required|in:approve,reject',
                'notes' => 'nullable|string|max:1000',
            ]);

            try {
                DB::beginTransaction();

                if ($request->action === 'approve') {
                    $invoice->update([
                        'status' => 'cancelled',
                        'notes' => $request->notes,
                    ]);
                } else {
                    // Reject cancellation - restore to previous status
                    $previousStatus = $invoice->balance_amount <= 0 ? 'paid' : 'unpaid';
                    $invoice->update([
                        'status' => $previousStatus,
                        'notes' => $request->notes,
                    ]);
                }

                DB::commit();

                $message = $request->action === 'approve'
                    ? __('billing.cancellation_approved')
                    : __('billing.cancellation_rejected');

                return redirect()->route('billing.pending-cancel-invoice.index')
                    ->with('success', $message);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Pending cancel invoice update failed: ' . $e->getMessage());

                return back()->withInput()
                    ->with('error', __('billing.pending_cancel_update_failed'));
            }
        }

        return view('billing.pending-cancel-invoice.edit', compact('invoice'));
    }

    /**
     * Remove the specified pending cancel invoice.
     */
    public function deletePendingCancel(Invoice $invoice)
    {
        // Ensure invoice is pending cancellation
        if ($invoice->status !== 'pending_cancellation') {
            return response()->json([
                'success' => false,
                'message' => __('billing.invalid_operation')
            ], 422);
        }

        try {
            $invoice->delete();

            return response()->json([
                'success' => true,
                'message' => __('billing.pending_cancel_invoice_deleted_successfully')
            ]);
        } catch (\Exception $e) {
            Log::error('Pending cancel invoice deletion failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('billing.pending_cancel_invoice_deletion_failed')
            ], 500);
        }
    }
}
