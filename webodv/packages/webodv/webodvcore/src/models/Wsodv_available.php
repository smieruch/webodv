<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wsodv_available extends Model
{

    //the DB
    protected $connection = 'wsodv-manager';

    //the table name
    protected $table = 'wsodv_available';


     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'url', 'port', 'proxy',
    ];


    public $timestamps = false;

}
