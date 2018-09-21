<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EventEquipment extends Model
{
    protected $table = 'tbleventequipments';

    public function reservation()
    {
        return $this->belongsToMany('App\Reservation', 'reservationcode');
    }

    public function equipment()
    {
        return $this->belongsToMany('App\Equipment', 'equipmentcode');
    }
}
