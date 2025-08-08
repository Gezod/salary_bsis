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
        Schema::table('weekly_payrolls', function (Blueprint $table) {
            $table->integer('bpjs_allowance')->default(0)->after('bpjs_deduction');
            $table->integer('bpjs_cash_payment')->default(0)->after('bpjs_allowance');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('weekly_payrolls', function (Blueprint $table) {
            $table->dropColumn(['bpjs_allowance', 'bpjs_cash_payment']);
        });
    }
};
