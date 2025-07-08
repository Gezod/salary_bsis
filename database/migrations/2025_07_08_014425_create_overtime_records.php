<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('overtime_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('overtime_minutes')->default(0);
            $table->integer('overtime_pay')->default(0);
            $table->datetime('scan4')->nullable();
            $table->datetime('expected_out')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            $table->timestamps();

            $table->unique(['employee_id', 'tanggal']);
            $table->index(['tanggal', 'employee_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('overtime_records');
    }
};
