<?php

namespace App\Console;

use App\Models\AuthRequest;
use App\Chat;
use App\Models\Message;
use App\Services\Auth\AuthService;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        ###> Deletion of expired auth requests ###
        $schedule->call(function () {
            AuthRequest::where('updated_at', '<', Carbon::now()->subDays(AuthService::AUTH_REQUEST_LIFETIME))->delete();
        })->everySixHours();
        ###< Deletion of expired auth requests ###

        ###> Deletion of expired refresh tokens ###
        $schedule->call(function () {
            Session::where('expires_in', '<', Carbon::now()->subDays(AuthService::REFRESH_TOKEN_LIFETIME))->delete();
        })->daily();
        ###< Deletion of expired refresh tokens ###
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
