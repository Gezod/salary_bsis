<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->date('tanggal_mulai_kontrak_awal')->nullable()->after('tanggal_end_kontrak');
        });
    }

    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn('tanggal_mulai_kontrak_awal');
        });
    }
};
