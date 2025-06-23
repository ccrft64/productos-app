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
            // Since the 'valor' column was already renamed to 'price'
            // by a previous migration, we now modify the 'price' column.
            // Adjust precision if needed, but it should already be 10,2 from the rename migration.
            // This migration might even be redundant if the rename migration already set it correctly.
            // However, to make this migration runnable and safe, we'll target 'price'.
            $table->decimal('price', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Revert the 'price' column to its original smaller size if you roll back.
            // Assuming your original 'valor' was decimal(8, 2)
            $table->decimal('price', 8, 2)->change();
        });
    }
};