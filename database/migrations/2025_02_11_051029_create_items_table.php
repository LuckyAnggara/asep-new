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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->foreignId('item_category_id')->constrained()->onDelete('cascade');
            $table->string('barcode')->unique()->nullable();
            $table->string('name');
            $table->string('unit'); // e.g., pcs, kg, liters
            $table->string('image')->nullable();
            $table->json('variations')->nullable(); // Example: sizes, colors
            $table->decimal('cogs', 10, 2)->default(0); // Cost of Goods Sold
            $table->decimal('price', 10, 2)->default(0); // Selling Price
            $table->integer('min_stock')->default(1);
            $table->decimal('weight', 8, 2)->nullable(); // In kg or grams
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
