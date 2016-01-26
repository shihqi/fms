<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
//use Illuminate\Foundation\Bus\DispatchesJobs as DispatchedJob;

class Kernel extends ConsoleKernel
{
    //use DispatchedJob;
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
        \App\Console\Commands\ParseFeed::class,
        \App\Console\Commands\ParseFile::class,
        \App\Console\Commands\CheckFeed::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //$schedule->command('feed:parse 2')
                //->dailyAt('01:00')
                //->everyFiveMinutes()
                //->sendOutputTo(storage_path().'/logs/console_log.txt')->emailOutputTo('vincent.lin@iprospect.com');
        //$schedule->dispatch(new SendReminderEmail())->everyFiveMinutes();
        $schedule->command('feed:check')
                ->dailyAt('01:00');
        //$schedule->command('parse:file')
                //->everyTemMinutes();
    }
}
