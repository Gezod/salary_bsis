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
        Schema::create('presensis', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->date('tanggal_presensi');
            $table->time('jam_in');
            $table->time('jam_awal_isti');
            $table->time('jam_akhir_isti');
            $table->time('jam_pulang');
            $table->integer('jam_denda')->nullable();
            $table->integer('jam_lembur')->nullable();
            $table->timestamps();

            // unique per karyawan per hari
            $table->unique(['username', 'tanggal_presensi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensis');
    }
};
