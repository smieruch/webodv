<?php

namespace Webodv\Webodvextractor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\webodvLibs\create_hummingbird_treeview;
use Illuminate\Support\Facades\Validator;
use App\webodvLibs\wsODV_manager;
use App\webodvLibs\create_header_links;
use App\webodvLibs\webodv_load_settings;
use App\Wsodv_available;
use Auth;
use WebSocket\Client;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
//use App\Mail\DownloadFinished;
use Notification;
use App\Notifications\DownloadFinished;
use Illuminate\Notifications\Notifiable;
use App\webodvTraits\trait_webodvextractor_download;
use App\webodvTraits\trait_get_branch;
use App\download_tracking;

class WebodvextractorController extends Controller
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

    use trait_webodvextractor_download;
    use trait_get_branch;

    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function webodvextractor(Request $request)
    {
        //check if a wsODV has been started
        //if not than this is a direct entry to that route or browser refresh
        //thus start new wsODV, i.e. go through wsODV_init
        //the url has an extra 'wsODV' at the end
        //see routes.php in webodvcore
        if ($request->session()->has('wsODV_started')){
            Log::info("wsODV_started exists");
        } else {
            Log::info("wsODV_started exists not");
            return redirect(url()->current() .'/wsODV');
        }



        //Log::info("webodvextractor");
        /* Log::info(print_r($request->all(),1)); */

        //get URL parameter datasetname
        $datasetname = $request->datasetname;
        //add to request for validation
        $request->request->add(['datasetname' => $datasetname]);

        
        $request->validate([
            'datasetname' => 'nullable|string',
        ]);

        //Log::info($datasetname);
        $datasetname_arr = explode('>',$datasetname);
        
        session(['dataset' => end($datasetname_arr)]);

        $branch = $this->getBranch();
        /* $branch_array = explode('/',url()->current()); */
        /* $branch = $branch_array[3]; */


        /* $private_workspace = ''; */
        /* //vre */
        /* if ($branch == "private"){ */
        /*     $private_workspace = Auth::user()->private_workspace; */
        /* } */

        if ($request->session()->has("private_workspace")){
            $private_workspace = '/'.session("private_workspace");
        } else {
            $private_workspace = "";
        }
        

        
        //$webodv_load_settings = new webodv_load_settings();
        //$webodv_settings = $webodv_load_settings->load(['branch' => '']);

        //-------------- SETTINGS ------------------------------//
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);
        $project = $webodv_settings["project"];
        /* if ($project == "webODV-AWI"){ */
        /*     $private_workspace = '/' . Auth::user()->email . '/webodv'; */
        /* } */



        $odv_data = $webodv_load_settings->load_odv(['datasetname' => $request->datasetname, 'private_workspace' => $private_workspace, 'priv_workspace_current_path' => session('priv_workspace_current_path')]);

        $allowed_wsodv_instances = $webodv_settings["allowed_wsodv_instances"];
        $allowed_wsodv_download_instances = $webodv_settings["allowed_wsodv_download_instances"];
        
        if (isset($webodv_settings["EMODnet_chem_settings"])){
            $EMODnet_chem_settings = $webodv_settings["EMODnet_chem_settings"];
            $marine_id_login = $EMODnet_chem_settings['marine_id_login'];
            $marine_id_logout = $EMODnet_chem_settings['marine_id_logout'];
            session(['marine_id_logout' => $marine_id_logout]);
        } else {
            $marine_id_login = "";
            $marine_id_logout = "";
        }


        //this is an extra text for the output variables help
        if (isset($odv_data["output_var_extra_text"])){
            $output_var_extra_text = $odv_data["output_var_extra_text"];
        } else {
            $output_var_extra_text = "";
        }
        
        /* $odv_data = file_get_contents(storage_path('app/JSON/'.str_replace('>','_',$request->datasetname).'.json')); */
        /* $odv_data = json_decode($odv_data,true); */

        //Log::info("extractor_info");
        //Log::info(print_r($odv_data[$request->file_num]['extractor_info'],1));
        
        //session(['dataset_link' => route('data', ['datasetname' => $datasetname])]);
        //session(['dataset' => str_replace('>',' > ',$odv_data['header'])]);
        session(['service' => 'DataExtraction']);  //this is needed to load js in webodv_layout.blade.php

        if (isset($webodv_settings["odv_data_header_first"])){
            $odv_data_header_first = $webodv_settings["odv_data_header_first"];
        } else {
            $odv_data_header_first = "";
        }

        
        //create header links
        $create_header_links = new create_header_links();
        $create_header_links_data = ['session_varname' => 'dataset_link', 'odv_data_header' => $odv_data['header'], 'baseurl' => url($webodv_settings['base_url']), 'odv_data_header_first' => $odv_data_header_first];
        $create_header_links_out = $create_header_links->create($create_header_links_data);

        //extractor exit link
        $odv_data_header = basename($odv_data['header'],'.odv');

        
        $extractor_info = $odv_data['extractor_info'];

        //if this is a geotraces dataset set project accordingly
        //else take default

        if (preg_match("/GEOTRACES/", $datasetname)){
            $project = 'GEOTRACES';
            session(['project' => 'GEOTRACES']);
        } else {
            $project = $webodv_settings["project"];
            session(['project' => $project]);
        }

        /* Log::info("project"); */
        /* Log::info($project); */
        
        //dump js
        echo session('js_dump');
        //echo '<script>var http_port="' . $webodv_settings['http_port'] . '";</script>';
        echo '<script>var http_port="' . session('http_port') . '";</script>';
        echo '<script>var branch="' . $branch . '";</script>';
        echo '<script>var project="' . $project . '";</script>';
        echo '<script>var datasetname="' . $datasetname . '";</script>';
        echo '<script>var left_mouse_click_on_map=' . json_encode($extractor_info['left_mouse_click_on_map']) . ';</script>';
        echo '<script>var point_size=' . $extractor_info['point_size'] . ';</script>';
        echo '<script>var default_dates=' . json_encode($extractor_info['dates']) . ';</script>';
        echo '<script>var default_koordinates=' . json_encode($extractor_info['default_koordinates']) . ';</script>';
        echo '<script>var mandatory_output_vars=' . json_encode($extractor_info['mandatory_output_vars']) . ';</script>';
        echo '<script>var default_output_vars=' . json_encode($extractor_info['default_output_vars']) . ';</script>';
        //echo '<script>var dataset_link="' . session('dataset_link') . '";</script>';
        echo '<script>var default_x_visualization=' . $extractor_info['default_x_visualization'] . ';</script>';
        echo '<script>var default_y_visualization=' . $extractor_info['default_y_visualization'] . ';</script>';
        echo '<script>var depth_var_num=' . $extractor_info['depth_var_num'] . ';</script>';
        echo '<script>var download_allowed=' . json_encode($extractor_info['download_allowed']) . ';</script>';        
        echo '<script>var direct_downloads=' . json_encode($webodv_settings["direct_downloads"]) . ';</script>';

        
        
        if (Auth::check()){
            $username = Auth::user()->name;
            $country = Auth::user()->country;
            $institution = Auth::user()->institution;
            #remove special emodnet string from email                                                                                                                                        
            $emstr = '_emodnet_chem';
            $email = Auth::user()->email;
            $email = str_replace($emstr,'',$email);
            $role = Auth::user()->role;
        } else {
            $username = "";
            $country = "";
            $institution = "";
            $email = "";
            $role = "";
        }

        echo '<script>var username="' . $username . '";</script>';
        echo '<script>var country="' . $country . '";</script>';
        echo '<script>var institution="' . $institution . '";</script>';
        echo '<script>var email="' . $email . '";</script>';
        echo '<script>var role="' . $role . '";</script>';

        if ($request->session()->has('vre_URL')){
            $exit_url = session('vre_URL');
        } else {
            $exit_url = url('/');
        }

        if ($request->session()->has('dev_pass')){
            $dev_pass = session('dev_pass');
            session(['dev_pass' => false]);
        } else {
            $dev_pass = false;
        }

        //$dev_pass = true;

        echo '<script>var dev_pass_server="' . $dev_pass . '";</script>';

        
        //Log::info(print_r(session()->all(),1));

        
        /* Log::info("baggi3"); */
        /* Log::info(print_r($request->all(),1)); */
        
        //check if contaminants
        if (strpos($datasetname, 'contaminants') !== false){
            $contaminants = true;
        } else{
            $contaminants = false;
        }

        echo '<script>var contaminants=' . json_encode($contaminants) . ';</script>';

        //check if eutrophication
        if (strpos($datasetname, 'eutrophication') !== false){
            $eutrophication = true;
        } else{
            $eutrophication = false;
        }

        echo '<script>var eutrophication=' . json_encode($eutrophication) . ';</script>';
        
        //load contaminants treeview
        $contaminants_treeview_path = storage_path('emodnet_contaminants_treeview.html');
        if (file_exists($contaminants_treeview_path)){
            $contaminants_treeview = file_get_contents($contaminants_treeview_path);
        } else {
            $contaminants_treeview = "";
        }


        echo '<script>var scatter_plot_button_snippet = \'Scatter plot &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <img src="' . asset('images/scatter_icon.png') . '" width="64px" />\';</script>';

        
        
        return view('webodv.webodvextractor.webodvextractor',compact('extractor_info','odv_data_header','marine_id_login','marine_id_logout','allowed_wsodv_instances','allowed_wsodv_download_instances','exit_url','output_var_extra_text','contaminants_treeview','project','contaminants'));        
    }



    public function webodvextractor_create_treeview(Request $request)
    {

        Log::info("webodvextractor_create_treeview");
        /* Log::info(print_r($request->all(),1)); */

        //get URL parameter datasetname
        $datasetname = $request->datasetname;
        //add to request for validation
        $request->request->add(['datasetname' => $datasetname]);

        
        $request->validate([
            'datasetname' => 'nullable|string',
        ]);

        //Log::info($datasetname);

        $branch = $this->getBranch();
        /* $branch_array = explode('/',url()->current()); */
        /* $branch = $branch_array[3]; */

        /* $private_workspace = ''; */
        /* //vre */
        /* if ($branch == "private"){ */
        /*     $private_workspace = Auth::user()->private_workspace; */
        /* } */

        if ($request->session()->has("private_workspace")){
            $private_workspace = '/'.session("private_workspace");
        } else {
            $private_workspace = "";
        }

        
        //-------------- SETTINGS ------------------------------//
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);
        $project = $webodv_settings["project"];
        /* if ($project == "webODV-AWI"){ */
        /*     $private_workspace = '/' . Auth::user()->email . '/webodv'; */
        /* } */


        Log::info("branch=".$branch);
        Log::info("priv_work=".$private_workspace);
        $webodv_load_settings = new webodv_load_settings();
        $odv_data = $webodv_load_settings->load_odv(['datasetname' => $request->datasetname, 'private_workspace' => $private_workspace, 'priv_workspace_current_path' => session('priv_workspace_current_path')]);

        /* $odv_data = file_get_contents(storage_path('app/JSON/'.str_replace('>','_',$request->datasetname).'.json')); */
        /* $odv_data = json_decode($odv_data,true); */
        Log::info("baggi2");
        Log::info(print_r($odv_data,1));

        //check if treeview file exists
        $webodv_settings_path = config('webodv.settings_path') . '/' . config('webodv.path_to_odv_settings');
        $treeview_path = $webodv_settings_path.'/TreeviewsVar'.$private_workspace.'/'.str_replace('>','_',$request->datasetname).'_variables_treeview.html';
        if (file_exists($treeview_path)) {
            //Log::info("treeview exists");
            $treeview = file_get_contents($treeview_path);
        } else {
            //create treeview
	    	//Log::info("treeview create");


            //copy file in the case of awi
            if ($project == "webODV-AWI"){
                $new_odv_file_name = Str::random(8) . '.odv';
                //copy odv file
                $msg = 'sudo -u ' . Auth::user()->name . ' cp ' . $odv_data['path'] . ' ' . storage_path('app/local/') . $new_odv_file_name;
                Log::info($msg);
                shell_exec($msg);
                //chmod odv file
                $msg = 'sudo -u ' . Auth::user()->name . ' chmod 775 ' . storage_path('app/local/') . $new_odv_file_name;
                Log::info($msg);
                shell_exec($msg);
                //new odv_data_path
                $odv_data['path'] = storage_path('app/local/') . $new_odv_file_name;
            }

            
            $create_treeview = new create_hummingbird_treeview();
            $create_treeview->create(['infile' => $odv_data['path'], 'outfile' => $treeview_path]);
            $treeview = file_get_contents($treeview_path);
        }
        

        return json_encode($treeview);
    }


    public function webodvextractor_download(Request $request)
    {


        
        
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);
        $odv_data = $webodv_load_settings->load_odv(['datasetname' => $request->datasetname]);


        $extractor_info = $odv_data['extractor_info'];

        if ($extractor_info["download_allowed"]){

            $branch = $this->getBranch();
            /* $branch_array = explode('/',url()->current()); */
            /* $branch = $branch_array[3]; */
            $request->request->add(['branch' => $branch]);
            
        
            //Log::info(print_r($request->all(),1));
            //validation in create_download
            $create_download = $this->create_download($request);
            //Log::info("baggi success");
            //die();

        


            //start a worker because I use QUEUE_CONNECTION=database
            //send email
            if ($create_download["export_success"]){
                if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
                    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];  
                    //Log::info("real ip = ".$ip);
                } else {
                    $ip = $request->ip();
                }


                //get country
                try {
                    $position = Location::get($ip);
                } catch (\Throwable $e) {
                    Log::info("Location exception");
                }


                if (isset($position->countryName)) {
                    $country = $position->countryName;
                } else {
                    $country = "dummy";
                }


                $download_tracking = new download_tracking;
                $download_tracking->email = $create_download["email"];
                $download_tracking->ws_message = json_encode($request->all());
                $download_tracking->service = "GEOTRACES";
                $download_tracking->country = $country;
                $download_tracking->domain = url('/');
                $download_tracking->save();
                

                //direct download or email
                if ($webodv_settings["direct_downloads"]) {
                    return json_encode(["output_file_url" => $create_download["output_file_url"], "file_name" => basename($create_download["output_file_url"])]);
                } else {
                    //send email
                    //customize
                    $intro = "Your webODV data request is ready for download within the next 24 hours. Please click the download button.";

                    //Log::info("send mail now");
                    $data = (object) ['intro' => $intro, 'url' => $create_download["output_file_url"], 'subject' => 'webODV download finished', 'action_text' => 'Download', 'bcc' => 'dummy'];
                    //$data = (object) ['intro' => $intro, 'url' => session('vre_URL'), 'subject' => 'webODV import finished', 'action_text' => 'VRE'];            
            
                    /* Log::info("data= "); */
                    /* Log::info(print_r($data,1)); */
            
                    Notification::route('mail', $create_download["email"])->notify(new DownloadFinished($data));
                }
            }

        }

        return json_encode('success');
        
    }



}
