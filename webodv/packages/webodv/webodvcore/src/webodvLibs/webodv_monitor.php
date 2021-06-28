<?php

namespace App\webodvLibs;

use Illuminate\Support\Facades\Log;
use App\Wsodv_used;
use App\monitor;
use Auth;

class webodv_monitor {

    public function monitor_process() {

        //Log::info("monitor_process");
        //get number of odvws instances
        
        $used_all = Wsodv_used::all();
        //Log::info(print_r(count($used_all),1));
        $num_odvws = count($used_all);

        //get CPU and RAM
        $msg = app_path("ShellScripts/Monitor.bash");
        //Log::info($msg);
        $monitor_stats = shell_exec($msg);
        //Log::info($monitor_stats);
        $monitor_arr = explode(',',$monitor_stats);


        //fill used db
        $monitor_db = new monitor;
        $monitor_db->cpu = trim($monitor_arr[0]);
        $monitor_db->mem = trim($monitor_arr[1]);
        $monitor_db->odvws_instances = $num_odvws;
        $monitor_db->save();
        
        
        return json_encode("success");
    }



}
