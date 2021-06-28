<?php

namespace App\webodvLibs;

use WebSocket\Client;
use Illuminate\Support\Facades\Log;
use App\Wsodv_used;
use App\Wsodv_available;
use App\webodvLibs\webodv_load_settings;
use Illuminate\Support\Facades\Cache;
use Auth;

class wsODV_manager {



    public function start_wsodv(Array $data){

        
        $msg = "sudo -u " . $data['user'] . " -H " . app_path("ShellScripts/Start_wsODV.bash") . " " . $data["odv_file_path"] . " " . $data["settings"] . " " . $data["homedir"] . " " . $data["exepath"] . " " . $data["virtual_display"] . " " . $data['readwrite'] . " " . $data['port'] . " " . $data['disable_exports'] . " " . $data['auto_shutdown'] . " " . $data['admin_password'];   //2>/dev/null
        Log::info($msg);
        $pid = shell_exec($msg);
        Log::info("pid=".$pid);

        session(['pid' => $pid]);

        return json_encode("success");
    }


    public function connect_to_wsodv(Array $data){


        Log::info(print_r($data,1));

        
        //get ssl info for server
        if ($data['ssl']){
            $websocket = 'wss://';
            $web = 'https://';
        } else {
            $websocket = 'ws://';
            $web = 'http://';
        }

        //get ssl info for client
        if ($data['ssl_client']){
            $websocket_client = 'wss://';
            $web_client = 'https://';
        } else {
            $websocket_client = 'ws://';
            $web_client = 'http://';
        }
        


        //create wsODV session
        //loop is not needed anymore, because odvws returns "collection ready"
        //in Start_wsODV.bash
        //however, for the import service I start an odvws without a collection
        //thus collection ready is not returned
        //thus I will still use the loop
        //if collection ready is used, fine than it needs only one try otherwise maybe two tries.
        for ($i=1;$i<=16;$i++) {
            try {
                //Log::info("try now 0");
                //create websocket client
                $client=new Client($websocket . $data['url'] . ':' . $data['port'],['timeout' => 60]);
                //Log::info(print_r($client,1));
                //die();
                //create message
                $websocket_msg=[
                    "sender_id" => $data['wsodv_secret'],
                    "cmds" =>  [[
                        "cmd" => "session_request",
                        "user" => $data['user'],
                    ],
                    ],
                ];
                //Log::info("try now 1");
                //send message
                $client->send(json_encode($websocket_msg));
                //receive answer
                //Log::info("try now 2");
                $answer=json_decode($client->receive());
                //Log::info(print_r($answer,1));
                //close connection
                //Log::info("try now 3");
                $client->close();
                //echo print_r($answer,1);
                //Log::info(print_r($answer,1));
                if ($answer->success) {
                    $session_id=$answer->session_id;
                    $collection_name=$answer->collection_name;
                } else {
                    $session_id=$answer->msg;
                    $collection_name="";
                }

                $wsUri = $websocket_client . $data['client_url'] . $data['http_port'] . $data['proxy'];
                //$wsUri = $websocket_client . $data['client_url'] . $data['http_port'] . '/' . $data['port'];
                //$wsUri = $websocket_client . '127.0.0.1' . $data['http_port'] . '/' . $data['port'];
                session(['resourceRoot' => 'js/webodv/odvonline/_shared/']);
                if ($data['server']){
                    //send session id to PHP
                    $js_dump = $session_id;
                } else {
                    //dump to JavaScript in odvonlineController  
                    /* let collectionName="<%= collectionName %>";
		     * let initialView="<%= initialView %>";
		     * let isLocalHost=false;
		     * let resourceRoot="<%= staticFilePathPrefix %>";
		     * let returnUrl='';
		     * let sessionId="<%= sessionId %>";
		     * let userId="<%= userId %>";
		     * let wsUri="<%= wsUri %>";
		     */
                    $js_dump = '<script>'.
			       'let wsUri="'.$wsUri.'";'.
			       'let isLocalHost=false;'.
			       'let collectionName="'.$collection_name.'";'.
			       'let initialView="$FullScreenMap$";'.
			       'let sessionId="'.$session_id.'";'.
			       'let userId="'.$data['user'].'";'.
			       'let resourceRoot="js/webodv/odvonline/_shared/";'.
			       'let returnUrl="'.url("/").'";'.
			       //'let returnUrl="";'.			       
			       '</script>';
                }
                //fill used db
                $wsodv_used = new Wsodv_used;
                $wsodv_used->url = $data['url'];
                $wsodv_used->port = $data['port'];
                $wsodv_used->proxy = $data['proxy'];
                $wsodv_used->permanent = $data['permanent'];
                $wsodv_used->mode = $data['mode'];
                $wsodv_used->user = $data['user'];
                $wsodv_used->dataset = $data['odv_file_path'];
                if (session()->exists('pid')){
                    $wsodv_used->pid = session('pid');
                } else {
                    $wsodv_used->pid = "none";
                }		
                $wsodv_used->save();
                break;
            }
            catch (\WebSocket\ConnectionException $e) {
                //error_log($e);
                Log::info("wsODV not ready yet !!!!!!!!!!!!!!!!!!!!");
            }
            //wait a second until the next try
            sleep(1);
        }
        return $js_dump;

    }



    public function clean($mode) {

        //if $mode="all" check all DB
        //if $mode other then check only for that user, e.g. sebastian.mieruch@awi.de or 172.3.2.1 or download_sebastian.mieruch@gmx.de etc.
        //Log::info("wsODV_cleaner.php");

        //sleep(10);
        
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);

