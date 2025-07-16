<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // This migration will update the penalties config to include half-day penalties
        // The actual config update will be handled in the AttendanceService
    }

    public function down(): void
    {
        // Nothing to rollback for config changes
    }
};
