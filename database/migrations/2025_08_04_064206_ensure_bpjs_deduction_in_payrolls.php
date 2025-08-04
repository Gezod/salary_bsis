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
        // Check if bpjs_deduction column exists, if not add it
        if (!Schema::hasColumn('payrolls', 'bpjs_deduction')) {
            Schema::table('payrolls', function (Blueprint $table) {
                $table->bigInteger('bpjs_deduction')->default(0)->after('total_fines');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('payrolls', 'bpjs_deduction')) {
            Schema::table('payrolls', function (Blueprint $table) {
                $table->dropColumn('bpjs_deduction');
            });
        }
    }
};
