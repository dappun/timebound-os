<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Import::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('timebound:report daily_ot')
            ->timezone('Asia/Manila')
            ->dailyAt('01:00');

        $schedule->command('timebound:report weekly_summary')
            ->timezone('Asia/Manila')
            ->weekly('11:30')
            ->sundays()->at('08:00');

        $schedule->command('timebound:watch to_close')
            ->timezone('Asia/Manila')
            ->twiceDaily(3, 23);

        $schedule->command('timebound:watch to_warn')
            ->timezone('Asia/Manila')
            ->hourly();
    }
}
