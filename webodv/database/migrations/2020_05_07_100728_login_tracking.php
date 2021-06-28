<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LoginTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('login_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('country');
            $table->string('domain');
            $table->timestamps();
        });
        
    }
            /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('login_tracking');
    }

    
}
