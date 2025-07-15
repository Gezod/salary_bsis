<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('tanggal_start_kontrak')->nullable()->after('kantor');
            $table->date('tanggal_end_kontrak')->nullable()->after('tanggal_start_kontrak');
        });
    }


    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['tanggal_start_kontrak', 'tanggal_end_kontrak']);
        });
    }
};
