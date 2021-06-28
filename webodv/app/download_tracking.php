<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class download_tracking extends Model
{
    protected $table = 'download_tracking';

    protected $fillable = [
        'email', 'ws_message', 'service', 'country', 'domain'
    ];
}
