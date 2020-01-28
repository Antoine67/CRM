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
        Commands\RefreshAllCustomers::class,
        Commands\RefreshListCustomers::class,
        Commands\RefreshGraphGrafana::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Refresh grafana graphs
        $schedule->command('refresh:grafana')//>call('App\Http\Controllers\GrafanaController@refreshAllGraph')
	    ->everyMinute()
	    ->appendOutputTo(storage_path().'/logs/grafana.log');

        //Refresh ALL customers data 
        $schedule->command('refresh:customersAll')//call('App\Http\Controllers\SharepointController@refreshAllCustomersFromSharepoint')
	    ->dailyAt('01:00')
	    ->appendOutputTo(storage_path().'/logs/customersAll.log');

        //Refresh customers list
        $schedule->command('refresh:customersList')//->call('App\Http\Controllers\SharepointController@refreshCustomersFromSharepoint')
	    ->twiceDaily(1, 13)
	    ->appendOutputTo(storage_path().'/logs/customersList.log');
        
        
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
