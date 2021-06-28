<?php 
namespace App\webodvTraits;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\login_tracking;
use Illuminate\Support\Facades\DB;
use Auth;
use Location;


trait trait_login_tracking
{
    public function track_logins(Request $request)     
    {               

/* $download_tracking->email = $create_download["email"]; */
        /* $download_tracking->ws_message = json_encode($request->all()); */
        /* $download_tracking->service = "EMODnet_chem"; */
        /* $download_tracking->save(); */

        Log::info("track_logins");
        //Log::info("Auth:".Auth::check());

        if (!Auth::check()){
            return ["success" => false];
        }

        //Log::info("track_logins 1");

        //$visit_tracking = new visit_tracking;

        //get ip of user
        //if it is behind a proxy
        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];  
            //Log::info("real ip = ".$ip);
        } else {
            $ip = $request->ip();
        }
        
        //Log::info("ip=".$ip);
        
        //log ip temporariliy in db
        $user = Auth::user();
        $user->ip = $ip;
        $user->save();

        
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

        $email = Auth::user()->email;
        
        $visitors = DB::table('login_tracking')
            ->whereDate('created_at', $today)
            ->where('email',$email)
            ->get();

        //if that visitor is here today for the first time
        //save in the db, otherwise not
        if (!$visitors->count()){
            Log::info("this is a new visit today");
            $login_tracking = new login_tracking;
            $login_tracking->email = $email;
            $login_tracking->country = $country;
            $login_tracking->domain = url('/');
            $login_tracking->save();
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