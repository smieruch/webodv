<?php

namespace Webodv\Webodvcore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Auth;
use App\webodvLibs\wsODV_manager;
use App\webodvLibs\vre;
use App\webodvLibs\create_treeview_from_folder;
use App\webodvLibs\create_json_config;
use App\webodvLibs\create_header_links;
use App\webodvLibs\webodv_load_settings;
use App\Wsodv_available;
// if you are using package Webodv\Vre Guzzle is needed
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
//use Illuminate\Support\Facades\Config;
//use \App\visit_tracking;
use App\webodvTraits\trait_visit_tracking;
use App\webodvTraits\trait_login_tracking;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\contactMail;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Notifications\DownloadFinished;
use Notification;
use Illuminate\Notifications\Notifiable;
use App\webodvTraits\trait_get_branch;
use App\webodvTraits\trait_apiwms;
use ZipArchive;
use App\Wsodv_used;
use App\monitor;
use App\private_workspaces;

class WebodvcoreController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }


    use trait_visit_tracking;
    use trait_login_tracking;
    use trait_get_branch;
    use trait_apiwms;
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        //if this is the testuser from create:dummUser logout
        /* if (Auth::check()){             */
        /*     if (Auth::user()->email == "testuser@webodv.de"){ */
        /*         Auth::logout(); */
        /*     } */
        /* } */

        Log::info("index !!!!!!!!!!!!!!!!!!!!");
    
        session(['js_css_update' => '?v=6.3']);
        
        //normally the mounted files and folders will be displayed in the treeview
        //but if there is a subfolder, which should be used define the path here
        //this is also changed for VRE
        //used in create_json_config
        //session(['private_workspace' => '']);

        $branch = $this->getBranch();
        //$branch_array = explode('/',url()->current());
        //Log::info("service_branch");
        //Log::info(print_r($branch_array,1));
        //$branch = $branch_array[3];

        /* $private_workspace = ''; */
        /* //vre */
        /* if ($branch == "private"){ */
        /*     $private_workspace = Auth::user()->private_workspace; */
        /* } */

        session(["awiimport" => false]);
        
        //-------------- SETTINGS ------------------------------//
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);
        
        if (isset($webodv_settings["EMODnet_chem_settings"])){
            $EMODnet_chem_settings = $webodv_settings["EMODnet_chem_settings"];
            $marine_id_login = $EMODnet_chem_settings['marine_id_login'];
            $marine_id_logout = $EMODnet_chem_settings['marine_id_logout'];
            session(['marine_id_logout' => $marine_id_logout]);
            session(['marine_id_login' => $marine_id_login]);
        } else {
            $marine_id_login = "";
            $marine_id_logout = "";
        }


        //get client url here
        //$client_url = $webodv_settings["client_url"];
        //
        $this_parse_url = parse_url(url('/'));
        $client_url = $this_parse_url['host'];

        if (isset($this_parse_url['port'])){
            $http_port = ':'.$this_parse_url['port'];
        } else {
            $http_port = "";
        }
        /* Log::info($client_url); */
        /* Log::info($http_port); */
        /* die(); */
        
        session(['client_url' => $client_url]);
        session(['http_port' => $http_port]);
        
        $project = $webodv_settings["project"];

        /* if ($project == "webODV-AWI"){ */
        /*     $private_workspace = '/' . Auth::user()->email . '/webodv'; */
        /*     //session(['private_workspace' => $private_workspace]); */
        /* } */



        session(['project' => $project]);
        $EMODnet = $webodv_settings["EMODnet"];
        $upload = $webodv_settings["upload"];
        $allowed_wsodv_instances = $webodv_settings["allowed_wsodv_instances"];
        $fileselector_filter = $webodv_settings["fileselector_filter"];

        //$index_add_text = $webodv_settings["index_add_text"];
        $index_add_text = $webodv_settings["service"];
        session(['title' => $webodv_settings["title"]]);

        //matomo
        session(['matomo' => $webodv_settings["matomo"]]);

        if (isset($webodv_settings["geotraces_subtitle"])){
            $subtitle = $webodv_settings["geotraces_subtitle"];
            $subtitle = str_replace("this_url",url('/'),$subtitle);
            session(['subtitle' => $subtitle]);
        } else {
            session(['subtitle' => ""]);
        }

        //-------this is now in config webodv
        //-------mode: dev or prod--------------------------//
        //---load .min.js in layouts
        /* if (config('webodv.mode') == "prod"){ */
        /*     //session(['modestr' => '.min']); */
        /*     $modestr = '.min'; */
        /* } */
        /* //---load .js in layouts */
        /* if (config('webodv.mode') == "dev"){ */
        /*     //session(['modestr' => '']); */
        /*     $modestr = ''; */
        /* } */
        $modestr = config('webodv.mode');
        Log::info("mode=".$modestr);

        
        
        //////////////   sdc_vre /////////////////////////////////////////////////////////////////////////////////////
        /* if ($request->has('service_auth_token')){ */
        /*     //Log::info(print_r($request->all(),1)); */
        /*     $vre = new vre(); */
        /*     $vre_data = $vre->integrate($request); */
        /*     Log::info(print_r($vre_data,1)); */
        /* } */

        /* //check if this is a VRE post or a VRE user */
        /* if (Auth::check()){ */
        /*     if ($project == "sdc_vre_p"){ */
        /*         session(['private_workspace' => Auth::user()->private_workspace . '/files']); */
        /*         session(['private_workspace_export' => '/odv_data' . Auth::user()->private_workspace . '/files']); */
        /*     } */
        /*     if ($project == "sdc_vre_o"){ */
        /*         session(['private_workspace' => '']); //this is where the treeview searches */
        /*         session(['private_workspace_export' => '/private_data' . Auth::user()->private_workspace . '/files']); // this is where the export data are saved */
        /*     } */
        /* } */
        /* //////////////   sdc_vre ///////////////////////////////////////////////////////////////////////////////////// */

        
        /* //////////////   sdc_vre ///////////////////////////////////////////////////////////////////////////////////// */
        /* //VRE project is only allowed if logged in */
        /* if ($project == "sdc_vre_p" || $project == "sdc_vre_o"){ */
        /*     if (!Auth::check()){ */
        /*         abort(401); */
        /*     } */
        /* } */
        //////////////   sdc_vre /////////////////////////////////////////////////////////////////////////////////////
        
        //treeview jump is used to automatically expand the treeview based on a path in the URL
        //Log::info("treeview_jump in index:".$request->treeview_jump);
        //get URL parameter treeview_jump
        if (isset($request->treeview_jump)){
            $treeview_jump = $request->treeview_jump;
        } else {
            $treeview_jump = "base";
        }


        //$user_allowed_wsodv = $request->user_allowed_wsodv;
        //add to request for validation
        $request->request->add(['treeview_jump' => $treeview_jump]);

        
        //validate
        $request->validate([
            'treeview_jump' => 'nullable|string',
            //'user_allowed_wsodv' => 'nullable',
            /* 'name' => 'nullable|string', */
            /* 'service_auth_token' => 'nullable|string', */
            /* 'vre_URL' => 'nullable|string', */
        ]);

        Log::info("hallo2");
        
        if ($request->session()->has('user_allowed_wsodv')){
            $user_allowed_wsodv = session('user_allowed_wsodv');
        } else {
            $user_allowed_wsodv = true;
        }
        Log::info("user_allowed_wsodv:".$user_allowed_wsodv);


        
        //cut out path to treeview item
        $treeview_jump = str_replace('>','/',$treeview_jump);
        //$treeview_jump = str_replace('EMODnet/','',$treeview_jump);
        //Log::info("treeview_jump = ".$treeview_jump);
        //Log::info("username = ".$request->username);

        

        /* $webodv_settings_path = config('webodv.settings_path') . '/' . config('webodv.path_to_odv_settings'); */
        /* $webodv_settings = file_get_contents($webodv_settings_path.'/webodv_settings.json'); */
        /* $webodv_settings = json_decode($webodv_settings,true); */
        //Log::info(print_r($webodv_settings,1));        
        //-------------- SETTINGS ------------------------------//
        

        
        

        
        //create treeview from folder in storage_path('app')
        //Log::info($Path_to_odv_data);
        //$Path_to_odv_data = config('webodv.path_to_odv_data');
        //read now from settings
        //if vre then, session private workspace exists
        $Path_to_odv_data = $webodv_settings['path_to_odv_data'];

        if ($request->session()->has("private_workspace")){
            $private_workspace = '/'.session("private_workspace");
        } else {
            $private_workspace = "";
        }

        //now via ajax
        //Log::info("Path_to_odv_data=" . $Path_to_odv_data . $private_workspace);
        /* $create_treeview_from_folder = new create_treeview_from_folder(); */
        /* if (Auth::check()){ */
        /*     Log::info("Pathxx"); */
        /*     Log::info($Path_to_odv_data . $private_workspace); */
        /*     $treeview_data = ['user' => Auth::user()->name, 'path' => $Path_to_odv_data . $private_workspace, 'fileselector_filter' => $fileselector_filter, 'data-boldParents' => 'data-boldParents="true"']; */
        /* } else { */
        /*     $treeview_data = ['user' => "dummy", 'path' => $Path_to_odv_data . $private_workspace, 'fileselector_filter' => $fileselector_filter, 'data-boldParents' => 'data-boldParents="true"']; */
        /* } */
        /* $treeview = $create_treeview_from_folder->create($treeview_data); */
        //Log::info($treeview);

        

        $workspace = true;
        //push to JS
        echo '<script>var workspace = true;</script>';
            
        session(['workspace' => $workspace]);
        $continue_url = '<script>var continue_url = "'.url($webodv_settings["continue_url"]).'";</script>';
        echo $continue_url;
        echo '<script>var EMODnet = '. json_encode($EMODnet) .';</script>';

        echo '<script>var treeview_jump = "'. $treeview_jump .'";</script>';

        echo '<script>var project = "'. $project .'";</script>';

        if ($user_allowed_wsodv){
            echo '<script>var user_allowed_wsodv = true;</script>';
        } else {
            echo '<script>var user_allowed_wsodv = false;</script>';
        }

        //get user
        if (Auth::check()){
            $username = Auth::user()->name;
            //get private workspaces if auth and put into session
            $priv_workspaces = Auth::user()->private_workspace;
            $priv_workspaces = json_decode($priv_workspaces);
            //Log::info(print_r($priv_workspaces,1));
            $number_of_pages = 1;
            Log::info("priv_workspaces");
            Log::info($priv_workspaces);
            if (!empty($priv_workspaces)){
                foreach ($priv_workspaces as $item){
                    Log::info(print_r($item,1));
                    $number_of_pages = $number_of_pages + 1;
                    //create folders
                    if (!file_exists(storage_path('app').'/'.$item->path)){
                        mkdir(storage_path('app').'/'.$item->path);
                        $msg = 'sudo -u root chgrp 1000 ' . storage_path('app').'/'.$item->path;
                        shell_exec($msg);
                        $msg = 'sudo -u root chmod 775 ' . storage_path('app').'/'.$item->path;
                        shell_exec($msg);
                    }
                }            
                session(['priv_workspaces' => $priv_workspaces]);
            }
        } else {
            $username = '';
            $priv_workspaces = '';
            $number_of_pages = 1;
        }
        $number_of_pages_id = $number_of_pages + 1;

        echo '<script>var username = "'. $username .'";</script>';

        echo '<script>var number_of_pages = "'. $number_of_pages .'";</script>';

        echo '<script>var index_page = "TRUE";</script>';

        //echo '<script>var http_port="' . $webodv_settings['http_port'] . '";</script>';
        echo '<script>var http_port="' . session('http_port') . '";</script>';
        
        $treeview_search = $webodv_settings["treeview_search"];

        echo '<script>var create_treeview_from_folder_ajax_url="' . route('create_treeview_from_folder_ajax') . '";</script>';


        //add mode: dev or prod        
        
        echo '<script>var hummingbird_treeview_js_path="' . asset('js/webodv/hummingbird-treeview'.$modestr.'.js') . '";</script>';

        echo '<script>var hummingbird_treeview_options_path="' . asset('js/webodv/webodvcore/hummingbird_treeview_options'.$modestr.'.js') . '";</script>';
        

        if (isset($webodv_settings["odv_data_header_first"])){
            $odv_data_header_first = $webodv_settings["odv_data_header_first"];
        } else {
            $odv_data_header_first = "";
        }

        //create header links
        $create_header_links = new create_header_links();
        $create_header_links_data = ['session_varname' => 'dataset_link', 'odv_data_header' => '', 'baseurl' => url($webodv_settings['base_url']), 'odv_data_header_first' => $odv_data_header_first];
        $create_header_links_out = $create_header_links->create($create_header_links_data);


        $request->session()->forget('sextantURL');

        //disable delete in treeview
        if ($webodv_settings['project'] != 'webODV-AWI'){
            $disable_treeview_delete = 1;
        } else {
            $disable_treeview_delete = 0;
        }

        echo '<script>var disable_treeview_delete = "'. $disable_treeview_delete .'";</script>';


        return view('webodv.webodvcore.index',compact('workspace','upload','treeview_search','project','index_add_text','allowed_wsodv_instances','priv_workspaces','number_of_pages_id'));
    }


    public function tracking(Request $request) {

        //Log::info("now track");
        
        //-------visit_tracking-------//
        $visit_tracking = $this->track_visitors($request);
        //Log::info("visit_tracking : " . print_r($visit_tracking,1));
        //-------visit_tracking-------//
        $login_tracking = $this->track_logins($request);

        return json_encode("tracking_success");
        
    }


    
    private function check_cache($name,$fextension,$Xarray){
        //----usage-----
        //$name is the name of the file in storage_path('app/')
        //$fextension is the extension of the file e.g. .json or .html
        //$Xarray is boolean, true to convert the data into an array and false to leave it as string
        //
        //check cache if data exist
        if (Cache::has($name)) {
            $data = Cache::get($name);
        } else {
            $data = file_get_contents(storage_path('app/'.$name.$fextension));
            //convert to array
            if ($Xarray){
                $data = json_decode($data,true);
            }
            //put into cache
            Cache::put($name,$data);
        }
        return $data;
    }
    

    public function service(Request $request)
    {

        Log::info($request->datasetname);
        //get URL parameter datasetname
        $datasetname = $request->datasetname;
        //add to request for validation
        $request->request->add(['datasetname' => $datasetname]);

        //validate
        $request->validate([
            'datasetname' => 'required|string',
        ]);



        
        
        //load settings
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);

        $subtitle = $webodv_settings["geotraces_subtitle"];
        $subtitle = str_replace("this_url",url('/'),$subtitle);
        session(['subtitle' => $subtitle]);
        

        
        /* $webodv_settings = file_get_contents(storage_path('app/webodv_settings.json')); */
        /* $webodv_settings = json_decode($webodv_settings,true); */
        
        //default json settings
        $default_dataset_json = $webodv_settings["default_dataset_json"];

        $branch = $this->getBranch();
        /* $branch_array = explode('/',url()->current()); */
        /* $branch = $branch_array[3]; */

        /* $private_workspace = ''; */
        /* //vre */
        /* if ($branch == "private"){ */
        /*     $private_workspace = Auth::user()->private_workspace; */
        /* } */

        //-------------- SETTINGS ------------------------------//
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);
        $project = $webodv_settings["project"];
        /* if ($project == "webODV-AWI"){ */
        /*     $private_workspace = '/' . Auth::user()->email . '/webodv'; */
        /* } */


        ///////////////////uui
        if ($project == "EMODnet Chemistry"){
            $catalog_url = 'https://www.emodnet-chemistry.eu/products/catalogue#/metadata/';
            //get uuid
            $uuid = file_get_contents(public_path('uuid_test.json'));
            $uuid = json_decode($uuid,true);
            /* Log::info("uuid"); */
            Log::info(print_r($uuid,1));

            $datasetname_explode = explode('>',$datasetname);
            $dataset_basename = end($datasetname_explode);
            Log::info(print_r($datasetname_explode,1));

            if (isset($uuid[ $datasetname_explode[0] ][ $datasetname_explode[1] ])){
                Log::info("is set");
                //Log::info(print_r($uuid[$dataset_basename],1));
                $sextantURL = $catalog_url . $uuid[ $datasetname_explode[0] ][ $datasetname_explode[1] ];
                //Log::info($sextantURL);
                session(['sextantURL' => $sextantURL]);
            } else {
                Log::info("is not set");
            }
        }


        ////////////////////

        
        if ($request->session()->has("private_workspace")){
            $private_workspace = '/'.session("private_workspace");
        } else {
            $private_workspace = "";
        }

        //check if a json config file for that dataset exist
        $webodv_settings_path = config('webodv.settings_path') . '/' . config('webodv.path_to_odv_settings');
        $json_path = $branch.$private_workspace;
        if ($request->session()->has('priv_workspace_current_path')){
            $priv_workspace_current_path = session('priv_workspace_current_path');
            if (!empty($priv_workspace_current_path)){
                $json_path = $priv_workspace_current_path;
            }
        }
            
        /* Log::info("json_path"); */
        /* Log::info($json_path); */
        /* Log::info(session('priv_workspace_current_path')); */
        /* die(); */

        Log::info($webodv_settings_path.'/JSON/'. $json_path . '/' . str_replace('>','_',$request->datasetname).'.json');

        if ( !file_exists($webodv_settings_path.'/JSON/'. $json_path . '/' . str_replace('>','_',$request->datasetname).'.json') ){
            Log::info("json config for this file exists not");
            Log::info("create json config");
            //create and write a json config file from the default
            $create_json_config = new create_json_config();
            $create_json_config_data = ['default_dataset_json' => $default_dataset_json, 'datasetname' => $request->datasetname, 'branch' => '', 'private_workspace' => $private_workspace, 'priv_workspace_current_path' => session('priv_workspace_current_path')];
            $create_json_config_out = $create_json_config->create($create_json_config_data);
            //
        } else {
            Log::info("json config for this file exists");
        }




        
        //read json config file
        $webodv_load_settings = new webodv_load_settings();
        $odv_data = $webodv_load_settings->load_odv(['datasetname' => $request->datasetname, 'private_workspace' => $private_workspace, 'priv_workspace_current_path' => session('priv_workspace_current_path')]);
        /* $odv_data = file_get_contents(storage_path('app/JSON/'.str_replace('>','_',$request->datasetname).'.json')); */
        /* $odv_data = json_decode($odv_data,true); */

        if (isset($webodv_settings["odv_data_header_first"])){
            $odv_data_header_first = $webodv_settings["odv_data_header_first"];
        } else {
            $odv_data_header_first = "";
        }


        //create header links
        $create_header_links = new create_header_links();
        $create_header_links_data = ['session_varname' => 'dataset_link', 'odv_data_header' => $odv_data['header'], 'baseurl' => url($webodv_settings['base_url']), 'odv_data_header_first' => $odv_data_header_first];
        $create_header_links_out = $create_header_links->create($create_header_links_data);
        
        //$Xpath = explode('>',$odv_data['header']);
        //session(['webODV_link' => url('/webodv')]); //.'/'.$Xpath[0]]);

        //Log::info("dataset_link in WebodvcoreController@service = ".session('dataset_link'));
        
        //Log::info(route("wsodv_init", ['data' => $odv_data['datasetname'], 'service' => preg_replace('/\s+/','',$odv_data['services'][$key])]));
        //Log::info(route("wsodv_init", ['datasetname' => $odv_data['datasetname'], 'servicename' => 'test']));

        $branch = $this->getBranch();
        //$branch_array = explode('/',url()->current());
        //Log::info("service_branch");
        //Log::info(print_r($branch_array,1));
        //$branch = $branch_array[3];

        
        $dataset = explode('>',$odv_data['header']);
        $dataset_heading = end($dataset);

        //get user
        if (Auth::check()){
            $username = Auth::user()->name;
        } else {
            $username = '';
        }

        echo '<script>var username = "'. $username .'";</script>';

        
        echo '<script>var http_port="' . session('http_port') . '";</script>';



        
        return view('webodv.webodvcore.service',compact('odv_data','dataset_heading','branch'));
    }


    

    public function wsodv_init(Request $request)
    {

        //Log::info("wsodv_init");
        //get URL parameter datasetname
        $datasetname = $request->datasetname;
        //add to request for validation
        $request->request->add(['datasetname' => $datasetname]);

        //get URL parameter datasetname
        $servicename = $request->servicename;
        //add to request for validation
        $request->request->add(['servicename' => $servicename]);

        /* Log::info("servicename"); */
        /* Log::info($servicename); */
        /* die(); */

        //validate request
        $request->validate([
            'datasetname' => 'nullable|string',
            'servicename' => 'nullable|string',
        ]);


        $tmpx = explode('Data',$request->servicename);
        //Log::info(print_r($tmpx,1));
        //this is the key to the JSON settings file
        //entry services, which cointains disable_exports and auto_shutdown
        $servicename_x = 'Data '.$tmpx[1];


        $branch = $this->getBranch();
        //$branch_array = explode('/',url()->current());
        //Log::info("service_branch");
        //Log::info(print_r($branch_array,1));
        //$branch = $branch_array[3];

        /* $private_workspace = ''; */
        /* //vre */
        /* if ($branch == "private"){ */
        /*     $private_workspace = Auth::user()->private_workspace; */
        /* } */

        //-------------- SETTINGS ------------------------------//
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);
        $project = $webodv_settings["project"];
        /* if ($project == "webODV-AWI"){ */
        /*     $private_workspace = '/' . Auth::user()->email . '/webodv'; */
        /* } */

        

        //do this again to overjump the service page in case
        //load settings
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);

        //Log::info(print_r($webodv_settings,1));
        //die();
        
        if (isset($webodv_settings["geotraces_subtitle"])){        
            $subtitle = $webodv_settings["geotraces_subtitle"];
            $subtitle = str_replace("this_url",url('/'),$subtitle);
            session(['subtitle' => $subtitle]);
        } else {
            session(['subtitle' => ""]);
        }



