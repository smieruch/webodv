<?php

namespace Webodv\Webodvxdefault;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\webodvTraits\trait_webodvextractor_download;
use App\download_tracking;
use Location;
use Illuminate\Support\Facades\Mail;
use Notification;
use App\Notifications\DownloadFinished;
use Illuminate\Notifications\Notifiable;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use App\webodvLibs\webodv_load_settings;
use App\webodvLibs\create_json_config;
use Auth;
use App\webodvTraits\trait_import;
use ZipArchive;
//

class WebodvxdefaultController extends Controller
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
    use trait_import;
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

    public function webodvextractor_download(Request $request)
    {

        /* Log::info("webodvextractor_download"); */
        /* Log::info(print_r($request->all(),1)); */
        //Log::info(json_encode($request->all()));


      //validation in create_download
        $create_download = $this->create_download($request);
        //Log::info("baggi success");
        //die();        
        
        if ($create_download["auth"] == false){
            Log::info("create_download_auth_false");
            return json_encode(["auth" => false]);
        }

        if ($create_download["validate"] == false){
            Log::info("create_download_validate_false");
            return json_encode(["validate" => false]);
        }

        /* Log::info("create_download"); */
        /* Log::info(print_r($create_download,1)); */

        //validate licence agree
        $request->validate([
            'licence_agree.usage' => 'required|string',
            'licence_agree.funding' => 'required|string'
        ]);


        //send email
        if ($create_download["export_success"]){

            //track download in DB

            //get ip of user
            //get ip of user
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
            


            //mail customize
            /* $intro = "Your GEOTRACES data request is ready for download within the next 24 hours. Please click the download button."; */
            /* $data = (object) ['intro' => $intro, 'url' => $create_download["output_file_url"]]; */
            /* Notification::route('mail', $create_download["email"])->notify(new DownloadFinished($data)); */
        }

        //return json_encode('success');
        return json_encode(["output_file_url" => $create_download["output_file_url"], "file_name" => basename($create_download["output_file_url"])]);
        
    }



    public function awiimport(Request $request)
    {
        Log::info("awiimport");

        //header breadcrumb
        session(['dataset_link' => ""]);
        
        //Log::info(print_r($request->all(),1));

        //validate
        $validatedData = $request->validate([
            "Dataset" => ['required'],
        ]);



        //DOWNLOAD
        //$orig_filename = basename($request->Dataset,'.txt');
        $orig_filename = "webodv_o2a_import.txt";
        $new_file_path = "awi_import_" . Str::random(10);
        $file_path = storage_path('app/local/').$new_file_path.'/'.$orig_filename.'.txt';

        session(['orig_filename' => $orig_filename]);
        session(['new_file_path' => $new_file_path]);

        if (!file_exists(storage_path('app/local/').$new_file_path)){
            mkdir(storage_path('app/local/').$new_file_path);
        }
        
        try
            {
                $client = new Client(['verify' => false]); //GuzzleHttp\Client
                $download_request = $client->request('GET', $request->Dataset, [
                    'sink' => $file_path
                ]);
                //
            }
        catch (\GuzzleHttp\Exception\ClientException $e) {
            abort(401);
        }


        //import
        //write list file----------------------------------------------------------------------------------------------------------

        $import_token = Str::random(40);
        $lists_path = storage_path('app/import_lists');
        Log::info("lists_path: ".$lists_path);
        if (!file_exists($lists_path)){
            Storage::makeDirectory('import_lists');
        }
        //full path to the file
        $listfile = $lists_path . '/' . $import_token . '.lst';

        //load data and write list file file
        $file_handler = fopen($listfile,"w");
        fwrite($file_handler, $file_path . "\n");
        fclose($file_handler);
        //------------------------------------------------------------------------------------

        
        //-------------- SETTINGS ------------------------------//
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);

        
        $private_workspace="";
        $default_dataset_json = $webodv_settings["default_dataset_json"];

        
        //write json file for that dataset
        $webodv_settings_path = config('webodv.settings_path') . '/' . config('webodv.path_to_odv_settings');
        $json_path = 'local/'; 
        if ( !file_exists($webodv_settings_path.'/JSON/'. $json_path . $new_file_path.'/'.$orig_filename .'.json') ){
            Log::info("json config for this file exists not");
            Log::info("create json config");
            //create and write a json config file from the default
            $create_json_config = new create_json_config();
            $create_json_config_data = ['default_dataset_json' => $default_dataset_json, 'datasetname' => $new_file_path.'>'.$orig_filename, 'branch' => '', 'private_workspace' => $private_workspace];
            $create_json_config_out = $create_json_config->create($create_json_config_data);
            //
        } else {
            Log::info("json config for this file exists");
        }



        //set some session vars
        $this_parse_url = parse_url(url('/'));
        $client_url = $this_parse_url['host'];

        if (isset($this_parse_url['port'])){
            $http_port = ':'.$this_parse_url['port'];
        } else {
            $http_port = "";
        }
        session(['client_url' => $client_url]);
        session(['http_port' => $http_port]);
        
        
        //start or connect odv instance        
        $Data = ["datasetname" => $new_file_path.'/'.$orig_filename, "datasetpath" => $new_file_path, "listfile" => $listfile, "Path_to_odv_data" => getenv('settings_name')];
        $start_import = $this->import($Data);

        Log::info("start_import=".$start_import['success']);


        //
        if ($start_import['success']){
            return json_encode(["success" => true, "URL" => route('awiimportservice')]);            
        } else {
            return json_encode("failure");            
        }


    }

    public function awiimportservice(Request $request)
    {
        session(["awiimport" => true]);
        $private_workspace = "";
        $webodv_load_settings = new webodv_load_settings();
        $odv_data = $webodv_load_settings->load_odv(['datasetname' => session('new_file_path').'>'.session('orig_filename'), 'private_workspace' => $private_workspace]);
        //Log::info(print_r($odv_data,1));
        $dataset_heading = "";
        $branch = "";
        //Log::info("return")

        $header_combine = '<a class="nav-link" style="display:inline; padding:0rem 0rem;" href="'. route('awiimportservice') .'">'. session('orig_filename')  .'</a>';
        session(['dataset_link' => $header_combine]);


        session(['service' => "awiimport"]);

            
        return view('webodv.webodvcore.service',compact('odv_data','dataset_heading','branch'));

    }
        

    
    public function awiimportclient(Request $request)
    {

        //header breadcrumb
        session(['dataset_link' => ""]);

        if ($request->has("Dataset")){
            //validate
            $validatedData = $request->validate([
                "Dataset" => ['required'],
            ]);

            $ImportDatasetURL = $request->Dataset;

            echo '<script>var ImportDatasetURL = "'.$ImportDatasetURL.'";</script>';
            echo '<script>var AJAXURL = "'. route('awiimport') .'";</script>';

            
            return view('webodv.awiimport');
        } else {

            $paras = [
                "Dataset" => 'https://webodv.awi.de/downloads/data_from_Dirty_Dataset.txt',
                //"Dataset" => 'localhost/downloads/data_from_Dirty_Dataset.txt',
            ];

            /* if ($request->has('LAYERS')){ */
            /*     Log::info("has LAYERS !!!"); */
            /*     $wms_url = url()->full(); */
            /*     Log::info($wms_url); */
            /*     echo '<img src="' .  $wms_url  . '" />'; */
            /* } */

        
            return view('webodv.awiimportclient',compact('paras'));
        }
    }
    

    public function awi_webodvextractor_download(Request $request)
    {

        Log::info("vre -> webodvextractor_download");
        Log::info(print_r($request->all(),1));

        //$private_workspace = "";
        $private_workspace = session('private_workspace');

        //put private_workspace into request only if data in the
        //private folder to access it
        $request->request->add(['private_workspace' => $private_workspace]);        
        //then use private workspace to put all data
        //private and public into the private workspace

        //$request->datasetname = Auth::user()->name . '>' . $request->datasetname;
        
        //validation in create_download
        $create_download = $this->create_download($request);
        //Log::info("baggi success");



        
        //copy zip to priv workspace into an webODV_yyyymmdd folder
        //and unzip

        //check if folder exist
        $new_folder = 'webODV_export_' . date('Ymd') . '/' . Str::random(8);
        $private_workspace_path = storage_path('app/awi/' . $private_workspace . '/webODV_export_' . date('Ymd'));
        $local_workspace_path = storage_path('app/local/' . $private_workspace . '/' . $new_folder);
        Log::info($private_workspace_path);
        if (!file_exists($private_workspace_path)){
            Log::info("folder does not exists");
            //create local
            mkdir($local_workspace_path,0777,true);
            $msg = 'sudo -u ' . Auth::user()->name . ' mkdir -p ' . $private_workspace_path;
            Log::info($msg);
            shell_exec($msg);
        }
        //$output_zip_file = $private_workspace_path . '/' . basename($create_download["output_file_path"]);
        //copy($create_download["output_file_path"],$private_workspace_path . '/' . $filename);

        

        Log::info("zip: " . $create_download["output_file_path"]);
        $zip = new ZipArchive;
        if ($zip->open($create_download["output_file_path"]) === TRUE) {
            $zip->extractTo($local_workspace_path);
            $zip->close();
            Log::info('ok');
        } else {
            Log::info('failed');
        }

        //copy unzipped export into private folder
        $msg = 'sudo -u ' . Auth::user()->name . ' cp -r ' . $local_workspace_path . ' ' . $private_workspace_path;
        shell_exec($msg);

        //send email
        if ($create_download["export_success"]){
            //customize
            if ($request->file_format == "odv"){
                $intro = 'Your webODV data export is ready in your workspace under the folder <span style="color:#5cb85c;"><b><i>' . $new_folder . '</i></b></span>. Click the button to access your data with webODV.';
            } else {
                $intro = 'Your webODV data export is ready in your workspace under the folder <span style="color:#5cb85c;"><b><i>' . $new_folder . '</i></b></span>. Click the button to go back to webODV.';
            }
            
            $data = (object) ['intro' => $intro, 'url' => url('/'), 'subject' => 'webODV export finished', 'action_text' => 'webODV', 'bcc' => 'dummy'];
            //$data = (object) ['intro' => $intro, 'url' => session('vre_URL')];
            
            
            //Log::info("data= ".$data->intro);
            
            
            Notification::route('mail', $create_download["email"])->notify(new DownloadFinished($data));
        }

        return json_encode('success');
        
    }

    
    
}
