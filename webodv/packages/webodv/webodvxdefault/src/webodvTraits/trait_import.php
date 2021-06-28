<?php 
namespace App\webodvTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\webodvLibs\webodv_load_settings;
use App\webodvLibs\wsODV_manager;
use App\Wsodv_available;
use Auth;
use WebSocket\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Imagick;

trait trait_import
{
    public function import($Data)     
    {               

        Log::info("trait_import");
        Log::info(print_r($Data,1));

        
        //load settings
        $private_workspace = "";
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => ""]);
        $odv_data = $webodv_load_settings->load_odv(['datasetname' => $Data["datasetname"], 'private_workspace' => $private_workspace]);
        //-------------- SETTINGS ------------------------------//

        if ($webodv_settings["virtual_screen"]){
            $virtual_display = 'enable'; //enable disable
        } else {
            $virtual_display = 'disable'; //enable disable
        }


        //get ip of user
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];  
            //Log::info("real ip = ".$ip);
        } else {
            $ip = $request->ip();
        }
        
        //create unique user name
        //$id = Str::random(8);
        //$user = 'awiimport_'.$id.'_'.$ip;
        $user = 'awiimport_'.$ip;

        //allowed webodv instances
        $allowed_wsodv_download_instances = $webodv_settings["allowed_wsodv_download_instances"];

        //clean db for that user
        $cleaner = new wsODV_manager();
        $cleaner->clean($user);

        //check allowed download
        $current_wsodv_download_instances = DB::connection('wsodv-manager')->table('wsodv_used')->where('user', '=', $user)->get();
        
        if (count($current_wsodv_download_instances) >= $allowed_wsodv_download_instances){
            Log::info("download not allowed");
            return ["success" => false, "current_wsodv_download_instances" => count($current_wsodv_download_instances)];            
        }
        
        //processing is allowed
        //
        //attention: we have no session here, let's see if it works
        //
        //get free port / proxy
        $wsodv_available = Wsodv_available::first();
        //Log::info(print_r($wsodv_available,1));
        $webodv_settings_path = config('webodv.settings_path') . '/' . config('webodv.path_to_odv_settings');
        //create wsODV_manager object
        $wsodv_manager = new wsODV_manager();
        //start wsODV
        $answer = $wsodv_manager->start_wsodv(
            [
                'odv_file_path' => public_path('init_collection.odv'), // see Start_wsODV.bash
                'settings' => $webodv_settings_path . '/' . $webodv_settings["odv_settings"],
                'homedir' => $webodv_settings["homedir"],
                'user' => $webodv_settings["user"],
                'exepath' => $webodv_settings["odv_exe_path"],
                'virtual_display' => $virtual_display,
                'readwrite' => 'ReadWrite',
                'port' => $wsodv_available->port,
                'url' => $wsodv_available->url,
                'auto_shutdown' => '-auto_shutdown',
                'disable_exports' => 'dummy',
                'admin_password' => $webodv_settings['wsodv_secret'],                
            ]);

        //delete db available
        $wsodv_available->delete();
        //secret
        $wsodv_secret = $webodv_settings["wsodv_secret"];
        //connect to wsODV
        $answer = $wsodv_manager->connect_to_wsodv(
            [
                'url' => $wsodv_available->url,
                'client_url' => session('client_url'), //url('/'), //$webodv_settings["client_url"],
                'port' => $wsodv_available->port,
                'proxy' => $wsodv_available->proxy,
                'ssl' => $webodv_settings["ssl"],
                'ssl_client' => $webodv_settings["ssl_client"],
                'http_port' => session('http_port'), //$webodv_settings["http_port"],
                'wsodv_secret' => $wsodv_secret,
                'user' => $user,
                'mode' => 'single',
                'permanent' => false,
                'server' => true,
                'odv_file_path' => "dummy",
            ]);

        Log::info("answer = ".$answer );
        $session_id = $answer;

        //connect
        $client=new Client('ws://localhost:' . $wsodv_available->port,['timeout' => 86400]);
        //Log::info(print_r($client,1));
        //die();
        //login to wsODV and send export msg
        //note that the output dir is defined in PKTodv_settings
        $websocket_msg = [
            "sender_id" => $session_id,
            "cmds" =>  [
                [
                    "cmd" => "login_request",
                    "view" => '$FullScreenMap$',
                ],
                [
                    "cmd" => "import_data",
                    "type" => "SPREADSHEET",
                    "input" => $Data['listfile'],
                    //"output_dir" =>  public_path('downloads/') . $new_folder . '/' . $new_output_dir . '/' ,
                    //"output_dir" => storage_path('app/local/') . $Data['datasetname'] . '/',
                    "output_dir" => storage_path('app/'.$Data['Path_to_odv_data'].'/'.$Data['datasetpath'] . '/'),
                ],
                [
                    //"cmd" => "logout_request",
                    //"save_view" => false,
                ],                
            ]
        ];
        $websocket_msg_json = json_encode($websocket_msg);
        //send
        $client->send($websocket_msg_json);

        $success = false;
        //answers
        while (true) {
            $answer = $client->receive();
            $answer_json = json_decode($answer);
            Log::info($answer);
            if ($answer_json->reply_id == "login_request" && $answer_json->success == true){
                Log::info("Import login ok");
            }
            if ($answer_json->reply_id == "login_request" && $answer_json->success == false){
                Log::info("Import login false");
                break;
            }
            if ($answer_json->reply_id == "import_data" && $answer_json->success == true){
                $success = true;
                break;
            }            
        }
        //////////////////////////////////////////////////////////////


        
        return ["success" => $success];
        
    }
    
}


?>