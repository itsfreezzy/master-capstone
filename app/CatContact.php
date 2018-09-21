<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatContact extends Model
{
    // use SoftDeletes;
    protected $table = 'tblcatcontacts';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    // protected $dates = ['deleted_at'];
    
    public function caterer()
    {
        return $this->belongsTo('App\Caterer', 'catererid');
    }
}
