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
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->boolean(
                'is_opening_balance'
            )->default(false);
            $table->date('date'); // Tanggal transaksi
            $table->string('reference')->nullable(); // Tanggal transaksi
            $table->string('description'); // Deskripsi transaksi
            $table->string('attachment')->nullable(); // Profile photo (path)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
