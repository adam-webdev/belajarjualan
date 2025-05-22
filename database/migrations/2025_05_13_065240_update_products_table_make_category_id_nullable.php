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
        Schema::table('products', function (Blueprint $table) {
            // Make category_id nullable and change the foreign key constraint
            $table->foreignId('category_id')->nullable()->change();

            // Drop the existing foreign key constraint
            $table->dropForeign(['category_id']);

            // Add the new foreign key constraint with onDelete set to set null
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop the foreign key constraint
            $table->dropForeign(['category_id']);

            // Make category_id required again
            $table->foreignId('category_id')->nullable(false)->change();

            // Re-add the foreign key constraint with onDelete cascade
            $table->foreign('category_id')
                  ->references('id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }
};
