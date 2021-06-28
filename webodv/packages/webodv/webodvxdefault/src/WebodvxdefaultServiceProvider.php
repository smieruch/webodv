<?php

namespace Webodv\Webodvxdefault;

use Illuminate\Support\ServiceProvider;
/* use Illuminate\Console\Scheduling\Schedule; */
/* use App\webodvLibs\wsODV_manager; */


class WebodvxdefaultServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->make('Webodv\Webodvxdefault\WebodvxdefaultController');

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

        //publish routes
        $this->publishes([
            __DIR__.'/routes/web.php' => base_path('routes/web.php'),
        ]);


        //publish routes
        $this->publishes([
            __DIR__.'/webodvTraits' => app_path('webodvTraits'),
        ]);


        
        
        /* //publish layouts */
        /* //overwrite app.blade.php */
        $this->publishes([
            __DIR__.'/layouts' => resource_path('views/layouts'),
        ]);

        //publish js
        $this->publishes([
            __DIR__.'/js' => public_path('js/webodv'),
        ]);

        //publish views
        $this->publishes([
            __DIR__.'/views/webodvextractor.blade.php' => resource_path('views/webodv/webodvextractor/webodvextractor.blade.php'),
        ]);
        /* $this->publishes([ */
        /*     __DIR__.'/views/awiimportclient.blade.php' => resource_path('views/webodv/awiimportclient.blade.php'), */
        /* ]); */
        /* $this->publishes([ */
        /*     __DIR__.'/views/awiimport.blade.php' => resource_path('views/webodv/awiimport.blade.php'), */
        /* ]); */
        
        //publish auth views
        $this->publishes([
            __DIR__.'/views/auth' => resource_path('views/auth'),
        ]);

        //publish controllers
        /* $this->publishes([ */
        /*     __DIR__.'/controllers/auth' => app_path('Http/Controllers/Auth'), */
        /* ]); */
        
        //css
        $this->publishes([
            __DIR__.'/css' => public_path('css'),
        ]);


        //img
        $this->publishes([
            __DIR__.'/img' => public_path('images'),
        ]);

        //documentation
        $this->publishes([
            __DIR__.'/documentation/webodv-data-extractor-howto.pdf' => public_path('documentation/webodv-data-extractor-howto.pdf'),
        ]);

        //config services
        /* $this->publishes([ */
        /*     __DIR__.'/config' => base_path('config'), */
        /* ]); */

        //providers
        /* $this->publishes([ */
        /*     __DIR__.'/providers' => app_path('Providers'), */
        /* ]); */
        
        //socialiteproviders
        /* $this->publishes([ */
        /*     __DIR__.'/socialiteproviders' => base_path('vendor/socialiteproviders'), */
        /* ]); */

        //socialite
        /* $this->publishes([ */
        /*     __DIR__.'/socialite' => base_path('vendor/laravel/socialite/src/Two/'), */
        /* ]); */

        //publish middleware
        $this->publishes([
            __DIR__.'/middleware' => app_path('Http/Middleware'),
        ]);

        //publish webodvLibs
        $this->publishes([
            __DIR__.'/webodvLibs' => app_path('webodvLibs'),
        ]);


    }
}
