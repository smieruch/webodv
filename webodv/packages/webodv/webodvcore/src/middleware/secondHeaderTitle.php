<?php

namespace App\Http\Middleware;

use Closure;

class secondHeaderTitle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        session(['dataset' => '']);
        session(['dataset_link' => '']);
        session(['vre_brand' => '']);
        session(['vre_url' => '']);

        //selete session entries for services
        session(['service' => '']);

        return $next($request);
    }
}
