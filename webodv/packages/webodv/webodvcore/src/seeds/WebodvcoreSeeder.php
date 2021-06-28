<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WebodvcoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->call(WsodvAvailable::class);
    }
}