/* $webodv_settings = file_get_contents(storage_path('app/webodv_settings.json')); */
        /* $webodv_settings = json_decode($webodv_settings,true); */
        
        //default json settings
        $default_dataset_json = $webodv_settings["default_dataset_json"];

        //check if a json config file for that dataset exist
        $webodv_settings_path = config('webodv.settings_path') . '/' . config('webodv.path_to_odv_settings');

        if ($request->session()->has("private_workspace")){
            $private_workspace = '/'.session("private_workspace");
        } else {
            $private_workspace = "";
        }

        
        $json_path = $branch.$private_workspace;
        if ( !file_exists($webodv_settings_path.'/JSON/'. $json_path . '/' . str_replace('>','_',$request->datasetname).'.json') ){
            Log::info("json config for this file exists not");
            Log::info("create json config");
            //create and write a json config file from the default
            $create_json_config = new create_json_config();
            $create_json_config_data = ['default_dataset_json' => $default_dataset_json, 'datasetname' => $request->datasetname, 'branch' => '', 'private_workspace' => $private_workspace, 'priv_workspace_current_path' => session('priv_workspace_current_path')];
            $create_json_config_out = $create_json_config->create($create_json_config_data);
            //
        } else {
            Log::info("json config for this file exists");
        }
        //


        
        //read json config file
        $webodv_load_settings = new webodv_load_settings();
        $odv_data = $webodv_load_settings->load_odv(['datasetname' => $request->datasetname, 'private_workspace' => $private_workspace, 'priv_workspace_current_path' => session('priv_workspace_current_path')]);

        /* $odv_data = file_get_contents(storage_path('app/JSON/'.str_replace('>','_',$request->datasetname).'.json')); */
        /* $odv_data = json_decode($odv_data,true); */


        //Log::info(print_r($odv_data,1));
        //die();


        //create header links
        $create_header_links = new create_header_links();
        $create_header_links_data = ['session_varname' => 'dataset_link', 'odv_data_header' => $odv_data['header'], 'baseurl' => url($webodv_settings['base_url'])];
        $create_header_links_out = $create_header_links->create($create_header_links_data);


        //this is the number of allowed_wsodv_instances
        //used to prevent for DOS attacks and
        //to limit ressources per user
        $allowed_wsodv_instances = $webodv_settings["allowed_wsodv_instances"];

        //get user
        if (Auth::check()){
            $user = Auth::user()->email;
        } else {
            #$user = 'anonymous@webodv';
            #$user = $request->ip();
            #get ip behind proxy
            $user = $_SERVER["HTTP_X_FORWARDED_FOR"];  
        }
        $user = str_replace('@','.at.',$user);


        //run the cleaner to shut down instances and remove db entries
        //what happens if there is a long running job
        //within the cleaner I have a WebSocket time out of 4s
        //thus in the case there is long running job
        //it will wait 4 s?
        //
        //
        $cleaner = new wsODV_manager();
        //
        //if it is the explorer on private data, which is operated in RW mode
        //kill all existing odvws of that user with that dataset to asure that the new instance is running in RW
        if ($servicename == "DataExploration"){
            if ($odv_data['permission'] == "ReadWrite"){
                $cleaner->kill($odv_data['path']);
            }
        }
        //
        //run cleaner now
        $cleaner->clean($user);
        
        //check if user is allowed to open a new wsODV
        $current_wsodv_instances = DB::connection('wsodv-manager')->table('wsodv_used')->where('user', '=', $user)->get();
        //Log::info(print_r($current_wsodv_instances,1));
        //Log::info("current_wsodv_instances: ". count($current_wsodv_instances));

        //check for downloads
        //in the download controller

        
        //only start wsODV if user is allowed to 
        if (count($current_wsodv_instances) < $allowed_wsodv_instances){
            $user_allowed_wsodv = true;
            //start or connect odv instance
            //decide how to start, single, multi, read, read-write
            $mode = 'single';
            $permanent = false;
            $readwrite = $odv_data['permission']; //ReadWrite ReadOnly
            if ($webodv_settings["virtual_screen"]){
                $virtual_display = 'enable'; //enable disable
            } else {
                $virtual_display = 'disable'; //enable disable
            }
            //get free port / proxy
            $wsodv_available = Wsodv_available::first();
            //delete db available
            $wsodv_available->delete();


            if ($project == 'webODV-AWI'){
                $start_wsodv_user = Auth::user()->name;
            } else {
                $start_wsodv_user = $webodv_settings["user"];
            }


            
            
            //Log::info(print_r($wsodv_available,1));
            //create wsODV_manager object
            $webodv_settings_path = config('webodv.settings_path') . '/' . config('webodv.path_to_odv_settings');
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
                    'readwrite' => $readwrite,
                    'port' => $wsodv_available->port,
                    'url' => $wsodv_available->url,
                    'auto_shutdown' => $odv_data["services"][$servicename_x]["auto_shutdown"],
                    'disable_exports' => $odv_data["services"][$servicename_x]["disable_exports"],
                    'admin_password' => $webodv_settings['wsodv_secret'],
                ]);
            Log::info("start_wsodv_answer=".$answer);
            //delete db available
            //$wsodv_available->delete();
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
                    'mode' => $mode,
                    'permanent' => $permanent,
                    'server' => false,
                    'odv_file_path' => $odv_data['path'],
                ]);


            //Log::info($answer);
            session(['js_dump' => $answer]);
            //decide here to send user to a specific service
            //$service = $odv_data[$file_num]['path'],

            Log::info("servicename= " . $servicename);

            //set this session var to indicate that a wsODV has been started
            //flash the session var only for the next http request
            $request->session()->flash('wsODV_started', true);

            $branch = $this->getBranch();
            //$branch_array = explode('/',url()->current());
            //Log::info("service_branch");
            //Log::info(print_r($branch_array,1));
            //$branch = $branch_array[3];
            

            if ($servicename == 'DataExploration'){
                //session(['service' => 'DataExploration']); //this is needed to load js in webodv_layout.blade.php
                //return view('webodv.odvonline.odvonline_init');
                //return redirect()->route('odvonline_'.$branch, ['datasetname' => $datasetname]);
                return redirect()->route('odvonline_init', ['datasetname' => $datasetname]);
            }
            if ($servicename == 'DataExtraction'){            
                //return redirect('/webodvextractor'."?f);
                return redirect()->route('webodvextractor', ['datasetname' => $datasetname]);
            }
            
        } else {
            $user_allowed_wsodv = false;
            //session flash !!!!!!!!!!!!!!!!!!!!!!!
            $request->session()->flash('user_allowed_wsodv', $user_allowed_wsodv);
            return redirect()->route('webodv', ['treeview_jump' =>  'base']);
        }

        
    }





    public function data_upload(Request $request)
    {
        Log::info($request->all());


        //validate
        $request->validate([
            'fileToUpload' => 'required',
            'filePaths' => 'nullable'
        ]);

        Log::info("validate ok");

        //-------------- SETTINGS ------------------------------//
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);


        $Path_to_odv_data = $webodv_settings['path_to_odv_data'];

        if ($request->session()->has("private_workspace")){
            $private_workspace = '/'.session("private_workspace");
        } else {
            $private_workspace = "";
        }

        if ($request->session()->has('priv_workspace_current_path')){
            $ODV_path = session('priv_workspace_current_path');
        } else {
            $ODV_path = $Path_to_odv_data . $private_workspace;
            Log::info($ODV_path);
        }

        //upload folder or file
        $top_folder = "";
        if (isset($request->filePaths[0])){
            Log::info("filePath isset");
            Log::info($request->filePaths[0]);
            $m = 0;
            foreach ($request->fileToUpload as $file){
                //Log::info($file->getClientOriginalName());
                Log::info(print_r($request->filePaths[$m],1));
                //Log::info(print_r($file->getClientOriginalName(),1));
                
                $dirname = pathinfo($request->filePaths[$m], PATHINFO_DIRNAME);
                Log::info(print_r($dirname,1));
                if ($top_folder == ""){
                    $top_folder = $dirname;
                }
                
                //Log::info(print_r($file,1));
                //$path = $request->fileToUpload->storeAs($ODV_path,$file->getClientOriginalName());
                //save locally
                //$path = $file->storeAs('/local/' . $ODV_path.'/'.$dirname,$file->getClientOriginalName());
                $path = $file->storeAs('/local/' . $ODV_path.'/'.$dirname,$file->getClientOriginalName());
                $m++;
            }
        } else {
            //Log::info($request->fileToUpload[0]->getClientOriginalName());
            //create dummy folder
            $dummy_folder = Str::random(8);
            $rel_path = '/local/' . $ODV_path . '/' .$dummy_folder;
            //session(['rel_path' => $rel_path]);
            $zip_file = $request->fileToUpload[0]->getClientOriginalName();
            Log::info("store file now");
            $path = $request->fileToUpload[0]->storeAs($rel_path,$zip_file);
            //unzip
            $zip = new ZipArchive;
            if ($zip->open(storage_path('app').$rel_path.'/'.$zip_file) === TRUE) {
                $zip->extractTo(storage_path('app').$rel_path);
                $zip->close();
                Log::info('ok');
                //remove .zip
                Storage::delete($rel_path.'/'.$zip_file);
            } else {
                Log::info('failed');
            }
            
        }
        //Log::info($path);
        
        //sleep(2);

        //Log::info("top_folder");
        //Log::info($top_folder);

        //folder must exist
        //AWI
        if ($webodv_settings['project'] == 'webODV-AWI'){
            $msg = 'sudo -u ' . Auth::user()->name . ' cp -r ' . storage_path('app').$rel_path  . '/* ' . storage_path('app/') . $ODV_path . '/.';
        }
        //EMODnet
        if ($webodv_settings['project'] == 'EMODnet Chemistry'){
            $msg = 'sudo -u woody cp -r ' . storage_path('app').$rel_path  . '/* ' . storage_path('app/') . $ODV_path . '/.';
        }
        

        Log::info($msg);
        shell_exec($msg);

        //delete dir
        Storage::deleteDirectory($rel_path);

        
        //return $path;
        
        //return redirect()->action('masterController@xviews');
        return json_encode("true");
    }

    public function delete_collection(Request $request){

        $request->validate([
            'filepathname' => 'required',
        ]);

        //-------------- SETTINGS ------------------------------//
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);


        if ($request->session()->has('priv_workspace_current_path')){
            $Path_to_odv_data = session('priv_workspace_current_path');
        } else {
            $Path_to_odv_data = $webodv_settings['path_to_odv_data'];
        }

        

        if ($request->session()->has("private_workspace")){
            $private_workspace = '/'.session("private_workspace");
        } else {
            $private_workspace = "";
        }
        
        
        $ODV_path = $Path_to_odv_data . $private_workspace;

        $delete_path = storage_path('app/') . $ODV_path . '/' . pathinfo($request->filepathname,PATHINFO_DIRNAME);
        $delete_file = basename($request->filepathname);
        $delete_folder = str_replace('.odv','.Data',$delete_file);

        //delete
        if ($webodv_settings['project'] == 'EMODnet Chemistry'){
            $msg = 'sudo -u woody rm -r ' . $delete_path .'/'. $delete_file . ' ' . $delete_path .'/'. $delete_folder;
        }

        if ($webodv_settings['project'] == 'webODV-AWI'){
            $msg = 'sudo -u ' . Auth::user()->name . ' rm -r ' . $delete_path .'/'. $delete_file . ' ' . $delete_path .'/'. $delete_folder;
        }

        Log::info($msg);
        shell_exec($msg);

        
        
        /* $msg = 'sudo -u ' . Auth::user()->name . ' find ' . storage_path('app/') . $ODV_path . ' -name "' . $request->filename . '"' . ' -exec rm {} \;'; */
        /* Log::info($msg); */
        /* shell_exec($msg); */

        /* $folder = str_replace('.odv','.Data',$request->filename); */
        /* $msg = 'sudo -u ' . Auth::user()->name . ' find ' . storage_path('app/') . $ODV_path . ' -name "' . $folder . '"' . ' -exec rm -r {} \;'; */
        /* Log::info($msg); */
        /* shell_exec($msg); */
        

        
        return json_encode("success");
    }


    public function download_collection(Request $request){

        $request->validate([
            'filepathname' => 'required',
        ]);

        //-------------- SETTINGS ------------------------------//
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);


        if ($request->session()->has('priv_workspace_current_path')){
            $Path_to_odv_data = session('priv_workspace_current_path');
        } else {
            $Path_to_odv_data = $webodv_settings['path_to_odv_data'];
        }


        if ($request->session()->has("private_workspace")){
            $private_workspace = '/'.session("private_workspace");
        } else {
            $private_workspace = "";
        }

        $ODV_path = $Path_to_odv_data . $private_workspace;

        /* $dummy_folder = Str::random(8); */
        /* $rel_path = '/local/' . $ODV_path . '/' .$dummy_folder; */

        /* chmod(storage_path('app/local/').$ODV_path,0777); */

        
        /* $msg = 'sudo -u ' . Auth::user()->name . ' mkdir -p ' . storage_path('app').$rel_path; */
        /* Log::info($msg); */
        /* shell_exec($msg); */
        
        /* $msg = 'sudo -u ' . Auth::user()->name . ' find ' . storage_path('app/') . $ODV_path . ' -name "' . $request->filename . '"'; // . ' -exec ls {} \;'; */
        /* Log::info($msg); */
        /* $download_file_path = shell_exec($msg); */
        /* Log::info($download_file_path); */

        $download_path = storage_path('app/') . $ODV_path . '/' . pathinfo($request->filepathname,PATHINFO_DIRNAME);
        $download_file = basename($request->filepathname);
        $download_folder = str_replace('.odv','.Data',$download_file);

        
        //$folder = str_replace('.odv','.Data',$request->filename);
        /* $msg = 'sudo -u ' . Auth::user()->name . ' find ' . storage_path('app/') . $ODV_path . ' -type d -name "' . $folder . '"' ; //. ' -exec ls {} \;'; */
        /* Log::info($msg); */
        /* $download_folder_path = shell_exec($msg); */
        /* Log::info($download_folder_path); */

        //set user for this action
        if ($webodv_settings['project'] == 'webODV-AWI'){
            $download_user = Auth::user()->name;
        } else {
            $download_user = "woody";
        }

        
        //create folder in local
        $xname = Str::random(8);
        mkdir(storage_path('app/local/').$xname);
        chmod(storage_path('app/local/').$xname,0777);
        //copy files
        $msg = 'sudo -u ' . $download_user . ' cp -r ' . $download_path .'/'. $download_file . ' ' . $download_path .'/'. $download_folder . ' ' . storage_path('app/local/').$xname.'/.';        Log::info($msg);
        shell_exec($msg);

        //and make readable
        $msg = 'sudo -u ' . $download_user . ' chmod -R 775 ' . storage_path('app/local/').$xname.'/';
        Log::info($msg);
        shell_exec($msg);

        
        //zip
        $zip_file_name = str_replace('.odv','_'.$xname.'.zip',$download_file);
        $msg = ' cd ' . storage_path('app/local/').$xname   . '; zip -r ' . public_path('downloads/').$zip_file_name . ' ' . $download_file . ' ' . $download_folder;
        Log::info($msg);
        shell_exec($msg);

        //delete
        $msg = 'cd ' . storage_path('app/local/').$xname   . '; sudo -u ' . $download_user . ' rm -r ' . $download_file . ' ' . $download_folder;
        Log::info($msg);
        shell_exec($msg);        
        Log::info($xname);
        rmdir(storage_path('app/local/').$xname);
        
        return json_encode(['url' => url('downloads').'/'.$zip_file_name]);
    }


    
    public function stats(Request $request){

        //allow only me
        $allow = false;
        $emstr = '_emodnet_chem';
        if (Auth::check()){
            if (Auth::user()->email == "sebastian.mieruch@awi.de" || Auth::user()->email == "sebastian.mieruch@awi.de".$emstr){
                $allow = true;
            }
        }
        if ($allow == false){
            abort(401);            
        }



        echo "<h1>Statistics</h1>";
        //
        //
        $users = DB::table('users')->get();
        echo "<h3>All users:</h3>";
        foreach($users as $user){
            echo $user->name;
            echo ", ";
            echo $user->email;
            echo ", ";
            echo $user->created_at;
            echo "<br>";
        }
        //
        //
        echo "<br>";
        echo "<br>";
        echo "<br>";
        //
        //
        $downloads = DB::table('download_tracking')->get();
        echo "<h3>All downloads:</h3>";
        foreach($downloads as $download){
            echo $download->email;
            echo ", ";
            echo $download->created_at;
            echo ", ";
            $ws_message = json_decode($download->ws_message);
            echo $ws_message->datasetname;
            echo "<br>";
        }
        //
        //
        echo "<br>";
        echo "<br>";
        echo "<br>";
        //
        //
        $visits = DB::table('visit_tracking')->get();
        echo "<h3>All daily visitors:</h3>";
        foreach($visits as $visit){
            echo $visit->id;
            echo ", ";
            echo $visit->country;
            echo ", ";
            echo $visit->created_at;
            echo "<br>";
        }
        //
        //
        echo "<br>";
        echo "<br>";
        echo "<br>";
        //
        //
        $logins = DB::table('login_tracking')->get();
        echo "<h3>All daily logins:</h3>";
        foreach($logins as $login){
            echo $login->id;
            echo ", ";
            echo $login->email;
            echo ", ";
            echo $login->country;
            echo ", ";
            echo $login->created_at;
            echo "<br>";
        }
        //
        //
        echo "<br>";
        echo "<br>";
        echo "<br>";
        //
        //
        $odvwss = DB::connection('wsodv-manager')->table('wsodv_used')->get();
        echo "<h3>Active Extractor instances:</h3>";
        foreach($odvwss as $odvws){
            if (preg_match('/download/',$odvws->user) == false){
                echo $odvws->user;
                echo ", ";
                echo $odvws->created_at;
                echo "<br>";
            }
        }

        //
        //
        echo "<br>";
        echo "<br>";
        echo "<br>";
        //
        //
        echo "<h3>Active Downloads:</h3>";
        foreach($odvwss as $odvws){
            if (preg_match('/download/',$odvws->user) == true){
                echo $odvws->user;
                echo ", ";
                echo $odvws->created_at;
                echo "<br>";
            }
        }
        
    }


    public function geotraces_download_stats(Request $request){

        Log::info("geotraces_download_stats");

        if (Auth::check()){
            $user = Auth::user();
            Log::info($user->email);
            if ($user->email == 'sebastian.mieruch@awi.de' || $user->email == 'baggi@gmx.li'){
                $dummy = true;
            } else {
                abort(401);
            }
        } else {
                abort(401);
        }



        //create new database e.g. name it webodv
        //import awi_geotraces_users.sql, awi_download_tracking.sql, awi_userTracking.sql

        //array with emails
        $email_array = [];
        
        //get userTracking emails
        $userTracking = DB::connection('webodv')->table('userTracking')->get();
        foreach ($userTracking as $item){
            /* echo $item->email; */
            /* echo "<br>"; */
            if (!isset($email_array[$item->email])){
                $email_array[$item->email] = true;
                /* echo $item->email; */
                /* echo "<br>"; */
            }
        }

        //get download_tracking
        $download_tracking = DB::connection('webodv')->table('download_tracking')->where('service','GEOTRACES')->get();
        foreach ($download_tracking as $item){
            /* echo $item->email; */
            /* echo "<br>"; */
            if (!isset($email_array[$item->email])){
                $email_array[$item->email] = true;
                /* echo $item->email; */
                /* echo "<br>"; */
            }
        }


        $L = count($email_array);
        //echo "count: " . $L;

        //get user info
        $m = 1;
        $info_array = [];
        echo "# number, institution, street, zipcode, city, country";
        echo "<br>";
        foreach ($email_array as $key => $item){
            //echo $key;
            $user_info = DB::connection('webodv')->table('users')->where('email',$key)->get();
            //echo print_r($user_info,1);

            if (isset($user_info[0])){
                if (!isset($info_array[$user_info[0]->institution])){
                    $info_array[$user_info[0]->institution] = true;
                    echo $m . ", " . str_replace(',',' ',$user_info[0]->institution) . ", " . str_replace(',',' ',$user_info[0]->street) . ", " . str_replace(',',' ',$user_info[0]->zipcode) . ", " . str_replace(',',' ',$user_info[0]->city) . ", " . str_replace(',',' ',$user_info[0]->country);
                    echo "<br>";
                    $m = $m+1;
                }
            }
        }

        

        return json_encode("success");


    }



    public function add_project_get(Request $request){


        $user = Auth::user();
        //get private workspace db
        $priv_workspaces = DB::table('private_workspaces')->where('email', '=', $user->email)->get();
        
        echo '<script>var remove_project_url="' . route('remove_project') . '";</script>';
        
        return view('webodv.webodvcore.add_project',compact('priv_workspaces'));
    }


    public function remove_project(Request $request){

        //validate
        $request->validate([
            'name' => 'required',
        ]);

        $user = Auth::user();
        //get private workspace db
        DB::table('private_workspaces')->where('email', '=', $user->email)->where('name', '=', $request->name)->delete();
        

        
        return json_encode(["add_project_url" => route('add_project_get')]);
    }

    public function add_project_post(Request $request){

        //validate
        $request->validate([
            'add_project_input' => 'required',
        ]);


        
        $user = Auth::user();
        //add
        $add_project = new private_workspaces;
        $add_project->email = $user->email;
        $add_project->name = 'projects/'.$request->add_project_input;
        $add_project->save();

        $priv_workspaces = DB::table('private_workspaces')->where('email', '=', $user->email)->get();

        echo '<script>var remove_project_url="' . route('remove_project') . '";</script>';
        
        return view('webodv.webodvcore.add_project',compact('priv_workspaces'));
    }

    

    public function contact(Request $request){

        //Log::info("contact");


        //validate input
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'message' => 'required',
        ]);            

            //error_log("here here 2");
        
        $xmail = config('mail.from.address');
        Log::info("xmail=".$xmail);
        
        //send mail
            Mail::to($xmail)->send(new contactMail($request));
        //

        return json_encode("contact_success");

    }

    public function profile(Request $request){

        Log::info("profile");

        //get user
        $user = Auth::user();

        //get email
        $email = $user->email;

        //Log::info(print_r($request->all(),1));
        
        //validate
        $this->validate($request, [
            'institution' => 'required|max:255',
            'street' => 'required|max:255',
            'city' => 'required|max:255',
            'country' => 'required|max:255',
            'zipcode' => 'required|max:255',
        ]);


        

        //update data base
        $thisuser = User::where([
            ['email', $email],
        ])->update([
            'institution' => $request['institution'],
            'street' => $request['street'],
            'city' => $request['city'],
            'country' => $request['country'],
            'zipcode' => $request['zipcode'],
        ]);

        //sleep(5);

        //error_log(print_r($request->all(),1));


        //replace request->email by $email
        //to guarantee that the correct email is pushed back to JS
        //if a hacker has tried to change email
        //which is actually not possible, but who knows
        $request->email = $email;
        
        //echo json_encode("SUCCESS");
        return json_encode($request->all());

    }


    public function deleteaccount(Request $request){

        //get user
        $user = Auth::user();

        //get email
        $email = $user->email;

        //Log::info(print_r($request->all(),1));
        
        //validate
        $this->validate($request, [
            'password' => 'required|max:255',
        ]);

        //check passwd
        if (Hash::check($request->password, $user->password)) {
            //update data base
            $thisuser = User::where([
                ['email', $email],
            ])->update([
                'email' => $user->email . '_deleted_' . Str::random(8),
                'ip' => '',
            ]);
            //Log out user
            Auth::logout();
            //
            return json_encode("true");
        } else {
            return json_encode("false");
        }
    }


    public function announcements(Request $request){

        //get user
        $user = Auth::user();
        
        //get email
        $email = $user->email;

        //check if system is in production
        if ($request->session()->has('modestr')){
            if (session('modestr') == 'prod'){
                //check role
                if ($user->role != "admin"){
                    abort(401);
                }
            }
        } else {
            abort(401);
        }

        $intro = "On <b>August 12th</b> we will upgrade the webODV GEOTRACES service. Therefore we have to shutdown the service for a few minutes.";
        $intro = $intro . "The upgrade includes:";
        $intro = $intro . "<ul></ul>";
        $intro = $intro . "<b>Layout update:</b>";
        $intro = $intro . "<ul>";
        $intro = $intro . "<li>Treeview data selection.</li>";
        $intro = $intro . "<li>Easy and fast navigation.</li>";
        $intro = $intro . "<li>New colours.</li>";
        $intro = $intro . "<li>Clear button headers.</li>";
        $intro = $intro . "</ul>";
        $intro = $intro . "<b>New extractor functions:</b>";
        $intro = $intro . "<ul>";
        $intro = $intro . "<li>Station info on click on the map.</li>";
        $intro = $intro . "<li>More zoom functions.</li>";
        $intro = $intro . "<li>Multiple zooming.</li>";
        $intro = $intro . "<li>Date selection.</li>";
        $intro = $intro . "<li>Visualisation step with custom axes settings.</li>";
        $intro = $intro . "<li>Download map, scatterplot.</li>";
        $intro = $intro . "</ul>";
        $intro = $intro . '<b>New service <span style="color:#a94442">Data Exploration</span>:</b>';
        $intro = $intro . "<ul>";
        $intro = $intro . "<li>This service is a nearly 1:1 online implementation of the widely used ODV software (<a href=\"https://odv.awi.de\" target=\"_blank\">https://odv.awi.de</a>).</li>";
        $intro = $intro . "<li>Supports right mouse click and context menus.</li>";
        $intro = $intro . "<li>Interactive exploration, analysis and visualisation.</li>";
        $intro = $intro . "<li>Maps, scatterplots, sections, surfaceplots.</li>";
        $intro = $intro . "<li>Predefined views.</li>";
        $intro = $intro . "<li>And much more.</li>";
        $intro = $intro . "</ul>";
        $intro = $intro . '<span style="color:green;"><b>Please contact us for feedback, questions, problems or bug reporting.</b></span>';

        
        $intro = "The GEOTRACES webODV services will be unavailable on <b>November ".
            "18th</b> between <b>11:30 and 12:30 UTC</b>, because of maintenance and ".
            "upgrade work. We will install a new version of the data extractor ".
            "providing easier dataset selection as well as more flexible zooming ".
            "and station selection. We also add basic visualization of the selected ".
            "data and provide station metadata. In addition, we also install a new ".
            "online data exploration service that lets you analyze and visualize ".
            "GEOTRACES datasets very much like the desktop Ocean Data View (ODV) ".
            "software, but without the need to download the GEOTRACES datasets or ".
            "install the ODV software on your computer.  Please note the new URL of ".
            "the GEOTRACES webODV services at <a href=\"https://geotraces.webodv.awi.de\" target=\"_blank\">https://geotraces.webodv.awi.de</a>.";
        
        $intro = "The GEOTRACES webODV services has been upgraded. We have installed a new version of the data extractor ".
            "providing easier dataset selection as well as more flexible zooming ".
            "and station selection. We also added a scatterplot overview of the selected ".
            "data and provide station metadata. In addition, we also installed a new ".
            "online data exploration service that lets you analyze and visualize ".
            "GEOTRACES datasets very much like the desktop Ocean Data View (ODV) ".
            "software, but without the need to download the GEOTRACES datasets or ".
            "install the ODV software on your computer.  Please note the new URL of ".
            "the GEOTRACES webODV services at <a href=\"https://geotraces.webodv.awi.de\" target=\"_blank\">https://geotraces.webodv.awi.de</a>.";

        //echo $intro;
        
        //$data = (object) ['intro' => $intro, 'url' => "https://webodv.awi.de", 'subject' => 'webODV upgrade', 'action_text' => 'Goto webODV'];            

        //put users emails into bcc array
        $bcc = [];
        
        //send
        $count = 1;
        $when = now();
        $users = DB::table('users')->where('domain','LIKE','%'."geotraces".'%')->get();
        //$users = DB::table('users')->get();
        foreach ($users as $user){
            if (preg_match('/deleted/',$user->email) == false){
                //Log::info("email: ".$user->email);
                //$when = $when->addSeconds(30);
                //Log::info("when: ".$when);
                $bcc[$user->name . "_" . $count] = $user->email;  //$user->email;
                //$bcc[] = $user->email;  //$user->email;
                $count++;
                /* try{ */
                /*     Notification::route('mail', $user->email)->notify((new DownloadFinished($data))->delay($when)); */
                /* } */
                /* catch(ErrorException $e){ */
                /*     Log::info("Notify exception"); */
                /*     Log::info(print_r($e,1)); */
                /* } */
            }
        }

        Log::info("bcc:");
        Log::info(print_r($bcc,1));

        $data = (object) ['intro' => $intro, 'url' => "https://geotraces.webodv.awi.de", 'subject' => 'webODV upgrade', 'action_text' => 'Goto webODV', 'bcc' => $bcc];

        //add delay
        //-------------------!!!!!!---start a worker: php artisan queue:work--------------!!!!!!!!!!!!!!!!!!//
        //$when = now();
        $when = $when->addSeconds(30);
        //now send emails
        //Notification::route('mail', 'sebastian.mieruch@awi.de')->notify((new DownloadFinished($data))->delay($when));
        
        //Notification::route('mail', "sebastian.mieruch@awi.de")->notify((new DownloadFinished($data))->delay($when));
        //Notification::route('mail', "sebastian.mieruch@awi.de")->notify(new DownloadFinished($data));


        //view
        return (new DownloadFinished($data))
                ->toMail("seb@test.de");

        //return json_encode("success");
    }



    public function copy_db(Request $request){


        //check if system is in production
        if ($request->session()->has('modestr')){
            if (session('modestr') == 'prod'){
                //check role
                if ($user->role != "admin"){
                    abort(401);
                }
            }
        } else {
            abort(401);
        }


        //load countries_codes.txt
        $countries_codes = file(public_path('countries_codes.txt'));
        //neww associative array
        $new_countries = [];
        foreach ($countries_codes as $key => $val){
            //Log::info($key . ' : ' . $val);
            //split val at :
            $tmp = explode(':',$val);
            $new_countries[$tmp[0]] = $tmp[1];
            //Log::info($tmp[0] . '=>' . $new_countries[$tmp[0]]);
        }

        // for testing only 10 users
        $m = 1;
        $users_geotraces = DB::table('users-geotraces')->get();
        foreach ($users_geotraces as $entry){
            $new_user = new User;
            $new_user->first_name = $entry->first_name;
            $new_user->last_name = $entry->last_name;
            $new_user->name = $entry->first_name . " " . $entry->last_name;
            //for testing put dummy email address
            //$new_user->email = Str::random(8) . '@' . Str::random(8) . '.xx';  //$entry->email;
            $new_user->email = $entry->email;
            $new_user->password = $entry->password;
            $new_user->institution = $entry->Institution;
            $new_user->street = $entry->Street;
            $new_user->city = $entry->City;
            //old geotraces country was encoded as country code
            if (isset($new_countries[$entry->Country])){
                $new_user->country = $new_countries[$entry->Country];
            } else {
                $new_user->country = "NA";
            }
            $new_user->zipcode = $entry->ZipCode;
            $new_user->agree = 1;
            $new_user->domain = "https://webodv.awi.de/geotraces";
            $new_user->created_at = $entry->created_at;
            $new_user->updated_at = $entry->updated_at;
            $new_user->save();
            //for testing
            if ($m == 10){
                //break;
            }
            $m++;
        }

        return json_encode("success");
        
        
    }



    public function getuserauthinfo(Request $request) {

        //this request comes from explore, thus it is a server to server communication without
        //any client involvement
        //no client can ever see the secret
        //a request without correct secret is blocked
        //
        $this->validate($request, [
            'secret' => ['required','in:'.'Qtz46Wrv'],
            'ip' => ['required', 'string', 'max:255'],
        ]);

        //Log::info(print_r($request->all(),1));
        Log::info("getuserauthinfo");
        
        //get username
        $user = DB::table('users')->where('ip',$request->ip)->first();  //get a single instance from DB
        Log::info(print_r($user,1));
        if (empty($user)){
            return json_encode(false);
        } else {            
            $username = str_replace('@','.at.',$user->email);
            return json_encode($username);            
        }
        
        

    }


    public function apiwmsform(Request $request) {

        session(['service' => 'apiwms']);

        $paras = [
            "LAYERS" => "eutrophication>Arctic>Eutrophication_Arctic_profiles_2021",
            "STYLES" => "default",
            "TRANSPARENCY" => "true",
            "FORMAT" => "image/png",
            "SERVICE" => "WMS",
            "VERSION" => "1.3.0",
            "REQUEST" => "GetMap",
            "ELEVATION" => "0",
            "TIME" => "2000",
            "CRS" => "EPSG:4326",
            "BBOX" => "30.0,0.0,80.0,50.0",
            "Width" => "512",
            "Height" => "512"
        ];

        /* if ($request->has('LAYERS')){ */
        /*     Log::info("has LAYERS !!!"); */
        /*     $wms_url = url()->full(); */
        /*     Log::info($wms_url); */
        /*     echo '<img src="' .  $wms_url  . '" />'; */
        /* } */

        
        return view('webodv.webodvcore.apiwmsform',compact('paras'));
    }


    public function apiwms(Request $request) {

        $create_image = $this->create_image($request);

        //return $create_image;

        
        header('Content-type: image/png');

        echo $create_image;

        //echo '<img src="' .  $create_image  . '" />';
        
        //readfile($create_image);

        //echo '<img src="data:image/png;base64,' .  base64_encode($create_image)  . '" />';
        //echo '<img src="' .  $create_image  . '" />';
        
        //later return only an image
        //return view('webodv.webodvcore.apiwms',compact('create_image'));
    }


    public function create_treeview_from_folder_ajax(Request $request) {


        Log::info("create_treeview_from_folder_ajax");


        $this->validate($request, [
            'pager_num' => ['required'],
        ]);
        Log::info("pager_num");
        Log::info($request->pager_num);

        
        //create treeview from folder in storage_path('app')
        //Log::info($Path_to_odv_data);
        //$Path_to_odv_data = config('webodv.path_to_odv_data');
        //read now from settings
        //if vre then, session private workspace exists
        //-------------- SETTINGS ------------------------------//
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);
        

        $fileselector_filter = $webodv_settings["fileselector_filter"];
        $Path_to_odv_data = $webodv_settings['path_to_odv_data'];
        Log::info("Path_to_odv_data");
        Log::info($Path_to_odv_data);

        if ($request->session()->has("private_workspace")){
            $private_workspace = '/'.session("private_workspace");
        } else {
            $private_workspace = "";
        }


        session(['priv_workspace_current_path' => '']);
        if ($request->pager_num > 1){
            //this was a click on the pager
            Log::info("new treeview");
            //get path
            $priv_workspaces = session('priv_workspaces');
            foreach ($priv_workspaces as $item){
                if ($item->id == $request->pager_num){
                    $Path_to_odv_data = $item->path;
                    session(['priv_workspace_current_path' => $Path_to_odv_data]);
                }
            }
        }
        

