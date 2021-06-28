<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Auth;
//use \App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class CreateDummyUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:dummyUser';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a user, e.g. to be used in auth.basic.one';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Log::info("CreateDummyUser");

        $email = "baggi@gmx.li";
        $name = "woody";
        $passwd = "buzz";

        //check if user does not exists
        if (DB::table("users")->where('email', '=', $email)->doesntExist()) {

            Log::info("dummy@dummy.org does not exist");
            //create user and login
            factory(\App\User::class)->create([
                'email' => $email,
                'name' => $name,
                'first_name' => "woo",
                'last_name' => "dy",
                'password' => bcrypt($passwd),
                'project' => "basic_auth",
                'private_workspace' => '/basic_auth',
            ]);

        } else {
            Log::info("dummy@dummy.org already exists");
        }



    }
}
