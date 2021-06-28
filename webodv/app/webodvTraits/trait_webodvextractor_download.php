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

trait trait_webodvextractor_download
{
    public function create_download(Request $request)     
    {               

        //check auth

        if (!Auth::check()){
            return ["auth" => false];
        }
                
        /* Log::info(print_r($request->all(),1)); */
        /* die(); */
        
        //validate
        $validator = Validator::make($request->all(), [
            'coords.*' => 'required',
            'cruises.*' => 'nullable',
            'pointsize' => 'required',
            'date1' => 'required',
            'date2' => 'required',
            'OutVar.*' => 'required',
            'ReqVarList.*' => 'nullable',
            'ReqVarEx' => 'nullable',
            'file_format' => 'required',
            'datasetname' => 'required',
            'viz_x_var' => 'nullable',
            'viz_y_var' => 'nullable'
        ]);
        Log::info(print_r($validator->errors(),1));
        /* Log::info(print_r($validator->fails(),1)); */

        /* Log::info("cruises!!!"); */
        /* Log::info(print_r($request->cruises,1)); */
        
        if ($validator->fails()){
            return ["auth" => true, "validate" => false];
        }

        //-------------- SETTINGS ------------------------------//
        //$webodv_settings = $this->check_cache('webodv_settings','.json',true);
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => $request->branch]);

        Log::info("branch in trait_webodvextractor_download");
        Log::info($request->branch);


        $private_workspace = '';
        //vre
        if ($request->has('private_workspace')){
            $private_workspace = $request->private_workspace;
        }


        //Log::info("datasetname");
        //Log::info($request->datasetname);
        
        //read json config file
        $webodv_load_settings = new webodv_load_settings();
        $odv_data = $webodv_load_settings->load_odv(['datasetname' => $request->datasetname, 'private_workspace' => $private_workspace]);
        //-------------- SETTINGS ------------------------------//

        //Log::info(print_r($odv_data,1));
        
        
        
        if ($webodv_settings["virtual_screen"]){
            $virtual_display = 'enable'; //enable disable
        } else {
            $virtual_display = 'disable'; //enable disable
        }

        
        //this is the number of allowed_wsodv_instances
        //used to prevent for DOS attacks and
        //to limit ressources per user
        $allowed_wsodv_download_instances = $webodv_settings["allowed_wsodv_download_instances"];
        Log::info("allowed_wsodv_download_instances=".$allowed_wsodv_download_instances);

        Log::info("trait_webodvextractor_download");
        Log::info(print_r($webodv_settings,1));
        
        //get user
        $email = Auth::user()->email;
        //$email = "hallo@hallo.de";
        $user = str_replace('@','.at.',$email);
        $user = 'download_'.$user;

        //clean
        $cleaner = new wsODV_manager();
        $cleaner->clean($user);


        //check alloed download
        $current_wsodv_download_instances = DB::connection('wsodv-manager')->table('wsodv_used')->where('user', '=', $user)->get();
        Log::info('number of download for user ' . $user . ' = ' . count($current_wsodv_download_instances));

        
        if (count($current_wsodv_download_instances) >= $allowed_wsodv_download_instances){
            Log::info("download not allowed");
            return ["auth" => true, "validate" => true, "current_wsodv_download_instances" => count($current_wsodv_download_instances)];            
        } else {
            Log::info("download is allowed");
        }

        
        if ($webodv_settings['project'] == 'webODV-AWI'){
            $start_wsodv_user = Auth::user()->name;
        } else {
            $start_wsodv_user = $webodv_settings["user"];
        }


        
        //get free port / proxy
        $wsodv_available = Wsodv_available::first();
        //Log::info(print_r($wsodv_available,1));
        $webodv_settings_path = config('webodv.settings_path') . '/' . config('webodv.path_to_odv_settings');
        //create wsODV_manager object
        $wsodv_manager = new wsODV_manager();
        //start wsODV
        $answer = $wsodv_manager->start_wsodv(
            [
                'odv_file_path' => $odv_data['path'],
                'settings' => $webodv_settings_path . '/' . $webodv_settings["odv_settings"],
                'homedir' => $webodv_settings["homedir"],
                'user' => $start_wsodv_user,
                'exepath' => $webodv_settings["odv_exe_path"],
                'virtual_display' => $virtual_display,
                'readwrite' => 'ReadOnly',
                'port' => $wsodv_available->port,
                'url' => $wsodv_available->url,
                'auto_shutdown' => $odv_data['auto_shutdown'],
                'disable_exports' => $odv_data['disable_exports'],
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
                'odv_file_path' => $odv_data['path'],
            ]);

        //Log::info("answer = ".$answer );
        $session_id = $answer;

        //cruise snipped
        if (empty($request->cruises)){
            Log::info("cruises empty");
            $cruise_snippet = '<StationSelectionCriteria> <Names cruise="*"/> </StationSelectionCriteria>';
        } else {
            Log::info("cruises not empty");
            $cruise_snippet = '<StationSelectionCriteria> <Names cruise="'. implode(" || ",$request->cruises) .'"/> </StationSelectionCriteria>';
        }



        //create modify view snippets
        $domain_snippet = '<GeographicMap> <MapDomain lon_min="' . $request->coords[0] . '" lon_max="' . $request->coords[1] . '" lat_min="' . $request->coords[2] . '" lat_max="' . $request->coords[3] . '"/> </GeographicMap>' . '<MapWindow  background_color="31"> <Symbol small_size="0.4" color="1" type="0" size="' . $request->pointsize . '"/>';

        $date1 = explode('/',$request->date1);
        $date2 = explode('/',$request->date2);
        $date_snippet = '<StationSelectionCriteria>' .  '<Period first_day="' . $date1[1] . '" last_year="' . $date2[2] . '" first_month="' . $date1[0] . '" last_month="' . $date2[0] . '" last_day="' . $date2[1] . '" first_year="' . $date1[2] . '" first_hour="' . "0" . '" last_hour="' . "24" . '" first_minute="' . "1" . '" last_minute="' . "60" .'"/>'   . '</StationSelectionCriteria>';

        $reqvar_snippet = '<StationSelectionCriteria>' . '<RequiredVarExpression expr="' . $request->ReqVarEx . '"/>'  . '</StationSelectionCriteria>';

        Log::info("Required Vars");
        Log::info(print_r($request->ReqVarEx,1));
        
        //Log::info($domain_snippet . " " . $date_snippet . " " . $reqvar_snippet);
        
        $output_file_tmp = str_replace('>','_',$request->datasetname) . '_' . Str::random(8);
        $output_file_path = public_path('downloads/').$output_file_tmp . '.zip';
        $output_file_url = url('/').'/downloads/'.$output_file_tmp . '.zip';


        //output vars
        Log::info("outvars before");
        Log::info(print_r($request->OutVar,1));
        
        $OutVar = [];
        foreach ($request->OutVar as $var){
            //geotraces
            $conc_vars = explode('|',$var);
            foreach ($conc_vars as $conc_var){
                $OutVar[] = intval($conc_var);
            }
        }
        Log::info("outvars after");
        Log::info(print_r($OutVar,1));

        
        //connect
        $client=new Client('ws://localhost:' . $wsodv_available->port,['timeout' => 86400]);
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
                    "cmd" => "get_collection_information",
                ]
            ]
        ];
        $websocket_msg_json = json_encode($websocket_msg);
        $client->send($websocket_msg_json);
        $extended_metavar_ids = [];
        for ($i=1;$i<=2;$i++) {
            $answer = $client->receive();
            //Log::info("answer= " . $answer);
            $answer_json = json_decode($answer);
            if ($answer_json->reply_id == "get_collection_information" && $answer_json->success == true){
                //Log::info(print_r($answer_json->extended_metavar_ids,1));
                $extended_metavar_ids = $answer_json->extended_metavar_ids;
            }
        }
                    



        $websocket_msg = [
            "sender_id" => $session_id,
            "cmds" =>  [
                [
                    "cmd" => "modify_view",
                    "view_snippet" => $cruise_snippet,
                ],
                [
                    "cmd" => "modify_view",
                    "view_snippet" => $domain_snippet,
                ],
                [
                    "cmd" => "modify_view",
                    "view_snippet" => $date_snippet,
                ],
                [
                    "cmd" => "modify_view",
                    "view_snippet" => $reqvar_snippet,
                ],
                [
                    "cmd" => "export_data",
                    "file" => $output_file_tmp . '.' . $request->file_format,
                    "extended_metavar_ids" => $extended_metavar_ids,
                    "var_ids" => $OutVar,
                    "error_export" => true,
                    "info_export" => false,
                    "quality_flag_export" => true,
                    "history_export" => true,
                    "compact_export" => false,
                ],
                [
                    "cmd" => "logout_request",
                    "save_view" => false,
                ],
            ]
        ];
        $websocket_msg_json = json_encode($websocket_msg);
        Log::info($websocket_msg_json);
        $client->send($websocket_msg_json);
        $export_success = false;
        for ($i=1;$i<=6;$i++) {
            $answer = $client->receive();
            Log::info("answer= " . $answer);
            $answer_json = json_decode($answer);
            if ($answer_json->reply_id == "export_data" && $answer_json->success == true){
                $export_success = true;
            }
        }


        //clean
        $cleaner = new wsODV_manager();
        $cleaner->clean($user);

        
        return ["export_success" => $export_success, "output_file_url" => $output_file_url, "email" => $email, "output_file_path" => $output_file_path, "auth" => true, "validate" => true];




    }
    
}


?>