//Log::info("Path_to_odv_data=" . $Path_to_odv_data . $private_workspace);
        $create_treeview_from_folder = new create_treeview_from_folder();
        if (Auth::check()){
            //Log::info("Pathxx");
            //Log::info($Path_to_odv_data . $private_workspace);
            $treeview_data = ['user' => Auth::user()->name, 'path' => $Path_to_odv_data . $private_workspace, 'fileselector_filter' => $fileselector_filter, 'data-boldParents' => 'data-boldParents="true"'];
        } else {
            $treeview_data = ['user' => "dummy", 'path' => $Path_to_odv_data . $private_workspace, 'fileselector_filter' => $fileselector_filter, 'data-boldParents' => 'data-boldParents="true"'];
        }
        $treeview = $create_treeview_from_folder->create($treeview_data);
        //Log::info($treeview);


        //return json_encode('baggi123');
        return json_encode($treeview);

    }
    

    public function monitor(Request $request) {

        if (Auth::check() == false){
            abort(401);
        }
        
        $user = Auth::user();
        /* if ($user->email == 'sebastian.mieruch@awi.de' || $user->email == 'baggi@gmx.li'){ */
        /*     $dummy = true; */
        /* } else { */
        /*     abort(401); */
        /* } */

        Log::info("monitor");
        Log::info($user->email);
        
        echo '<script>var monitor_process_url = "'.route('monitor_process').'";</script>';

        
        return view('webodv.webodvcore.monitor');
    }


    
    public function monitor_process(Request $request) {

        //get number of odvws instances
        
        //$used_all = Wsodv_used::all();
        //Log::info(print_r(count($used_all),1));
        //$num_odvws = count($used_all);

        //get CPU and RAM

        $this->validate($request, [
            'real_time_from' => ['required', 'string', 'max:255'],
        ]);
        //Log::info('real_time_from = '.$request->real_time_from);
        
        
        /* $xdate = "date"; */
        /* $cpu = "cpu"; */
        /* $mem = "mem"; */
        /* $num_odvws = 1; */

        
        //$q->where('created_at', '>=', date('Y-m-d').' 00:00:00'));
        $monitor = DB::table('monitor')
            ->where('created_at', '>=', $request->real_time_from)
            ->get();

        //Log::info(print_r($monitor,1));
        
        /* foreach($monitor as $item){ */
        /*     Log::info($item->created_at); */
        /* } */
    
        
        return json_encode($monitor);
    }
    
    
    
}
