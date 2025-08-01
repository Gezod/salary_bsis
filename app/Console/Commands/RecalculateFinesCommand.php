<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Illuminate\Support\Facades\Log;

class RecalculateFinesCommand extends Command
{
    protected $signature = 'fines:recalculate';
    protected $description = 'Recalculate all attendance fines';

    public function handle()
    {
        $attendances = Attendance::with('employee')->get();
        $bar = $this->output->createProgressBar(count($attendances));

        $bar->start();

        foreach ($attendances as $attendance) {
            try {
                if ($attendance->employee) {
                    $attendance->calculateFines();
                }
            } catch (\Exception $e) {
                Log::error('Failed to recalculate fines for attendance', [
                    'id' => $attendance->id,
                    'error' => $e->getMessage()
                ]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->info("\nFines recalculation completed!");

        return 0;
    }
}
