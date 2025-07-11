<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->integer('month'); // 1-12
            $table->integer('year');
            $table->integer('working_days')->default(0);
            $table->integer('present_days')->default(0);
            $table->integer('basic_salary')->default(0);
            $table->integer('overtime_pay')->default(0);
            $table->integer('total_fines')->default(0);
            $table->integer('gross_salary')->default(0);
            $table->integer('net_salary')->default(0);
            $table->enum('payment_method', ['cash', 'transfer'])->nullable();
            $table->date('payment_date')->nullable();
            $table->string('payment_proof')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->unique(['employee_id', 'month', 'year']);
            $table->index(['month', 'year']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payrolls');
    }
};
