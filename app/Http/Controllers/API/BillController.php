<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Reservation;
use App\ReservationService;
use App\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BillController extends Controller
{
    public function generateBill($id){
     $reservationBill = Reservation :: where('id','=',$id)->first();
     $services = DB::table('reservation_services')->join('services','services.id','reservation_services.service_id')
                    ->select('reservation_services.id','services.name','services.description',
                    'reservation_services.no_of_times','reservation_services.service_charges')->where('reservation_id','=',$id)->get();
     $guest = DB::table('guests')->where('id','=',$reservationBill->guest_id)->first();
     $room = Room::where('id','=',$reservationBill->room_id)->first();
     $check_out_date=date_create($reservationBill->check_out);
     $check_out_date->modify('+1 day');
     $check_in_date=date_create($reservationBill->check_in);
     $day_of_stay = date_diff($check_in_date,$check_out_date)->format("%a");
     $room_amount = $room->amount * ($day_of_stay +0);
     $total_service_charge = DB::table('reservation_services')->where('reservation_id','=',$id)->sum('service_charges');
     $data=[
         'reservation'=> $reservationBill,
         'services'=>$services,
         'guest'=>$guest,
         'room'=>$room,
         'day_of_stay'=>$day_of_stay,
         'room_amount'=>$room_amount,
         'total_service_charges'=>$total_service_charge
     ];
     return $data;
    }
} 
