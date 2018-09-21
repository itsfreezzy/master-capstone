<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caterer extends Model
{
    use SoftDeletes;
    protected $table = 'tblcaterers';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function emails()
    {
        return $this->hasMany('App\CatEmail', 'catererid');
    }

    public function contactpersons()
    {
        return $this->hasMany('App\CatContactPerson', 'catererid');
    }

    public function contacts()
    {
        return $this->hasMany('App\CatContact', 'catererid');
    }
}
