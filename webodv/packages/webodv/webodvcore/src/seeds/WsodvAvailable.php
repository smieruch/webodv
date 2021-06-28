<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Wsodv_available;
use Illuminate\Support\Facades\Log;

class WsodvAvailable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Log::info("seed");

        //only if db is empty, i.e. null
        $Wsodv_available_first = Wsodv_available::first();

        if (is_null($Wsodv_available_first)){
             $wsODV_URLs = config('webodv.proxy');
            //Log::info($wsODV_URLs);
            if (file_exists($wsODV_URLs)) {
                $wsODV_URLs_List = file($wsODV_URLs);
                foreach ($wsODV_URLs_List as $item) {
                    //Log::info(trim($item));
                    $line = trim($item);
                    $line = explode(' ',$line);
                    $full_url = $line[2];
                    $full_url = explode(':',$full_url);
                    $url = $full_url[0].':'.$full_url[1];
                    $url = explode('//',$url);
                    $url = $url[1];
                    $port = substr($full_url[2],0,-1);
                    $proxy = $line[1];
                    //fill database
                    $entry = new Wsodv_available();
                    $entry->url = $url;
                    $entry->port = $port;
                    $entry->proxy = $proxy;
                    $entry->save();
                }
            }
        }
    }
}
