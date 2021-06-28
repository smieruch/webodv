<?php

namespace App\webodvLibs;

use Illuminate\Support\Facades\Log;
use App\webodvTraits\trait_get_branch;


class create_header_links {

        use trait_get_branch;
    
    public function create(Array $data){


        $branch = $this->getBranch();
        //$branch_array = explode('/',url()->current());
        //Log::info("service_branch");
        //Log::info(print_r($branch_array,1));
        //$branch = $branch_array[3];

        if (empty($data['odv_data_header'])){
            $branch_str = $branch;
        } else {
            $branch_str = "";
        }

        if (isset($data['odv_data_header_first'])){
            $branch_str = $data['odv_data_header_first'];
        }


        $header_explode = explode('>',$data['odv_data_header']);
        Log::info("header_explode");
        Log::info(print_r($header_explode,1));
        //Log::info("data:");
        //Log::info(print_r($data,1));
        $header_combine = '<a class="nav-link" style="display:inline; padding:0rem 0rem;" href="' . $data['baseurl'] . '">' . $branch_str . '</a>' . "\n";
        $headX_combine = "";
        $linkX_combine = $data['baseurl'];
        $L = count($header_explode);
        Log::info("L ist=".$L);
        for ($i=0;$i<=$L-1;$i++){
            if ($i==0){
                $headX_combine = $header_explode[$i];
                $linkX_combine = $linkX_combine . '/' . $header_explode[$i];
            } else {
                $headX_combine = $header_explode[$i];
            }
            if ($i>0 && $i<($L-1)){
                $linkX_combine = $linkX_combine . '>' . $header_explode[$i];
            }
            if ($i==($L-1)){
                if ($L>1){
                    $linkX_combine = str_replace($data['baseurl'],$data['baseurl'].'/service',$linkX_combine) . '>' . str_replace('.odv','',$header_explode[$i]);
                } else {
                    $linkX_combine = str_replace($data['baseurl'],$data['baseurl'].'/service',str_replace('.odv','',$linkX_combine));
                }
                Log::info("linkX_combine=".$linkX_combine);
            }
            if (empty($header_explode[0])){
                $header_combine = $header_combine  . '<a class="nav-link" style="display:inline; padding:0rem 0rem;" href="' . $linkX_combine . '">' . $headX_combine . '</a>' . "\n";
            } else {
                $header_combine = $header_combine . ' > ' . '<a class="nav-link" style="display:inline; padding:0rem 0rem;" href="' . $linkX_combine . '">' . $headX_combine . '</a>' . "\n";
            }
            //Log::info("xheader:".$i);
        }
        //Log::info("xheader");
        //Log::info(print_r($header_combine,1));
        session([$data['session_varname'] => $header_combine]);

        //Log::info("create_header XXXXXXXXXXXXXXXXXXXXXXXXXXXXX");
        
        if (session('awiimport')){
            //Log::info("create_header awiimport");
            $header_combine = '<a class="nav-link" style="display:inline; padding:0rem 0rem;" href="'. route('awiimportservice') .'">'. session('orig_filename')  .'</a>';
            session([$data['session_varname'] => $header_combine]);
        }


        return true;
    }




}