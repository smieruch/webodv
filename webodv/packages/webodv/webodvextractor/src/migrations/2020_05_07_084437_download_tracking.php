<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DownloadTracking extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('download_tracking', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->text('ws_message');
            $table->string('service');
            $table->string('domain');          
            $table->string('country');
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
        Schema::drop('download_tracking');
    }
}
