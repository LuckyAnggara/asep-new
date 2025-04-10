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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            // $table->foreignId('warehouse_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['in', 'out']); // Stok masuk atau keluar
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->timestamp('transaction_date')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
