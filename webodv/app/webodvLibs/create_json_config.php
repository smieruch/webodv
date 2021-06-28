<?php

namespace App\webodvLibs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\webodvTraits\trait_get_branch;

class create_json_config {

        use trait_get_branch;

    public function create(Array $data){

        if (!empty($data['branch'])){
            $branch = $data['branch'];
        } else {
            $branch = $this->getBranch();
            //$branch_array = explode('/',url()->current());
            //Log::info("service_branch");
            //Log::info(print_r($branch_array,1));
            //$branch = $branch_array[3];
        }

        if (isset($data['private_workspace'])){
            $private_workspace = $data['private_workspace'];
        } else {
            $private_workspace = '';
        }
        
        $Path_to_odv_data = $branch.$private_workspace;

        if (!empty($data['priv_workspace_current_path'])){
            $Path_to_odv_data = $data['priv_workspace_current_path'];
        } 

        $webodv_settings_path = config('webodv.settings_path') . '/' . config('webodv.path_to_odv_settings');
        $template = file_get_contents($webodv_settings_path.'/Defaults/' . $data['default_dataset_json']);
        $template = json_decode($template,true);
        $template['datasetname'] = $data['datasetname'];
        $template['header'] = $data['datasetname'].'.odv';
        $file_path_name = str_replace('>','/',$data['datasetname']);
        $template['path'] = storage_path('app/'.$Path_to_odv_data.'/'.$file_path_name.'.odv');
        $template['text'] = '';
        $template = json_encode($template,JSON_PRETTY_PRINT);
        $final_path = $webodv_settings_path.'/JSON/'.$Path_to_odv_data;
        if (!file_exists($final_path)){
            mkdir($final_path,0777,true);
        }
        file_put_contents($final_path . '/' . str_replace('>','_',$data['datasetname']).'.json', $template);


        return true;
    }




}