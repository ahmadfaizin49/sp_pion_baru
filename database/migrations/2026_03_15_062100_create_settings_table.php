<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();      // Kunci unik, contoh: 'iuran_bulanan'
            $table->string('label');              // Label untuk ditampilkan di form admin
            $table->text('value')->nullable();    // Nilai yang bisa diubah admin
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
