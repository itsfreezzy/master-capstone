<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipment extends Model
{
    use SoftDeletes;
    protected $table = 'tblequipments';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function eventequipment()
    {
        return $this->hasMany('App\EventEquipment', 'equipmentcode');
    }
}
