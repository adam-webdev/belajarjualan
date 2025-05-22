<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Make user_id nullable for guest carts
            $table->foreignId('user_id')->nullable()->change();

            // Add total columns used in cart controller
            $table->decimal('subtotal', 12, 2)->default(0)->after('user_id');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('subtotal');
            $table->decimal('total', 12, 2)->default(0)->after('discount_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn(['subtotal', 'discount_amount', 'total']);
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};