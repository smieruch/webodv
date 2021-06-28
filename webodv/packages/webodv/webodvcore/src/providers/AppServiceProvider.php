<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        
        if (getenv('REVERSE_PROXY')){
            \URL::forceScheme(getenv('FORCE_SCHEME'));
            \URL::forceRootUrl(getenv('FORCE_ROOT_URL'));
        }        
    }
}
