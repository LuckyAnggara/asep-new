<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // Tabel untuk kategori utama (Asset, Liabilities, dll)
        Schema::create('account_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name'); // Nama kategori utama
            $table->string('description')->nullable(); // Deskripsi tambahan
            $table->string(
                'normal'
            );
            $table->timestamps();
        });

        // Tabel untuk sub kategori (Bank, Accounts Receivable, dll)
        Schema::create('account_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('account_categories')->onDelete('cascade');
            $table->string('code');
            $table->string('name'); // Nama sub kategori
            $table->string('description')->nullable();
            $table->timestamps();
        });

        // Tabel untuk chart of accounts (Bank BNI, Bank Mandiri, dll)
        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_category_id')->constrained('account_sub_categories')->onDelete('cascade');
            $table->string('code');
            $table->string('name'); // Nama akun spesifik
            $table->string('account_number')->unique(); // Nomor akun (opsional)
            $table->enum('cashflow_type', ['operating', 'investing', 'financing'])->nullable();
            $table->string('description')->nullable(); // Deskripsi tambahan
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chart_of_accounts');
        Schema::dropIfExists('account_sub_categories');
        Schema::dropIfExists('account_categories');
    }
};
