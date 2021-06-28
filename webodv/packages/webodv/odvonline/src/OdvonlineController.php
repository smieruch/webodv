<?php

namespace Webodv\Odvonline;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\webodvLibs\create_header_links;
use App\webodvLibs\webodv_load_settings;
use Auth;
use App\webodvTraits\trait_get_branch;

class OdvonlineController extends Controller
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

    use trait_get_branch;

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function odvonline_init(Request $request)
    {
        //check if a wsODV has been started
        //if not than this is a direct entry to that route or browser refresh
        //thus start new wsODV, i.e. go through wsODV_init
        //the url has an extra 'wsODV' at the end
        //see routes.php in webodvcore
        if ($request->session()->has('wsODV_started')){
            Log::info("wsODV_started exists");
        } else {
            return redirect(url()->current() .'/wsODV');
        }
        //
        

        
        //get URL parameter datasetname
        $datasetname = $request->datasetname;
        //add to request for validation
        $request->request->add(['datasetname' => $datasetname]);
        //
        $request->validate([
            'datasetname' => 'nullable|string',
        ]);

        
        $datasetname_arr = explode('>',$datasetname);
        session(['dataset' => end($datasetname_arr)]);


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

        if ($request->session()->has("private_workspace")){
            $private_workspace = '/'.session("private_workspace");
        } else {
            $private_workspace = "";
        }


        
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);
        Log::info("webodv_settings");
        Log::info(print_r($webodv_settings,1));
        $odv_data = $webodv_load_settings->load_odv(['datasetname' => $request->datasetname, 'private_workspace' => $private_workspace, 'priv_workspace_current_path' => session('priv_workspace_current_path')]);
        /* $odv_data = file_get_contents(storage_path('app/JSON/'.str_replace('>','_',$request->datasetname).'.json')); */
        /* $odv_data = json_decode($odv_data,true); */


        //create header links
        /* $create_header_links = new create_header_links(); */
        /* $create_header_links_data = ['session_varname' => 'dataset_link', 'odv_data_header' => $odv_data['header'], 'baseurl' => url('/webodv')]; */
        /* $create_header_links_out = $create_header_links->create($create_header_links_data); */


        if (isset($webodv_settings["odv_data_header_first"])){
            $odv_data_header_first = $webodv_settings["odv_data_header_first"];
        } else {
            $odv_data_header_first = "";
        }

        
        //create header links
        $create_header_links = new create_header_links();
        $create_header_links_data = ['session_varname' => 'dataset_link', 'odv_data_header' => $odv_data['header'], 'baseurl' => url($webodv_settings['base_url']), 'odv_data_header_first' => $odv_data_header_first];
        $create_header_links_out = $create_header_links->create($create_header_links_data);

        
        //get user
        if (Auth::check()){
            $username = Auth::user()->name;
        } else {
            $username = '';
        }

        echo '<script>var username = "'. $username .'";</script>';


        session(['service' => 'DataExploration']);  //this is needed to load js in webodv_layout.blade.php


        echo '<script>var http_port="' . $webodv_settings['http_port'] . '";</script>';

        echo '<script>var route_odvonline="' . route('odvonline') . '";</script>';

        
        return view('webodv.odvonline.odvonline_init');
    }

    public function odvonline()
    {

        $resourceRoot = session('resourceRoot');

        //this is in WebodvcoreController
        $version_js_css = session('js_css_update');

        echo session('js_dump');
        return view('webodv.odvonline.odvonline',compact('resourceRoot','version_js_css'));        
    }
    

}
