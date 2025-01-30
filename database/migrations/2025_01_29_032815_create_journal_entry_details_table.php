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
        Schema::create('journal_entry_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('journal_entry_id')->constrained()->onDelete('cascade'); // Relasi ke journal_entries
            $table->foreignId('chart_of_accounts_id')->constrained()->onDelete('cascade'); // Relasi ke akun (nanti buat tabel accounts)
            $table->decimal('debit', 15, 2)->default(0); // Jumlah debit
            $table->decimal('credit', 15, 2)->default(0); // Jumlah kredit
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('journal_entry_details');
    }
};
