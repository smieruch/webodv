<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class WsodvUsed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('wsodv-manager')->create('wsodv_used', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('url');
            $table->string('port');
            $table->string('proxy');
            $table->boolean('permanent');
            $table->string('mode');          //single or multi
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
        Schema::connection('wsodv-manager')->drop('wsodv_used');
    }
}
