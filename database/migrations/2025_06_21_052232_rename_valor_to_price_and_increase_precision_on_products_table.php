<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Needed for DB::statement if using spatial types etc., but good practice

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // First, change the column type to accommodate larger values.
            // Using 10 digits total, with 2 after the decimal point (e.g., up to 99,999,999.99)
            // You can adjust '10' and '2' based on your maximum expected price.
            $table->decimal('valor', 10, 2)->change();

            // Then, rename the column from 'valor' to 'price'.
            $table->renameColumn('valor', 'price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Reverse the rename first
            $table->renameColumn('price', 'valor');

            // Then, revert the column type if necessary (match your original definition)
            // Assuming your original was decimal(8, 2)
            $table->decimal('valor', 8, 2)->change();
        });
    }
};