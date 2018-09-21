<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Reservation extends Model
{
    use SoftDeletes;
    protected $table = 'tblreservations';
    
    /**
    * The attributes that should be mutated to dates.
    *
    * @var array
    */
   protected $dates = ['deleted_at'];

    public function reservationinfo()
    {
        return $this->hasOne('App\ReservationInfo', 'reservationcodeid');
    }

    public function customer()
    {
        return $this->belongsTo('App\Customer', 'customercode', 'code');
    }

    public function eventequipment()
    {
        return $this->hasMany('App\EventEquipment', 'reservationcode', 'code');
    }

    protected $fillable = ['id', 'reservationcode', 'reservationinfoid', 'customercode', 'datefiled', 'status', 'eventtitle', 'eventdate', 'eventorganizer', 'eocontactno', 'eoemail', 'approvedby', 'isDone', 'dateMarkedAsDone', 'hasContract', 'contractComment', 'hasBilling', 'billingComment', 'created_at', 'updated_at', 'deleted_at'];
}
