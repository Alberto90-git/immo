<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\CheckSubscriptionExpiration;
use App\Console\Commands\ProcessAutomationRules;
use App\Services\WhatsAppService;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        CheckSubscriptionExpiration::class,
        ProcessAutomationRules::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('subscription:check-expiration')->dailyAt('08:00');

        // Automation : exécuté toutes les heures pour respecter l'heure configurée par règle
        $schedule->command('automation:process')->everyMinute()->withoutOverlapping();

        $schedule->call(function () {
            (new WhatsAppService())->nettoyerFichiersTemp();
        })->daily()->name('whatsapp-temp-cleanup')->withoutOverlapping();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
