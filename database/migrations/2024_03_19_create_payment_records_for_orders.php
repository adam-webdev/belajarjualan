<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
use App\Models\Payment;

return new class extends Migration
{
    public function up()
    {
        // Get all orders without payment records
        $orders = Order::whereDoesntHave('payment')->get();

        foreach ($orders as $order) {
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'pending',
                'amount' => $order->total,
                'status' => 'pending'
            ]);
        }
    }

    public function down()
    {
        // No need to do anything in down() as we're just fixing data
    }
};