<?php

namespace App\webodvLibs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class create_treeview_from_folder {

    public function create(Array $data){

        $m = 1;
        $known_folders = [];
        //data-id creates an individual id of the treeview ul, which is per default "treeview" and
        //with data-id="_example" it is "treeview_example"
        if (!isset($data['id'])){
            $data['id']="";
        }
        if (!isset($data['data-boldParents'])){
            $data['data-boldParents'] = "";
        }
        $treeview = '<div class="hummingbird-treeview-converter" data-height="400px" data-scroll="true" ' . $data['id']  . ' ' . $data['data-boldParents']  . '>';
        //Log::info("create_treeview_from_folder.php");
        //Log::info($data['path']);
        $files = Storage::allFiles($data['path']);
        foreach ($files as $file){
            //filetr odv files
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            if ("" != $data['fileselector_filter']){
                if ($extension != $data['fileselector_filter']){
                    continue;
                }
            }
            $file = str_replace($data['path'].'/','',$file);
            $folders = explode('/',$file);
            $hyphens = "";
            $treePath = "";
            $filepath = "";
            foreach ($folders as $folder){
                $filepath = $filepath . $folder;
                if (isset($known_folders[$filepath])){
                    $hyphens = $hyphens . '-';
                    if ($treePath == ""){
                        $treePath =  $folder;
                    } else {
                        $treePath =  $treePath . '/' . $folder;
                    }
                    continue;
                } else {
                    $known_folders[$filepath] = 1;
                }

                if ($treePath == ""){
                    $treePath =  $folder;
                } else {
                    $treePath =  $treePath . '/' . $folder;
                }
                //Log::info($treePath);
                $treeview_tmp = '<li id="item_' . $m  . '" data-id="' . $treePath  . '">' . $hyphens . $folder  . '</li>';
                $treeview = $treeview . $treeview_tmp;
                $hyphens = $hyphens . '-';
                $m++;
            }
            $hyphens = "-";
            $treePath = "";
        }
        $treeview = $treeview . '</div>';

        return $treeview;
    }




}