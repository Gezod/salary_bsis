<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('staff_payroll_settings', function (Blueprint $table) {
            // Hapus kolom denda lama
            $table->dropColumn(['monthly_fine', 'fine_notes']);

            // Tambah kolom uang makan harian
            $table->integer('daily_meal_allowance')->default(0)->after('monthly_salary');
            $table->text('meal_allowance_notes')->nullable()->after('daily_meal_allowance');
        });
    }

    public function down()
    {
        Schema::table('staff_payroll_settings', function (Blueprint $table) {
            // Kembalikan kolom denda
            $table->integer('monthly_fine')->default(0)->after('monthly_salary');
            $table->text('fine_notes')->nullable()->after('monthly_fine');

            // Hapus kolom uang makan
            $table->dropColumn(['daily_meal_allowance', 'meal_allowance_notes']);
        });
    }
};
