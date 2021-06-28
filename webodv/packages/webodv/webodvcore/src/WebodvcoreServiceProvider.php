<?php

namespace Webodv\Webodvcore;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use App\webodvLibs\wsODV_manager;
use App\webodvLibs\webodv_monitor;
use Illuminate\Support\Facades\Schema;


class WebodvcoreServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('Webodv\Webodvcore\WebodvcoreController');

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
                $cleaner = new wsODV_manager();
                $cleaner->clean("all");
            })->everyMinute();
        });

        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            $schedule->call(function () {
                $monitor = new webodv_monitor();
                $monitor->monitor_process();
            })->everyMinute();
        });
        


        
        //for older DBs see https://laravel.com/docs/6.x/migrations#creating-indexes
        Schema::defaultStringLength(191);


        //publish views
        $this->publishes([
            __DIR__.'/views' => resource_path('views/webodv/webodvcore'),
        ]);

        //publish controller
        /* $this->publishes([ */
        /*     __DIR__.'/controller' => app_path('Http/Controllers'), */
        /* ]); */

        //publish auth
        $this->publishes([
            __DIR__.'/auth' => resource_path('views/auth'),
        ]);
        //publish errors
        $this->publishes([
            __DIR__.'/views/errors' => resource_path('views/errors'),
        ]);
        //publish auth_passwords
        $this->publishes([
            __DIR__.'/passwords' => resource_path('views/auth/passwords'),
        ]);
        
        //publish mails
        $this->publishes([
            __DIR__.'/mails' => app_path('Mail'),
        ]);
        
        //publish config
        $this->publishes([
            __DIR__.'/config' => base_path('config'),
        ]);

        //publish webpack
        $this->publishes([
            __DIR__.'/webpack' => base_path(),
        ]);
        
        //publish maxmind
        //https://github.com/stevebauman/location
        $this->publishes([
            __DIR__.'/maxmind' => base_path('database/maxmind'),
        ]);

        //publish layouts
        //overwrite app.blade.php
        /* $this->publishes([ */
        /*     __DIR__.'/layouts' => resource_path('views/layouts'), */
        /* ]); */

        //publish middleware
        $this->publishes([
            __DIR__.'/middleware' => app_path('Http/Middleware'),
        ]);
        //register middleware
        $router = $this->app['router'];
        $router->middlewareGroup('header',['\App\Http\Middleware\secondHeaderTitle::class']);

        //publish middleware
        $this->publishes([
            __DIR__.'/providers' => app_path('Providers'),
        ]);
        

        //css
        $this->publishes([
            __DIR__.'/css' => public_path('css/webodv'),
        ]);
        //js
        $this->publishes([
            __DIR__.'/js' => public_path('js/webodv'),
        ]);
        //fonts
        $this->publishes([
            __DIR__.'/assets/fonts' => public_path('css/fonts'),
        ]);
        //impressum, privacy
        $this->publishes([
            __DIR__.'/assets' => public_path(),
        ]);
        /* $this->publishes([ */
        /*     __DIR__.'/assets/impressum.html' => public_path('impressum.html'), */
        /* ]); */
        /* $this->publishes([ */
        /*     __DIR__.'/assets/privacy.html' => public_path('privacy.html'), */
        /* ]); */
        /* $this->publishes([ */
        /*     __DIR__.'/assets/countries.txt' => public_path('countries.txt'), */
        /* ]); */
        /* $this->publishes([ */
        /*     __DIR__.'/assets/countries_codes.txt' => public_path('countries_codes.txt'), */
        /* ]); */
        /* $this->publishes([ */
        /*     __DIR__.'/assets/favicon.ico' => public_path('favicon.ico'), */
        /* ]); */
        //images
        $this->publishes([
            __DIR__.'/images' => public_path('images'),
        ]);

        //documentation
        $this->publishes([
            __DIR__.'/documentation/odv-online-howto.pdf' => public_path('documentation/webodv-data-explorer-howto.pdf'),
        ]);
        $this->publishes([
            __DIR__.'/documentation/webodv-data-extractor-howto.pdf' => public_path('documentation/webodv-data-extractor-howto.pdf'),
        ]);

        
        //publish traits
        $this->publishes([
            __DIR__.'/webodvTraits' => app_path('webodvTraits'),
        ]);

        //notification email
        $this->publishes([
            __DIR__.'/notifications' => resource_path('views/vendor/notifications'),
        ]);
        

        //publish migrations
        $this->publishes([
            __DIR__.'/migrations' => base_path('database/migrations'),
        ]);

        //publish seeder
        $this->publishes([
            __DIR__.'/seeds' => base_path('database/seeders'),
        ]);

        //publish models
        $this->publishes([
            __DIR__.'/models' => app_path(),
        ]);

        //publish factories
        $this->publishes([
            __DIR__.'/factories' => base_path('database/factories'),
        ]);

        //publish artisan commands
        $this->publishes([
            __DIR__.'/commands' => app_path('Console/Commands'),
        ]);

        //publish webodvLibs
        $this->publishes([
            __DIR__.'/webodvLibs' => app_path('webodvLibs'),
        ]);

        //publish ShellScripts
        $this->publishes([
            __DIR__.'/ShellScripts' => app_path('ShellScripts'),
        ]);

        

    }
}
