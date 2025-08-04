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
        Schema::create('bpjs_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->string('bpjs_number', 50);
            $table->integer('bpjs_monthly_amount')->default(0);
            $table->integer('bpjs_weekly_amount')->default(0);
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            // Ensure one BPJS setting per employee
            $table->unique('employee_id');

            // Add indexes for better performance
            $table->index(['employee_id', 'is_active']);
            $table->index('bpjs_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bpjs_settings');
    }
};
