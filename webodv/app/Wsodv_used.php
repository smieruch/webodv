<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wsodv_used extends Model
{

    //the DB
    protected $connection = 'wsodv-manager';


    //the table name
    protected $table = 'wsodv_used';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url', 'port', 'proxy', 'permanent', 'mode', 'user', 'pid', 'service', 'dataset'
    ];
    //
}
