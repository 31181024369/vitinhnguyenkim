<?php

namespace App\Console;

use Carbon\Carbon;
use App\Models\Coupon;
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
        // Commands\ExpireCoupons::class,
        // Commands\CategoryProductCron::class,
        // Commands\StatisticsCommand::class,
        Commands\FlashSalseCommand::class,
        Commands\PromotionCommand::class,
        Commands\AdminLogCommand ::class,
        Commands\delStatisticsCommand ::class,
        Commands\delAdminLogCommand ::class,
        'App\Console\Commands\StatisticsCommand',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->job(new \App\Jobs\GetProductListEveryDay)->everyMinute();
        // $schedule->command('coupons:expire')->everyMinute();
        $schedule->command('statistics:expire')->saturdays()->at('10:00');
        $schedule->command('flashsale:expire')->daily();
        $schedule->command('promotion:expire')->daily();
        $schedule->command('adminlog:expire')->fridays()->at('10:00');
        $schedule->command('delStatistics:expire')->tuesdays()->at('10:00');
        // $schedule->command('delStatistics:expire')->dailyAt('09:00')->when(function () {
        //     return Carbon::now()->endOfMonth()->isToday();
        // });
        //$schedule->command('delStatistics:expire')->thursdays()->at('13:00');
        $schedule->command('delAdminLog:expire')->wednesdays()->at('8:55');
        $schedule->command('delExcel:expire')->monthly();
        $schedule->command('database:expire')->monthly();
        // $schedule->command('demo:cron')->everyMinute();
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
