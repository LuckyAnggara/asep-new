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
        Schema::create('members', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('member_id')->unique(); // Unique member identifier
            $table->string('name'); // Member name
            $table->string('email')->unique(); // Email address
            $table->string('phone')->nullable(); // Phone number
            $table->string('address')->nullable(); // Home address
            $table->date('date_of_birth')->nullable(); // Date of birth
            $table->enum('gender', ['male', 'female', 'other'])->nullable(); // Gender
            $table->string('job_title')->nullable(); // Job title of the member
            $table->string('department')->nullable(); // Department or unit
            $table->date('join_date'); // Date of joining the union
            $table->boolean('active')->default(true); // Membership status
            $table->string('photo')->nullable(); // Profile photo (path)
            $table->text('notes')->nullable(); // Additional notes

            $table->timestamps(); // Created at & Updated at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
