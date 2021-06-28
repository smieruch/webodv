<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DeleteMigrationsDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:migrationDB';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes the wsodv migrations from the migrations DB to allow a distributed webODV system, which is using the same user DB to create internal (Docker) wsodv DBs.';

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
        Log::info("DeleteMIgrationsDB");
        $mig = DB::table('migrations')->where('migration','2020_01_21_125050_add_user_pid_to_wsodv_used')->delete();
        //Log::info("mig=");
        //Log::info(print_r($mig,1));
        $mig = DB::table('migrations')->where('migration','2019_11_16_091758_wsodv_used')->delete();
        $mig = DB::table('migrations')->where('migration','2019_11_16_091752_wsodv_available')->delete();
    }
}
