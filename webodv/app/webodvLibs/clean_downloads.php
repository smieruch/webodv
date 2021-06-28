<?php

namespace App\webodvLibs;

use Illuminate\Support\Facades\Log;

class clean_downloads {



    public function clean(){
        //Log::info("clean_downloads");
        $files = scandir(public_path('downloads'));

        $current_time = time();

        foreach ($files as $file){
            //Log::info($file);
            $ext = pathinfo($file,PATHINFO_EXTENSION);
            $path = public_path('downloads/');
            //Log::info($ext);
            if ($ext=="zip"){
                //Log::info("remove zip file");
                //get current datetime
                //Log::info(filemtime($path.$file));

                $d = $current_time - filemtime($path.$file);
                //Log::info($d);
                //seconds
                if ($d > 86400){
                    unlink($path.$file);
                }
            }
        }
    }


}