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
        Schema::table('attendances', function (Blueprint $table) {
            // Check if columns don't exist before adding them
            if (!Schema::hasColumn('attendances', 'is_half_day')) {
                $table->boolean('is_half_day')->default(false)->after('invalid_break');
            }

            if (!Schema::hasColumn('attendances', 'half_day_type')) {
                $table->enum('half_day_type', ['shift_1', 'shift_2'])->nullable()->after('is_half_day');
            }

            if (!Schema::hasColumn('attendances', 'half_day_notes')) {
                $table->text('half_day_notes')->nullable()->after('half_day_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            if (Schema::hasColumn('attendances', 'half_day_notes')) {
                $table->dropColumn('half_day_notes');
            }

            if (Schema::hasColumn('attendances', 'half_day_type')) {
                $table->dropColumn('half_day_type');
            }

            if (Schema::hasColumn('attendances', 'is_half_day')) {
                $table->dropColumn('is_half_day');
            }
        });
    }
};
