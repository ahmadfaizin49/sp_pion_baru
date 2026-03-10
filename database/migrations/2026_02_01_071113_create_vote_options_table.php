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
        Schema::create('vote_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // kandidat
            $table->string('label'); // nama kandidat
            $table->timestamps();

            // Penting: Satu user cuma bisa milih 1x di satu voting yang sama
            $table->unique(['vote_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vote_options');
    }
};
