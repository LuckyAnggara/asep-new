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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slogan')->nullable();
            $table->string('logo')->nullable(); // Simpan path logo
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('language')->nullable();
            $table->string('registration_number')->nullable(); // NPWP atau nomor registrasi
            $table->string('currency')->default('IDR'); // Mata uang default
            $table->integer('decimal')->default(2); // Mata uang default
            $table->string('timezone')->default('Asia/Jakarta'); // Zona waktu
            $table->string('theme')->default('light'); // Tema aplikasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
