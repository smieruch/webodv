<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class monitor extends Model
{
    protected $table = 'monitor';

    protected $fillable = [
        'cpu', 'mem', 'odvws_instances',
    ];

}
