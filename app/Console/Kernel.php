<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Artisan commands for the application.
     */
    protected $commands = [
        \App\Console\Commands\DropMigrationsTable::class,
        \App\Console\Commands\DiscardMigrationsTablespace::class,
        \App\Console\Commands\StorageResetLink::class,
        \App\Console\Commands\StorageCleanPublic::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
