<?php 
namespace App\webodvTraits;
use Illuminate\Support\Facades\Log;

trait trait_get_branch
{
    public function getBranch()     
    {               

        /* $branch_array = explode('.',url()->current()); */
        /* Log::info("service_branch"); */
        /* Log::info(print_r($matches,1)); */
        /* $num = getenv('branch_num'); //default is 3 */
        /* $branch = $branch_array[$num]; */


        //$branch_array = preg_match('/\/\/(.*?)\./',url()->current(),$matches);
        ////Log::info("service_branch");
        ////Log::info(print_r($matches,1));
        ////$num = getenv('branch_num'); //default is 3
        //$branch = $matches[1];

        //new: now defined in docker-compose
        $branch = config('webodv.settings_name');
        //Log::info("branch=".$branch);
        //Log::info(config('webodv.settings_name'));

        return $branch;
    }
    
}


?>