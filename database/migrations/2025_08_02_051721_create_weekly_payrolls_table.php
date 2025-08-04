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
        Schema::create('weekly_payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('working_days');
            $table->integer('present_days');
            $table->bigInteger('basic_salary')->default(0);
            $table->bigInteger('overtime_pay')->default(0);
            $table->bigInteger('meal_allowance')->default(0);
            $table->bigInteger('total_fines')->default(0);
            $table->bigInteger('bpjs_deduction')->default(0);
            $table->bigInteger('gross_salary')->default(0);
            $table->bigInteger('net_salary')->default(0);
            $table->string('payment_method')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_proof')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_payrolls');
    }
};
