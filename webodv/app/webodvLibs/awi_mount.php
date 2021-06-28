<?php

namespace App\webodvLibs;

use Illuminate\Support\Facades\Log;
use Auth;
use Illuminate\Support\Facades\Storage;
use App\webodvLibs\wsODV_manager;
use App\webodvLibs\webodv_load_settings;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use ZipArchive;


class awi_mount {



    public function mount_home(){

        
        
        $webodv_load_settings = new webodv_load_settings();
        $webodv_settings = $webodv_load_settings->load(['branch' => '']);

        
        if ($webodv_settings["upload"]){
            $user = Auth::user();

            /* if ($user->email != "sebastian.mieruch@awi.de"){ */
            /*     abort(401); */
            /* } */

            //$Path_to_odv_data = getenv('path_to_odv_data').'/'.$user->email . '/' . getenv('path_to_odv_data_2');
            //Log::info($Full_path);
            //create folder if not existing
            /* if (!Storage::exists($Path_to_odv_data)){ */
            /*     Storage::makeDirectory($Path_to_odv_data); */
            /* } */


            session(["private_workspace" => $user->name]);
            
            //mount awi home dir
            if ($webodv_settings["awi_mount"]){

                

                //---------------testing----------------------
                //$username = "smieruch";
                //$password = "xxxxxxxx";
                /* $msg = 'sudo -u root -H mount -t cifs //smb.isibhv.dmawi.de/home/gphs1/' . $username  . ' /var/www/html/webodv/storage/app/'. getenv('path_to_odv_data') .'/' . $user->email  . ' -o username=' . $username . ',password='. $password . ',uid=6542,gid=33,vers=3.0,dir_mode=0775,file_mode=0775'; */
                /* $msg2 = 'sudo -u woody mkdir /var/www/html/webodv/storage/app/'.getenv('path_to_odv_data').'/' . $user->email . '/webodv'; */
                /* $msg = $msg . '; ' . $msg2; */
                /* //Log::info($msg); */
                /* shell_exec($msg); */
                //---------------testing----------------------

                //get kerberos ticket
                try{
                    $client = new Client(['verify' => false]); //GuzzleHttp\Client
                    $tmppath = storage_path('app/local');
                    $tmpfile = tempnam($tmppath,'krest_');
                    //
                    $url = 'https://krest.awi.de/krbtickets/new';
                    $krb_request = $client->request('GET', $url, [
                        'headers' => [
                            'Authorization' => 'Token ' . session('access_token')
                        ],
                        'sink' => $tmpfile
                    ]);
                    //
                    //unzip ticket 
                    $zip = new ZipArchive;
                    if ($zip->open($tmpfile) === TRUE) {
                        $zip->extractTo(storage_path('app/local'));
			$zip->close();
                        Log::info('ok');
                    } else {
                        Log::info('failed');
                    }
                    //rename
                    //overwrite because Ticket has lifetime
                    //if (!file_exists('/tickets/krb5cc_'.$user->uid)){
                    //rename(storage_path('app/local/'.$user->name.'.ccache'),'/tickets/krb5cc_'.$user->uid);
                    //copy file with owner $user->name and permissions 600
                    $msg = 'sudo -u ' . $user->name . ' cp ' . storage_path('app/local/'.$user->name.'.ccache') . ' /tickets/krb5cc_'.$user->uid;
                    Log::info($msg);
                    shell_exec($msg);               
                    $msg = 'sudo -u ' . $user->name . ' chmod 600 ' . ' /tickets/krb5cc_'.$user->uid;
                    Log::info($msg);
                    shell_exec($msg);
                    //}
                    //create user
                    $user_exists = shell_exec('id -u '.$user->uid);
                    if ($user_exists == ""){
                        shell_exec('sudo -u root -H groupadd user --gid 1000');
                        //use the homedir of woody
                        //some configs still needed with group 270, can we use group 1000?
                        shell_exec('sudo -u root useradd -u ' . $user->uid . ' -d /home/woody -s /bin/bash ' . $user->name . ' -g 1000');
                    }

                    //priv workspace                                                                                                                                                          
                    session(["private_workspace" => $user->name]);
                    
                    //mount
                    /* $msg = 'sudo -u root -H mount -t cifs //smb.isibhv.dmawi.de/home/gphs1/' . $user->name  . ' /var/www/html/webodv/storage/app/'. getenv('path_to_odv_data') .'/' . $user->email  . ' -o ' . 'sec=krb5,uid='.$user->uid.',gid=1000,vers=3.0,dir_mode=0775,file_mode=0775'; */
                    /* $msg2 = 'sudo -u woody mkdir /var/www/html/webodv/storage/app/'.getenv('path_to_odv_data').'/' . $user->email . '/webodv'; */
                    /* $msg = $msg . '; ' . $msg2; */
                    /* Log::info($msg); */
                    /* shell_exec($msg); */

                    
                }
                catch (\GuzzleHttp\Exception\ClientException $e) {
                    abort(401);
                }
                
                
            }
        }
    }
    

    public function umount_home(){

        //$wsodv_manager = new wsODV_manager();
        //$wsodv_manager->kill();

        $user = Auth::user();
        $msg = 'sudo -u root -H umount /var/www/html/webodv/storage/app/odv_data/' . $user->email;
        //Log::info($msg);
        //shell_exec($msg);

    }

}