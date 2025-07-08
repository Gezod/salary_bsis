<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('overtime_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('staff_rate')->default(10000);
            $table->integer('karyawan_rate')->default(15000);
            $table->integer('minimum_minutes')->default(30);
            $table->timestamps();
        });

        // Insert default settings
        DB::table('overtime_settings')->insert([
            'staff_rate' => 10000,
            'karyawan_rate' => 15000,
            'minimum_minutes' => 30,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('overtime_settings');
    }
};
