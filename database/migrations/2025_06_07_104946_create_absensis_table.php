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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->string('type')->nullable();
            $table->text('notes')->nullable();
            $table->string('bukti')->nullable();
            $table->time('waktu_masuk')->nullable();
            $table->time('waktu_pulang')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'tanggal']); // agar tidak bisa absen dua kali
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
