<?php 
namespace App\webodvTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\visit_tracking;
use Illuminate\Support\Facades\DB;
use Location;



trait trait_visit_tracking
{
    public function track_visitors(Request $request)     
    {               

/* $download_tracking->email = $create_download["email"]; */
        /* $download_tracking->ws_message = json_encode($request->all()); */
        /* $download_tracking->service = "EMODnet_chem"; */
        /* $download_tracking->save(); */

        Log::info("track_visitors");


        //$visit_tracking = new visit_tracking;

        //get ip of visitor
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
        
        //get day of 
        $today = date('Y-m-d');
        //Log::info("today=".$today);

        //check also domain
        
        $visitors = DB::table('visit_tracking')
            ->whereDate('created_at', $today)
            ->where('ip',$ip)
            ->get();

        /* $url = url('/'); */
        /* Log::info("trait_visit_tracking"); */
        /* Log::info($url); */
        
        
        //if that visitor is here today for the first time
        //save in the db, otherwise not
        if (!$visitors->count()){
            Log::info("this is a new visit today");
            $visit_tracking = new visit_tracking;
            $visit_tracking->ip = $ip;
            $visit_tracking->country = $country;
            $visit_tracking->domain = url('/');
            $visit_tracking->save();
        } else {
            Log::info("this is not a new visit today");
        }

        /* foreach($visitors as $visitor){ */
        /*     Log::info($visitor->id . ', ' . $visitor->ip . ', ' . $visitor->created_at); */
        /* } */


        

        return ["success" => true];
    }
    
}


?>