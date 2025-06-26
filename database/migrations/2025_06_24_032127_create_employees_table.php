<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $t) {
            $t->id();
            $t->unsignedInteger('pin')->unique();       // PIN mesin
            $t->unsignedInteger('nip')->nullable();
            $t->string('nama');
            $t->string('jabatan')->nullable();
            $t->string('departemen')->nullable();
            $t->string('kantor')->nullable();
            $t->enum('role', ['karyawan', 'pengurus'])->default('karyawan');
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
