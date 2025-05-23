<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard with statistics and recent data
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get total counts
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total');

        // Get order status counts
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();

        // Get payment status counts
        $paidOrders = Order::whereHas('payment', function($q) {
            $q->where('status', 'paid');
        })->count();

        $pendingPayments = Order::whereHas('payment', function($q) {
            $q->where('status', 'pending');
        })->count();

        // Get recent orders with relationships
        $recentOrders = Order::with(['user', 'payment'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'pendingOrders',
            'processingOrders',
            'deliveredOrders',
            'paidOrders',
            'pendingPayments',
            'recentOrders'
        ));
    }
}