<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the payments.
     */
    public function index()
    {
        $payments = Payment::with(['order.user'])
            ->latest()
            ->paginate(10);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Update payment status
     */
    public function updateStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:paid,failed,refunded'
        ]);

        try {
            // Update payment status
            $payment->status = $request->status;

            if ($request->status === 'paid') {
                $payment->paid_at = now();
                $payment->order->updateStatus('processing');
            } elseif ($request->status === 'failed') {
                $payment->order->updateStatus('cancelled');
            } elseif ($request->status === 'refunded') {
                $payment->order->updateStatus('cancelled');
            }

            $payment->save();

            return response()->json([
                'success' => true,
                'message' => 'Payment status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update payment status'
            ], 500);
        }
    }

    /**
     * Check for expired payments and cancel them
     */
    public function checkExpiredPayments()
    {
        $expiredPayments = Payment::where('status', 'pending')
            ->where('created_at', '<=', Carbon::now()->subHours(24))
            ->get();

        foreach ($expiredPayments as $payment) {
            $payment->status = 'failed';
            $payment->save();

            $payment->order->updateStatus('cancelled');
        }

        return response()->json([
            'success' => true,
            'message' => 'Expired payments checked and updated'
        ]);
    }

    /**
     * Delete failed payment and its associated order
     */
    public function deleteFailedPayment(Payment $payment)
    {
        try {
            // Check if payment is failed
            if ($payment->status !== 'failed') {
                return redirect()
                    ->back()
                    ->with('error', 'Hanya pembayaran dengan status gagal yang dapat dihapus.');
            }

            DB::beginTransaction();

            // Get the order before deleting payment
            $order = $payment->order;

            // Delete payment
            $payment->delete();

            // Delete order and its details
            if ($order) {
                // Delete order details first
                $order->details()->delete();
                // Then delete the order
                $order->delete();
            }

            DB::commit();

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Pembayaran dan pesanan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus pembayaran: ' . $e->getMessage());
        }
    }
}