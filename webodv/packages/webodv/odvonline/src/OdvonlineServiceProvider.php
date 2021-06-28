<?php

namespace Webodv\Odvonline;

use Illuminate\Support\ServiceProvider;


class OdvonlineServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('Webodv\Odvonline\OdvonlineController');

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

        //publish views
        $this->publishes([
            __DIR__.'/views' => resource_path('views/webodv/odvonline'),
        ]);


        $this->publishes([
            __DIR__.'/assets' => public_path('js/webodv/odvonline'),
        ]);

        $this->publishes([
            __DIR__.'/js' => public_path('js/webodv'),
        ]);
        

    }
}
