<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class login_tracking extends Model
{
    protected $table = 'login_tracking';

    protected $fillable = [
        'email', 'country', 'domain'
    ];

}
