<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationInfo extends Model
{
    protected $table = 'tblreservationinfo';

    public function reservation()
    {
        return $this->belongsTo('App\Reservation', 'reservationinfoid');
    }
}
