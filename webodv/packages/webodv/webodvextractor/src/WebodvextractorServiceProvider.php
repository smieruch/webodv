<?php

namespace Webodv\Webodvextractor;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\webodvLibs\clean_downloads;

class WebodvextractorServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('Webodv\Webodvextractor\WebodvextractorController');

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadRoutesFrom(__DIR__.'/routes/routes.php');

        //schedule
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->call(function () {
                $clean_downloads = new clean_downloads();
                $clean_downloads->clean();
            })->hourly(); //everyMinute(); //hourly();
        });

        
        //publish views
        $this->publishes([
            __DIR__.'/views' => resource_path('views/webodv/webodvextractor'),
        ]);

        //publish migrations
        $this->publishes([
            __DIR__.'/migrations' => base_path('database/migrations'),
        ]);

        //publish models
        $this->publishes([
            __DIR__.'/models' => app_path(),
        ]);

        
        /* //publish email templates */
        /* $this->publishes([ */
        /*     __DIR__.'/emails' => resource_path('/views/webodv/webodvextractor/emails'), */
        /* ]); */

        //publish mail
        $this->publishes([
            __DIR__.'/mail' => app_path('Mail'),
        ]);

        //publish notifications
        $this->publishes([
            __DIR__.'/emails/download_finished.blade.php' => resource_path('views/vendor/notifications/email.blade.php'),
        ]);

        //override email notification
        $this->publishes([
            __DIR__.'/notifications' => app_path('Notifications'),
        ]);

        //publish js
        $this->publishes([
            __DIR__.'/js' => public_path('js/webodv'),
        ]);

        //publish css
        $this->publishes([
            __DIR__.'/css' => public_path('css/webodv'),
        ]);

        //publish webodvLibs
        $this->publishes([
            __DIR__.'/webodvLibs' => app_path('webodvLibs'),
        ]);

        //publish traits
        $this->publishes([
            __DIR__.'/webodvTraits' => app_path('webodvTraits'),
        ]);

        //publish public
        $this->publishes([
            __DIR__.'/public' => public_path(),
        ]);

        

    }
}
