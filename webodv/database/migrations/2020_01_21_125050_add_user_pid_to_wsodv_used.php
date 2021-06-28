<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserPidToWsodvUsed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('wsodv-manager')->table('wsodv_used', function (Blueprint $table) {
            $table->string('user')->after('mode');
            $table->string('pid')->after('user');
            $table->string('service')->after('pid')->nullable();
            $table->text('dataset')->after('service')->nullable();	            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('wsodv-manager')->table('wsodv_used', function (Blueprint $table) {
            $table->dropColumn(['user','pid','service','dataset']);
        });
    }
}