        /* $webodv_settings_path = config('webodv.settings_path') . '/' . config('webodv.path_to_odv_settings'); */
        /* $webodv_settings = file_get_contents($webodv_settings_path.'/webodv_settings.json'); */
        /* $webodv_settings = json_decode($webodv_settings,true); */


        
        //secret
        $wsodv_secret = $webodv_settings['wsodv_secret'];
        //get server ssl info
        if ($webodv_settings['ssl']){
            $websocket = 'wss://';
            $web = 'https://';
        } else {
            $websocket = 'ws://';
            $web = 'http://';
        }

        
        //go through db
        if ($mode == "all"){
            $used_all = Wsodv_used::all();
        } else {
            $used_all = Wsodv_used::where('user', '=', $mode)->get();
        }
        foreach ($used_all as $used){
            //Log::info("check: ". $used->user . ' port: '.$used->port);
            $shutdown_instance = false;
            try {
                //wait only 2 seconds for an answer then continue
                //in case this is long running job
                //Log::info("create client start");
                $client=new Client($websocket . $used->url . ':' . $used->port,['timeout' => 2]);
                //Log::info("create client done");
                //create message
                $websocket_msg=[
                    "sender_id" => $wsodv_secret,
                    "cmds" =>  [
                        [
                            "cmd" => "status_info_request",
                        ],
                    ],
                ];
                //send message
                $client->send(json_encode($websocket_msg));
                //receive answer
                $status=json_decode($client->receive());
                //Log::info(print_r($status,1));
                //close connection
                //$client->close();
                //retrieve response information
                $shutdown_instance = false;
                if ($status->success == false){
                    $shutdown_instance = true;
                    //Log::info("error");
                    //Log::info("shutdown this instance and clear DB");
                }
                if ($status->success == true && $status->session_count == 0 ){ //&& $status->session_request_count == 0) {
                    $shutdown_instance = true;
                    //Log::info("no wsODV user");
                    Log::info("shutdown this instance and clear DB");
                } else {
                    Log::info("wsODV instance active");
                }

                if ($shutdown_instance){
                    //msg
                    $websocket_msg=[
                        "sender_id" => $wsodv_secret,
                        "cmds" =>  [
                            [
                                "cmd" => "shutdown_request",
                            ],
                        ],
                    ];
                    //send message
                    $client->send(json_encode($websocket_msg));
                    //bring back URL and delete db entry
                    $available = new Wsodv_available();
                    $available->url = $used->url;
                    $available->port = $used->port;
                    $available->proxy = $used->proxy;
                    $available->save();
                    $used->delete();
                }
            }
            catch (\WebSocket\ConnectionException $e) {

		$PID = trim($used->pid);

		
		Log::info("instance " . $PID . " not reachable");

                $current_time = time();
                $this_instance_time = strtotime($used->created_at);
                $diff_instance = $current_time-$this_instance_time;
                
                /* Log::info("current_time=".$current_time); */
                /* Log::info("this_instance_time=".$this_instance_time); */
                /* Log::info("diff_instance=".$diff_instance); */

		if (empty(shell_exec('ps -p ' . $PID . '| grep odvws'))){
		    Log::info("instance " . $PID . " not existing");
		    //delete db entry
                    $available = new Wsodv_available();
                    $available->url = $used->url;
                    $available->port = $used->port;
                    $available->proxy = $used->proxy;
                    $available->save();
                    $used->delete();		    
		} else {
		    Log::info("instance " . $PID . " exists");
		    //if an instance exists for more than 4 hours and is not reachable
                    //we kill it
		    //thus long running jobs that are longer than 4 hours are not allowed
		    //actually this here is used for unnormal situations, e.g. a network failure
		    //or other unforeseen situations
                    //do it only in scheduler mode="all"
                    if ($mode == "all"){
			if ($diff_instance > (4*3600)){
                            //
                            //if the cleaner is run as scheduler it is the user woody
                            //if it is run from a PHP script it is user www-data
                            /* Log::info(shell_exec("whoami")); */
                            $msg = app_path("ShellScripts/Kill_wsODV.bash") . ' ' . $PID;
                            //Log::info($msg);
                            shell_exec($msg);
                            //Log::info("kill done");
                            
                            //Log::info("delete db used:");
                            //Log::info($used->port);
                            $available = new Wsodv_available();
                            $available->url = $used->url;
                            $available->port = $used->port;
                            $available->proxy = $used->proxy;
                            $available->save();
                            $used->delete();
			}
                    }		    
		}		
            }
        }
    }

    public function kill($dataset) {
        $user = Auth::user()->email;
        $user = str_replace('@','.at.',$user);

        if ($dataset == "all"){
            $used_all = Wsodv_used::where([
                ['user', $user]
            ])->get();
        } else {
            $used_all = Wsodv_used::where([
                ['user', $user],
                ['dataset', $dataset]
            ])->get();
        }

        
        $pids = "";
        foreach ($used_all as $used){
            //Log::info(print_r($entry,1));
            $pids = $pids . ' ' . trim($used->pid);
            //bring back URL and delete db entry
            $available = new Wsodv_available();
            $available->url = $used->url;
            $available->port = $used->port;
            $available->proxy = $used->proxy;
            $available->save();
            //
            $used->delete();
        }
        
        $msg = "sudo -u woody -H " . app_path("ShellScripts/Kill_wsODV.bash") . " " . '"'.$pids.'"' . " 2>/dev/null";
        Log::info($msg);
        shell_exec($msg);
        
    }   


}
