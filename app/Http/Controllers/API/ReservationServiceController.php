<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\ReservationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationServiceController extends Controller
{
   public function addService(Request $request){
      // dd($request->reservation_id);
    $request->validate([
        'reservation_id' => 'required|numeric',
        'service_id' => 'required|numeric',
        'no_of_times' => 'required|numeric',
        'service_charges' => 'required|numeric',
    ]);
    $existReservationService = ReservationService::where([['reservation_id','=',$request->reservation_id],
                                ['service_id','=',$request->service_id]])->first();
    if($existReservationService!= null){
        $existReservationService->no_of_times =  $existReservationService->no_of_times + $request->no_of_times;
        $existReservationService->service_charges =  $existReservationService->service_charges + $request->service_charges;
        $existReservationService->save();
    }else{
        $reservationService = DB::table('reservation_services')->insert([
            'reservation_id' => $request->reservation_id,
            'service_id' => $request->service_id,
            'no_of_times' => $request->no_of_times,
            'service_charges' => $request->service_charges,
        ]);

    }
   }
}
