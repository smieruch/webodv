<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class private_workspaces extends Model
{
    protected $table = 'private_workspaces';

    protected $fillable = [
        'email', 'name'
    ];

}
