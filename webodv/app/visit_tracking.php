<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class visit_tracking extends Model
{
    protected $table = 'visit_tracking';

    protected $fillable = [
        'ip', 'country', 'domain',
    ];

}
