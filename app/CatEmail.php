<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatEmail extends Model
{
    // use SoftDeletes;
    protected $table = 'tblcatemails';

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
