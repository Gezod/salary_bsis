<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $t) {
            $t->id();
            $t->foreignId('employee_id')->constrained()->onDelete('cascade');

            $t->date('tanggal');

            // scan waktu
            $t->dateTime('scan1')->nullable();
            $t->dateTime('scan2')->nullable();
            $t->dateTime('scan3')->nullable();
            $t->dateTime('scan4')->nullable();
            $t->dateTime('scan5')->nullable();

            // hitungan menit
            $t->integer('late_minutes')->default(0);
            $t->integer('early_leave_minutes')->default(0);
            $t->integer('overtime_minutes')->default(0);
            $t->integer('excess_break_minutes')->default(0);
            $t->boolean('invalid_break')->default(false);

            // denda (rupiah)
            $t->integer('late_fine')->default(0);
            $t->integer('break_fine')->default(0);
            $t->integer('absence_fine')->default(0);
            $t->integer('total_fine')->default(0);

            $t->timestamps();
            $t->unique(['employee_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
