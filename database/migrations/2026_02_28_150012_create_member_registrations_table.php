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
        Schema::create('member_registrations', function (Blueprint $table) {
            $table->id();
            // Relasi ke anggota yang mendaftarkan (Referral)
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');

            // Form Data
            $table->string('name');
            $table->string('nik')->unique();
            $table->string('department');
            $table->string('birth_place');
            $table->date('birth_date');
            $table->text('address');
            $table->enum('gender', ['male', 'female']);
            $table->string('religion');
            $table->string('education');
            $table->string('phone');

            // Status Flow
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('member_registrations');
    }
};
