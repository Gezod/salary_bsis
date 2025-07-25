<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('leaves', function (Blueprint $table) {
            // Add approval related fields if they don't exist
            if (!Schema::hasColumn('leaves', 'approval_notes')) {
                $table->text('approval_notes')->nullable()->after('status');
            }
            if (!Schema::hasColumn('leaves', 'approved_at')) {
                $table->timestamp('approved_at')->nullable()->after('approval_notes');
            }
            if (!Schema::hasColumn('leaves', 'approved_by')) {
                $table->string('approved_by')->nullable()->after('approved_at');
            }
        });
    }

    public function down()
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn(['approval_notes', 'approved_at', 'approved_by']);
        });
    }
};
