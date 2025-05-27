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
        Schema::create('association_rules', function (Blueprint $table) {
            $table->id();
            $table->json('antecedent_product_ids'); // Array of product IDs (e.g., [1, 5])
            $table->json('antecedent_names');       // Array of product names (e.g., ["Susu", "Roti"])
            $table->json('consequent_product_ids'); // Array of product IDs (e.g., [10])
            $table->json('consequent_names');       // Array of product names (e.g., ["Telur"])
            $table->decimal('support', 5, 4);
            $table->decimal('confidence', 5, 4);
            $table->decimal('lift', 5, 4);
            $table->timestamps();

            // Opsional: Menambah indeks untuk pencarian cepat berdasarkan ID produk
            // Ini akan membutuhkan ekstensi JSON di database Anda atau strategi query yang berbeda

        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('association_rules');
    }
};