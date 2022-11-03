<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $reservations = DB::table('reservations')
                            ->join('guests', 'guests.id', 'reservations.guest_id')
                            ->join('rooms', 'rooms.id', 'reservations.room_id')
                            ->select(
                                'reservations.id', 'reservations.guest_id', 'guests.first_name AS first_name', 'guests.last_name AS last_name',
                                'rooms.name AS room_name', 'reservations.room_id',
                                'reservations.check_in', 'reservations.check_out','reservations.guests')
                            ->where('reservations.checkedIn','=',0)
                            ->paginate(5);

        return $reservations;
    }

    /**
     * Get the list of check-in guests.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCheckInGuestsList()
    {
        $checkInGuests = DB::table('reservations')
                            ->join('guests', 'guests.id', 'reservations.guest_id')
                            ->join('rooms', 'rooms.id', 'reservations.room_id')
                            ->select(
                                'reservations.id', 'reservations.guest_id', 'guests.first_name AS first_name', 'guests.last_name AS last_name',
                                'rooms.name AS room_name', 'reservations.room_id',
                                'reservations.check_in', 'reservations.check_out','reservations.guests')
                            ->where([['reservations.checkedIn','=',1],['reservations.checkedOut','=',0]])
                            ->paginate(5);

        return $checkInGuests;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'guest_id' => 'required|numeric',
            'room_id' => 'required|numeric',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'guests' => 'required|numeric',
            // 'rooms' => 'required|numeric',
        ]);
            $max_guests = DB::table('rooms')->join('room_types','room_types.id','rooms.room_type_id')
                            ->select('room_types.capacity')->where('rooms.id','=',$request->room_id)->get();
                            $max_guests = $max_guests[0]->capacity;
            if($request->guests > $max_guests){
                return response()->json([
                    'status' => 'Error',
                    'msg' => 'Selected room have maximum capacity for '. $max_guests,
                    'code' => 400
                ], 400);
            }
            $existReservation = DB::table('reservations')
            ->where([['check_in','<=',$request->check_in],['check_out','>=',$request->check_in]])
            ->orWhere([['check_in','<=',$request->check_out],['check_out','>=',$request->check_out]]);

            $existReservation = $existReservation->where('room_id','=',$request->room_id)->get();
           if($existReservation->count()>=1){
            return response()->json([
                'status' => 'Error',
                'msg' => 'Selected room is not avaliblable from '.  $request->check_in  .' to '. $request->check_out ,
                'code' => 400
            ], 400);
           }
           else{
        $reservation = DB::table('reservations')
            ->insert([
                'guest_id' => $request->guest_id,
                'room_id' => $request->room_id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'guests' => $request->guests,
                // 'rooms' => $request->rooms,
            ]);

            return response()->json([
                'status' => 'Success',
                'msg' => 'Reservation created Successfully.',
                'cod' => 201
        ]);}
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'guest_id' => 'required|numeric',
            'room_id' => 'required|numeric',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'guests' => 'required|numeric',
            // 'rooms' => 'required|numeric',
        ]);
        $max_guests = DB::table('rooms')->join('room_types','room_types.id','rooms.room_type_id')
        ->select('room_types.capacity')->where('rooms.id','=',$request->room_id)->get();
        $max_guests = $max_guests[0]->capacity;
if($request->guests > $max_guests){
return response()->json([
'status' => 'Error',
'msg' => 'Selected room have maximum capacity for '. $max_guests,
'code' => 400
], 400);
}
        $reservation = DB::table('reservations')
                            ->where('id', $id)
                            ->update([
                                'guest_id' => $request->guest_id,
                                'room_id' => $request->room_id,
                                'check_in' => $request->check_in,
                                'check_out' => $request->check_out,
                                'guests' => $request->guests,
                                // 'rooms' => $request->rooms,
                            ]);

        return $reservation;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $reservation = DB::table('reservations')
                            ->where('id', $id)
                            ->delete();

        return $reservation;
    }
    /**
     *check in the guest.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkInGuest($id)
    {
        $reservation = DB::table('reservations')
                            ->where('id', $id)
                            ->update([
                                'checkedIn'=> 1
                            ]);

        return $reservation;
    }

    /**
     *check out the guest.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkOutGuest($id)
    {
        $date = date("Y-m-d");
        $checkOutGuest = DB::table('reservations')
                            ->where('id', $id)
                            ->update([
                                'checkedOut'=> 1,
                                'check_out'=>$date
                            ]);

        return $checkOutGuest;
    }

    
}
