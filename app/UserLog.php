<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserLog extends Model
{
    protected $table = 'userlog';
    public $timestamps = false;

    protected $fillable = [
        'userid', 'date', 'action'
    ];
}
