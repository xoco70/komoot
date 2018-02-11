<?php

namespace App\Console;

use App\Jobs\SendMailDigest;
use App\MailDigest;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Cache;

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
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // We put in cache the hourly time of mail digest.
        // This will allow us to send async mails
        Cache::put('schedule_timestamp', Carbon::now(), 60);

        $digestsByUser = MailDigest::build();
        foreach ($digestsByUser as $email => $digest) {
            $schedule
                ->job(new SendMailDigest($email, $digest))
                ->hourlyAt(0);
        }
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
