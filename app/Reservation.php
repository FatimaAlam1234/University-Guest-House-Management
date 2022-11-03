<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    /**
     * Attributes that are mass assignable.
     * 
     * @var array
     */
    protected $fillable = [
        'guest_id', 'room_id', 'check_in', 'check_out', 'guests'
    ];
}
