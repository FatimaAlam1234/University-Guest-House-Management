<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReservationService extends Model
{
    /**
     * Attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'reservation_id', 'service_id', 'no_of_times','service_charge'
    ];
}
