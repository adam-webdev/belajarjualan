<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display order history with filters
     */
    public function index(Request $request)
    {
        $query = Order::where('user_id', Auth::id())
            ->with(['payment', 'details.productCombination.product']);

        // Apply status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Apply date filter
        if ($request->filled('date')) {
            $date = Carbon::now();
            switch ($request->date) {
                case '30days':
                    $date = $date->subDays(30);
                    break;
                case '3months':
                    $date = $date->subMonths(3);
                    break;
                case '6months':
                    $date = $date->subMonths(6);
                    break;
                case '1year':
                    $date = $date->subYear();
                    break;
            }
            $query->where('created_at', '>=', $date);
        }

        // Apply search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('details.productCombination.product', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('shop.orders', compact('orders'));
    }

    /**
     * Display order details
     */
    public function show($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', Auth::id())
            ->with(['details.productCombination.product', 'address', 'payment'])
            ->firstOrFail();

        return view('shop.order-detail', compact('order'));
    }

    /**
     * Upload payment proof for an order
     */
    public function uploadPaymentProof(Request $request, Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Validate request
        $validated = $request->validate([
            'payment_proof' => 'required|image|max:2048',
        ]);

        // Get existing payment record
        $payment = Payment::where('order_id', $order->id)->first();

        if ($payment) {
            // Delete old payment proof if exists
            if ($payment->payment_proof) {
                $oldImagePath = storage_path('app/public/' . $payment->payment_proof);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Store the new uploaded image
            $imagePath = $request->file('payment_proof')->store('payment_proofs', 'public');

            // Update payment record
            $payment->payment_proof = $imagePath;
            $payment->status = 'pending'; // Set to pending for admin approval
            $payment->save();

            return back()->with('success', 'Payment proof updated successfully.');
        }

        return back()->with('error', 'Payment record not found.');
    }

    /**
     * Cancel an order (if it's still in pending status)
     */
    public function cancel(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if order can be cancelled
        if (!in_array($order->status, ['pending', 'processing'])) {
            return back()->with('error', 'This order cannot be cancelled.');
        }

        // Cancel the order
        $order->status = 'cancelled';
        $order->save();

        // Return stock to inventory
        foreach ($order->details as $detail) {
            $combination = $detail->productCombination;
            $combination->stock += $detail->quantity;
            $combination->save();
        }

        return back()->with('success', 'Order cancelled successfully.');
    }

    /**
     * Confirm order delivery
     */
    public function confirmDelivery(Order $order)
    {
        // Check if order belongs to user
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Check if order is in shipped status
        if ($order->status !== 'shipped') {
            return back()->with('error', 'Only shipped orders can be marked as delivered.');
        }

        // Update order status
        $order->status = 'delivered';
        $order->save();

        return back()->with('success', 'Order marked as delivered successfully.');
    }
}