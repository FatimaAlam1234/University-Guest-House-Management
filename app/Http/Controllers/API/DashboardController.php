<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Reservation;
use App\ReservationService;
use App\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function getguestHouseInfo(){
     $guests =DB::table('guests')->get()->count();
     $rooms = DB::table('rooms')->get()->count();
     $services = DB::table('services')->get()->count();
     $roomTypes = DB::table('room_types')->get()->count();
     $data=[
         'guests' =>$guests,
         'rooms' => $rooms,
         'services' => $services,
         'roomTypes' => $roomTypes
     ];
     return $data;
    }
} 
