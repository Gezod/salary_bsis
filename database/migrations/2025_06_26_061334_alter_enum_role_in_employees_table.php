<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Ubah enum menjadi termasuk 'staff' dan 'manager'
            $table->enum('role', ['karyawan', 'pengurus', 'staff', 'manager'])->default('karyawan')->change();
        });
    }

    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Kembalikan enum seperti semula
            $table->enum('role', ['karyawan', 'pengurus'])->default('karyawan')->change();
        });
    }
};
