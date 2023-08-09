<?php

namespace App\Console;

use App\Models\Export;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            $exports = Export::query()->whereNotIn('status', [0, 2])->get();

            foreach ($exports as $export) {
                if ($export->create_at->diff(Carbon::now())->format('%a') > 0) {
                    $export->status = 0;
                    $export->update();
                }
            }
        })->everyThirtyMinutes();
    }


    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
