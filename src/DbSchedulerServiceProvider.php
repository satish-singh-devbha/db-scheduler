<?php

namespace Satishsinghdevbha\DbScheduler;

use Illuminate\Support\ServiceProvider;
use Satishsinghdevbha\DbScheduler\Schedule\Schedule;
use Illuminate\Console\Scheduling\Schedule as BaseSchedule;

class DbSchedulerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {

        $this->mergeConfigFrom(
            __DIR__ . '/../config/config.php', // Default package config
            'db-scheduler'
        );

        $this->publishes([
            __DIR__.'/../config/config.php' => config_path('db-scheduler.php')
        ], 'db-scheduler');

        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'dbscheduler');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');


        $this->app->extend(BaseSchedule::class, function ()  {
            return (new Schedule());
        });
    }
}
