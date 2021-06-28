<?php

namespace App\webodvLibs;

use Illuminate\Support\Facades\Log;
use Auth;

class create_hummingbird_treeview {



    public function create(Array $data){


        $Xtime1 = microtime(true);

        //search for doubles test
        //$doubles_file = fopen("/var/www/html/webodv/storage/app/doubles.txt","a");
        
        
        //////////////////////////////////////
        //error_log(print_r($request->all(),1));
        $odv_file_path = $data['infile'];
        $linebreak = "";
        
        $outfile = $data['outfile'];
        $dialog = "";

        $mode = "full";

        $with_all = "true";
        
        $disableGroups="";
        ////////////////////////////////////

        $varnums = array();
        $varnums = array_pad($varnums,500,"");

        $data_group_names = array();
        //$data_group_names = array_pad($data_group_names,500,"");

        $data_group_nums = array();
        $data_group_nums = array_pad($data_group_nums,500,"");

        $data_group_nums_top_level = array();


        $varnames = array();
        $varnames = array_pad($varnames,500,"");

        $varnames_asso = array();
        $fullames_asso = array();

        $fullames = array();
        $fullnames = array_pad($varnames,500,"");

        $First_Num_For_Group = array();
        $First_Num_For_Group = array_pad($First_Num_For_Group,500,"");
        
        $variables_ok = false;
        $data_groups_ok = false;

        $anz_data_groups = 0;
        $anz_vars = 0;

        $vergeben = 0;

        $m = 0;
        $k = 0;
        $h = 1;
        $hx = 0;

        //get path
        $the_path = pathinfo($outfile,PATHINFO_DIRNAME);
        //Log::info("path:".$the_path);
        //check if path exist
        //mkdir
        if (!file_exists($the_path)){
            mkdir($the_path,0777,true);
        }
        $target_file = fopen($outfile,"w");
        if ($mode == "pseudo") {
            fwrite($target_file, '<div class="hummingbird-treeview-converter" data-height="470px" data-scroll="true">' . "\n");
            fwrite($target_file,'<li id="' . $dialog . 'var_0' . '" data-id="' . ''  . '">' . 'All' . '</li>' . "\n");
        }
        if ($mode == "full" && $with_all == "true") {
            fwrite($target_file,'<li><i class="fa fa-plus"></i> <label> <input id="' . $dialog . 'var_0' . '" data-id="0" type="checkbox" />' . ' All</label>' . $linebreak);
            fwrite($target_file,'<ul >' . $linebreak);
        }
        
        
        $tmp = "";
        if (file_exists($odv_file_path)) {
            $odv_file = file($odv_file_path);
            foreach ($odv_file as $value) {



                /////////////////////////////////////////////////////
                //////////  cut out variables start
                /////////////////////////////////////////////////////
        
                
                //find empty line, i.e. the end of the Variables
                if ($variables_ok == true) {
                    if (trim($value)  == '') {
                        $variables_ok = false;
                        error_log("m gleich: " . $m . " value= " . $value . " variables_ok= " . $variables_ok);
                    }
                }

                //find position of Variables start
                if (preg_match("/\[Variables\]/",$value)) {
                    $variables_ok = true;
                    error_log("m gleich: " . $m . " value= " . $value . " variables_ok= " . $variables_ok);
                }

                if ($variables_ok == true) {
                    if ($k>0) {                //jump over first line
                        //cut out numbers
                        //remove leading and trailing 0's
                        $regex_count = preg_match_all('/[^0].*?(?=\s=\s)/',$value,$tmp);
                        //error_log("numbers: " . print_r($tmp,1));
                        $varnums[$k] = $tmp[0][0];                        
                        //get varname
                        //1. cut out until first ;
                        $regex_count = preg_match_all('/(?<==\s)[^;]+/',$value,$tmp);
                        $varnames[$k] = $tmp[0][0];
                        //2. cut out before last _BOTTLE|_FISH|_PUMP|_UWAY
                        $regex_count = preg_match_all('/(?<==\s).+(?=_BOTTLE|_FISH|_PUMP|_UWAY)/',$value,$tmp);
                        //if the varname ends with _BOTTLE etc
                        if ($regex_count>0) {
                            $varnames[$k] = $tmp[0][0];
                        }
                        //error_log("varnames: " . print_r($tmp,1));


                        //error_log($varnums[$k] . " " . $varnames[$k]);
                        //if this variable has different types
                        if ($regex_count>0) {
                            if (isset($varnames_asso[$varnames[$k]])) {
                                $varnames_asso[$varnames[$k]] = $varnames_asso[$varnames[$k]] . "|" . $varnums[$k];
                            } else {
                                $varnames_asso[$varnames[$k]] = $varnums[$k];
                                //remember only the first variable id to connect with the group
                                //error_log("baggi: " . print_r($varnums[$k][0],1));
                                $First_Num_For_Group[(string) $varnums[$k]] = $varnames[$k];
                                $regex_count_x = preg_match_all('/[^;]+$/',$value,$tmp_x);
                                $fullnames_asso[$varnames[$k]] = $tmp_x[0][0];
                            }
                       } else {
                            if (isset($varnames_asso[$varnames[$k]])) {
                                //geotraces, i.e. put same names together
                                $varnames_asso[$varnames[$k]] = $varnames_asso[$varnames[$k]] . "," . $varnums[$k];
                                //write filename into file and the numbers
                                //fwrite($doubles_file,$odv_file_path . " " . $varnames_asso[$varnames[$k]] . " " . $varnames[$k] . "\n");
                                //Log::info("double detected !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
                                //emodnet doubled names, but still different vars
                                //$varnames_asso[$varnames[$k].'-'] = $varnums[$k];
                            } else {
                                $varnames_asso[$varnames[$k]] = $varnums[$k];
                                //remember only the first variable id to connect with the group
                                $First_Num_For_Group[(string) $varnums[$k]] = $varnames[$k];
                                $regex_count_x = preg_match_all('/[^;]+$/',$value,$tmp_x);
                                $fullnames_asso[$varnames[$k]] = $tmp_x[0][0];
                            }
                        }
                            
                        /* error_log($varnums[$k] . " " . $varnames[$k]); */
                        /* if (isset($First_Num_For_Group[(string) $varnums[$k]])) { */
                        /*     error_log($varnums[$k] . " " .  $First_Num_For_Group[(string) $varnums[$k]]); */
                        /* } else { */
                        /*     error_log("First_Num_For_Group not set!"); */
                        /* } */
                        
                    }
                    $k++;
                }

                
                //find position of Data Groups start
                if (preg_match("/\[Data Groups\]/",$value)) {
                    $data_groups_ok = true;
                    //error_log("m gleich: " . $m . " value= " . $value . " data_groups_ok= " . $data_groups_ok);
                } else {
                    //change $value
                    /* if ($variables_ok == true) {                         */
                    /*     Log::info("variables ok"); */
                    /*     Log::info("k = " . $k); */
                    /*     Log::info("no Data Groups"); */
                    /*     Log::info($value); */
                    /* } */
                }

                if ($data_groups_ok) {
                    if ($m>0 && !empty(trim($value))) {                //jump over first line
                        //error_log($value);
                        //cut out group name
                        $regex_count = preg_match_all('/(?<=\s).*(?=\s=)/',$value,$tmp);                        
                        /* Log::info($value); */
                        /* Log::info($tmp); */
                        //error_log($tmp[0][0]);
                        $group_name = $tmp[0][0];
                        //cut out variables id's
                        $regex_count = preg_match_all('/(?<==\s).+[0-9]/',$value,$tmp);
                        //convert string $tmp[0][0] to array
                        //error_log("varid= ". $tmp[0][0]);
                        //remove any whitespace
                        $variable_ids = explode(", ",$tmp[0][0]);
                        //error_log(print_r($variable_ids,1));
                        $children = count($variable_ids);
                        //$data_group_names
                        //create treeview
                        $hyphen = '-';
                        if ($group_name != '<TopLevelGroup>') {
                            if ($mode == "pseudo") {
                                fwrite($target_file,'<li  id="' . $dialog . 'var_' . $h . '" data-id="' . ''  . '">' . '-' . $group_name . '</li>' . "\n");
                                $h++;
                                $hyphen = '--';
                            }
                            if ($mode == "full") {
                                $expandable = '<i class="fa fa-plus"></i>&nbsp;';
                                $child_exist = "";
                                $data_id = "";
                                fwrite($target_file, '<li >' . $expandable . '<label><input class="' . $child_exist . '" id="' . $dialog . 'var_' . $h . '" data-id="' . $data_id . '" type="checkbox" />' . ' ' . $group_name . '</label>' . $linebreak);
                                $h++;
                            }
                        
                            //error_log($group_name);
                            //for full mode open <ul> if children exist

                            if ($mode == "full" && $children > 0 ) {
                                fwrite($target_file,"<ul>" . $linebreak);
                            }
                        }
                        foreach($variable_ids as $id) {
                            //note that $First_Num_For_Group is an associative array with strings as indices
                            //thus, no whitespace in number string
                            //error_log("id=" . $id);
                            if (!empty($First_Num_For_Group[(string) $id])) {
                                //error_log("First_Num_For_Group= " . $First_Num_For_Group[(string) $id]);
                                if ($mode == "pseudo") {
                                    fwrite($target_file,'<li  id="' . $dialog . 'var_' . $h . '" data-id="' . $varnames_asso[$First_Num_For_Group[(string) $id]]  . '">' . $hyphen . $First_Num_For_Group[(string) $id] . '</li>' . "\n");
                                    $h++;
                                }
                                if ($mode == "full") {
                                    $expandable = "";
                                    $child_exist = "hummingbird-end-node";
                                    $data_id = $varnames_asso[$First_Num_For_Group[(string) $id]];
                                    fwrite($target_file, '<li >' . $expandable . '<label><input class="' . $child_exist . '" id="' . $dialog . 'var_' . $id . '" data-id="' . $data_id . '" type="checkbox" />' . ' ' . $First_Num_For_Group[(string) $id] . '</label></li>' . $linebreak);
                                    $h++;
                                }
                            }                         
                        }

                        
                        if ($mode == "full") {
                            if ($group_name != '<TopLevelGroup>') {
                                if ($children > 0 ) {
                                    fwrite($target_file,"</ul>" . $linebreak);
                                }
                                fwrite($target_file,"</li>" . $linebreak);
                            }                            
                        }                            
                    }
                    $m++;
                }
                

            }
            
            //if no data groups exist:
            if (!$data_groups_ok){
                Log::info("no data groups");
                $expandable = "";
                $child_exist = "hummingbird-end-node";
                $dialog = "";
                $linebreak = "";
                //Log::info(print_r($varnames_asso,1));
                foreach ($varnames_asso as $key => $val){
                    
                    //Log::info($key . " " . $val);
                    //fwrite($target_file,'<li  id="var_' . $val . '" data-id="' . $val  . '">' . '-' . $key . '</li>' . "\n");
                    fwrite($target_file, '<li >' . $expandable . '<label><input class="' . $child_exist . '" id="' . $dialog . 'var_' . $val . '" data-id="' . $val . '" type="checkbox" />' . ' ' . $key . '</label></li>' . $linebreak);
                    
                }

                
                //foreach($variable_ids as $id) {
                    //note that $First_Num_For_Group is an associative array with strings as indices
                    //thus, no whitespace in number string
                    //error_log("id=" . $id);

                    //Log::info($id);
                    
                    /* if (!empty($First_Num_For_Group[(string) $id])) { */
                    /*     //error_log("First_Num_For_Group= " . $First_Num_For_Group[(string) $id]); */
                    /*     if ($mode == "pseudo") { */
                    /*         fwrite($target_file,'<li  id="' . $dialog . 'var_' . $h . '" data-id="' . $varnames_asso[$First_Num_For_Group[(string) $id]]  . '">' . $hyphen . $First_Num_For_Group[(string) $id] . '</li>' . "\n"); */
                    /*         $h++; */
                    /*     } */
                    /*     if ($mode == "full") { */
                    /*         $expandable = ""; */
                    /*         $child_exist = "hummingbird-end-node"; */
                    /*         $data_id = $varnames_asso[$First_Num_For_Group[(string) $id]]; */
                    /*         fwrite($target_file, '<li >' . $expandable . '<label><input class="' . $child_exist . '" id="' . $dialog . 'var_' . $id . '" data-id="' . $data_id . '" type="checkbox" />' . ' ' . $First_Num_For_Group[(string) $id] . '</label></li>' . $linebreak); */
                    /*         $h++; */
                    /*     } */
                    /* }                          */
                
                //}
            }
            //error_log(print_r($fullnames_asso,1));
            
        }

        if ($mode == "pseudo") {
            fwrite($target_file,'</div>');
        }
        if ($mode == "full" && $with_all == "true") {
            fwrite($target_file,'</ul></li>');
        }
        fclose($target_file);

        //sleep(2);

        //fclose($doubles_file);
        
        error_log("time= ");
        error_log(microtime(true)-$Xtime1);


        
        return json_encode("success");
    }



}