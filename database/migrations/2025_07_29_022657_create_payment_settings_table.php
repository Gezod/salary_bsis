<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('staff_payroll_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->integer('monthly_salary')->default(0); // Gaji bulanan staff
            $table->integer('monthly_fine')->default(0); // Denda bulanan
            $table->text('fine_notes')->nullable(); // Catatan denda
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->unique('employee_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('staff_payroll_settings');
    }
};
