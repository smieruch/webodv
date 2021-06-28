<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class removeCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes app specific file cache. Note to run this command as www-data, because of file permissions: sudo -u www-data php artisan remove:cache. Sebastian Mieruch';

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
        //
        Log::info('removeCache');
        //Cache::forget('odv_data');
        //Cache::forget('webodv_settings');
        Cache::flush();
    }
}
