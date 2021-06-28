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

trait trait_apiwms
{
    public function create_image(Request $request)     
    {               
        Log::info(print_r($request->all(),1));

        $validatedData = $request->validate([
            "LAYERS" => ['required'],
            "STYLES" => ['required'], 
            "TRANSPARENCY" => ['required'], 
            "FORMAT" => ['required'], 
            "SERVICE" => ['required'], 
            "VERSION" => ['required'], 
            "REQUEST" => ['required'], 
            "ELEVATION" => ['required'], 
            "TIME" => ['required'], 
            "CRS" => ['required'], 
            "BBOX" => ['required'], 
            "Width" => ['required'], 
            "Height" => ['required'] 
        ]);

        //load settings
        $private_workspace = "";
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => ""]);
        $odv_data = $webodv_load_settings->load_odv(['datasetname' => $request->LAYERS, 'private_workspace' => $private_workspace]);
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
        $id = Str::random(8);
        $user = 'apiwms_'.$id.'_'.$ip;

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
                'odv_file_path' => $odv_data['path'],
                'settings' => $webodv_settings_path . '/' . $webodv_settings["odv_settings"],
                'homedir' => $webodv_settings["homedir"],
                'user' => $webodv_settings["user"],
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
                ]
            ]
        ];
        $websocket_msg_json = json_encode($websocket_msg);

        //Log::info("websocket_msg_json");
        //Log::info(print_r($websocket_msg_json,1));
        //
        //send
        $client->send($websocket_msg_json);

        //answers
        $answer = $client->receive();
        $answer_json = json_decode($answer);
        if ($answer_json->reply_id == "login_request" && $answer_json->success == true){
            Log::info("Download login ok");
        }
        if ($answer_json->reply_id == "login_request" && $answer_json->success == false){
            Log::info("Download login false");
            return ["success" => false, "odvws_login" => false];
        }
        //////////////////////////////////////////////////////////////

        //modify view, export img
        $BBOX = explode(',',$request->BBOX);
        $POINTSIZE = 1.2;
        $x_proj = 100;
        $y_proj = 100;
        if ($request->TRANSPARENCY == "true"){
            $draw_coast_etc = "F";
            $axis_color = "-1";
            $map_bg_color = "-1";
            $extended_rect = false;
        } else {
            $draw_coast_etc = "T";
            $axis_color = "0";
            $map_bg_color = "0";
            $extended_rect = true;
        }
        $domain_snippet = '<GeographicMap projection_type="-1"><AutoMapLayerSettings ocean_bathymetry="'.$draw_coast_etc.'" coastline_fill="'.$draw_coast_etc.'" coastlines="'.$draw_coast_etc.'"/> <MapDomain lon_min="' . $BBOX[1] . '" lon_max="' . $BBOX[0] . '" lat_min="' . $BBOX[3] . '" lat_max="' . $BBOX[2] . '"/> </GeographicMap>' . '<MapWindow  background_color="'.$map_bg_color.'"> <Axis color="'.$axis_color.'" create_labels="'.$draw_coast_etc.'" draw_grid="'.$draw_coast_etc.'"/> <Symbol small_size="0.2" color="1" type="0" size="' . $POINTSIZE . '" <Window> <Geometry x_left="' . $BBOX[1] . '" x_right="' . $BBOX[0] . '" y_bottom="'. $BBOX[3] . '" y_top="'. $BBOX[2] .'" x_projection="'.$x_proj.'" y_projection="'.$y_proj.'" x_offset_bb="0" x_length_bb="20" y_offset_bb="0" y_length_bb="20"/> </Window> </MapWindow>';

        $websocket_msg = [
            "sender_id" => $session_id,
            "cmds" =>  [
                [
                    "cmd" => "modify_view",
                    "view_snippet" => $domain_snippet,
                ],
    		    [
                    "cmd" => 'export_graphics',
                    'win_id' => 0,
                    'fmt' => "png",
                    'dpi' => 300,
                    'transparent_background' => true,
                    'extended_rect' => $extended_rect,
    		    ]
            ]
        ];

        $websocket_msg_json = json_encode($websocket_msg);

        //Log::info("websocket_msg_json");
        //Log::info(print_r($websocket_msg_json,1));
        //
        //send
        $client->send($websocket_msg_json);

        //answers
        $output = "false";
        $m = 1;
        while (true) {
            $answer = $client->receive();
            $answer_json = json_decode($answer);
            Log::info("answer:".$m);
            $m++;
            Log::info(print_r($answer_json,1));
            if (is_object($answer_json)){
                Log::info("this is an object!");
                if ($answer_json->reply_id == "export_graphics" && $answer_json->success == true){
                    break;
                }
            } else {
                Log::info("this is NOT an object!");
                //Log::info(print_r($answer,1));
                $output = $answer;
            }
            /* if ($answer_json->reply_id == "login_request" && $answer_json->success == false){ */
            /*     Log::info("Download login false"); */
            /*     return ["success" => false, "odvws_login" => false]; */
            /* } */
        }
        
        

        ///////////////////////////////////////////////////////////////
        //logout
        $websocket_msg = [
            "sender_id" => $session_id,
            "cmds" =>  [
                [
                    "cmd" => "logout_request",
                    "save_view" => false,
                ]
            ]
        ];
        $websocket_msg_json = json_encode($websocket_msg);

        Log::info("websocket_msg_json");
        Log::info(print_r($websocket_msg_json,1));
        //
        //send
        $client->send($websocket_msg_json);

        //answers
        $answer = $client->receive();
        $answer_json = json_decode($answer);
        Log::info(print_r($answer_json,1));
        



        Log::info(print_r($output,1));
        //cut out the first 256 bytes
        $head = substr($output,0,256);
        Log::info("head=".$head);
        $body = substr($output,256);
        
        Log::info(gettype($output));
        $image = new Imagick();
        $image->readimageblob($body);
        $image->setImageFormat("png");
        $image->scaleImage($request->Width, $request->Height, false);



        //file_put_contents(public_path('downloads/api.png'), $image->getImageBlob());
        
        //Log::info(print_r($image,1));
        
        //return $image;
        
        //echo '<img src="data:image/png;base64,' .  base64_encode($image->getimageblob())  . '" />';

        /* Log::info(print_r(base64_encode($image->getimageblob()),1)); */
        /* return response($image->getimageblob()); */

        //echo $image->getimageblob();
        //return $image->getImageBlob();

        //return public_path('downloads/api.png');

        return $image;

        
        /* fpassthru($image->getimageblob()); */
        /* exit; */
        
        //return json_encode("success");
        
    }
    
}


?>