<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WsodvAvailable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('wsodv-manager')->create('wsodv_available', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url');
            $table->string('port');
            $table->string('proxy');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('wsodv-manager')->drop('wsodv_available');
    }
}
