<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    // protected $guard = 'customer';
    protected $table = 'tblcustomers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'type', 'name', 'tinnumber', 'contactnumber', 'username', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    
    protected $dates = ['deleted_at'];

    public function reservation()
    {
        return $this->hasMany('App\Reservation', 'customercode', 'code');
    }
}
