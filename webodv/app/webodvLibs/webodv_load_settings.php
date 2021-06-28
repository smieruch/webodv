<?php

namespace App\webodvLibs;

use Illuminate\Support\Facades\Log;
use App\webodvTraits\trait_get_branch;

class webodv_load_settings {

    use trait_get_branch;


    public function load(array $data){

        if (!empty($data['branch'])){
            $branch = $data['branch'];
        } else {
            $branch = $this->getBranch();
            //$branch_array = explode('/',url()->current());
            //Log::info("service_branch");
            //Log::info(print_r($branch_array,1));
            //$branch = $branch_array[3];
        }

        $webodv_settings_path = config('webodv.settings_path') . '/' . config('webodv.path_to_odv_settings');
        $settings_file = $webodv_settings_path.'/webodv_settings_' . $branch . '.json';
        //Log::info("settings_file");
        //Log::info($settings_file);
        if (file_exists($settings_file)){
            $webodv_settings = file_get_contents($settings_file);
        } else {
            return false;
        }
        $webodv_settings = json_decode($webodv_settings,true);
        return $webodv_settings;
    }

    public function load_odv(array $data){

        $branch = $this->getBranch();
        
        //$branch_array = explode('/',url()->current());
        //Log::info("service_branch");
        //Log::info(print_r($branch_array,1));
        //$branch = $branch_array[3];

        if (isset($data['private_workspace'])){
            $private_workspace = $data['private_workspace'];
        } else {
            $private_workspace = '';
        }

        if ($branch == "awi"){
            $Path_to_odv_data = $branch.'/'.$private_workspace;
        } else {
            $Path_to_odv_data = $branch.$private_workspace;
        }

        if (!empty($data['priv_workspace_current_path'])){
            //Log::info("not empty");
            $Path_to_odv_data = $data['priv_workspace_current_path'];
        }

        /* Log::info($Path_to_odv_data); */
        /* die(); */


        
        $webodv_settings_path = config('webodv.settings_path') . '/' . config('webodv.path_to_odv_settings');
        $final_path = $webodv_settings_path.'/JSON/'.$Path_to_odv_data;
        $json_file = $final_path . '/' . str_replace('>','_',$data['datasetname']).'.json';
        Log::info("json_file");
        Log::info($json_file);
        if (file_exists($json_file)){
            $odv_data = file_get_contents($json_file);
        } else {
            return false;
        }
        $odv_data = json_decode($odv_data,true);
        return $odv_data;
    }


}