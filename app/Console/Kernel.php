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
        \App\Console\Commands\MobapassOutput::class,
        \App\Console\Commands\PointBooking::class,
        \App\Console\Commands\UpdateMembers::class,// STS 2021/08/16 Task 45
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // モバパス連携（予約・取消・アプリ番号-購入・アプリ番号-譲渡）(5分毎) GL->mbps
        $schedule->command('MobapassOutput')->everyFiveMinutes();
        // モバパス連携（入場）(5分毎) GL<-mbps
        $schedule->command('MobapassInput')->everyFiveMinutes();
        $schedule->command('SeatingHK')->everyMinute();
        $schedule->command('PointBooking')->dailyAt('05:00');

        // STS 2021/08/16 Task 45: Choose the specific time to updateMember information - START
        $schedule->command('updateMembers 2 1')->daily();           // Everyday at midnight
        // $schedule->command('updateMembers 2 1')-dailyAt('02:00');   // Everyday at 02:00
        // $schedule->command('updateMembers 2 1')->hourly();          // Every hour
        // $schedule->command('updateMembers 2 1')->everyTenMinutes(); // Every ten minutes
        // $schedule->command('updateMembers 2 1')->everyMinute(); // Every minute
        // STS 2021/08/16 Task 45 end


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
