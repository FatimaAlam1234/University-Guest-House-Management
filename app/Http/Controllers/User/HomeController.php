<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Room;
use App\Reservation;

class HomeController extends Controller
{
    /**
     * Get the home page.
     * 
     * @return view
     */
    public function index()
    {
        // query builder
        $rooms = DB::table('rooms')
            ->join('room_types', 'room_types.id', 'rooms.room_type_id')
            ->select(
                'rooms.id',
                'rooms.name',
                'rooms.photo',
                'room_types.name AS type',
                'room_types.price',
                'room_types.size',
                'room_types.capacity',
                'room_types.bed_type'
            )
            ->take(4)
            ->get();

        return view('user.home', ['rooms' => $rooms]);
    }

    /**
     * Get the rooms page.
     * 
     * @return view
     */
    public function rooms()
    {
        $rooms = DB::table('rooms')
            ->join('room_types', 'room_types.id', 'rooms.room_type_id')
            ->select(
                'rooms.id',
                'rooms.name',
                'rooms.photo',
                'room_types.name AS type',
                'room_types.price',
                'room_types.size',
                'room_types.capacity',
                'room_types.bed_type'
            )
            ->paginate(8);

        return view('user.pages.rooms', ['rooms' => $rooms]);
    }

    /**
     * Get the room specified by the user.
     * 
     * @return view
     */
    public function room($id, $checkIn, $checkOut)
    {
        $room = DB::table('rooms')
            ->join('room_types', 'room_types.id', 'rooms.room_type_id')
            ->select(
                'rooms.id',
                'rooms.name',
                'rooms.photo',
                'room_types.name AS type',
                'room_types.price',
                'room_types.size',
                'room_types.capacity',
                'room_types.bed_type'
            )
            ->where('rooms.id', $id)
            ->paginate(8);

        return view('user.pages.room', ['room' => $room, 'checkIn' => $checkIn, 'checkOut' => $checkOut]);
    }

    /**
     * Get the room specified by the user.
     * 
     * @return view
     */
    public function checkRooms(Request $request)
    {
        $request->validate([
            'check_in' => 'required|date',
            'check_out' => 'required|date',
        ]);
        $time_from = $request->input('check_in');
        $time_to = $request->input('check_out');
        $Availablerooms = Room::whereNotIn('id', Reservation::select('room_id')
                ->where(function ($query) use ($time_from, $time_to) {
                    $query->where([['check_in', '<=', $time_from], [
                        'check_out', '>=', $time_from
                    ]])
                        ->orWhere([
                            ['check_in', '<=', $time_to],
                            ['check_out', '>=', $time_to]
                        ]);
                }))->get();
        return view('user.pages.rooms', ['rooms' => $Availablerooms, 'check_in_date' => $time_from, 'check_out_date' => $time_to]);
    }

    /**
     * Store the reservation to database.
     * 
     * @param Request $request
     * @param int $id
     * @return redirect
     */
    public function bookRoom(Request $request, $id)
    {

        $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email_address' => 'required|email',
            'phone_number' => 'required|numeric',
            'street_address' => 'required|string',
            'check_in' => 'required|date',
            'check_out' => 'required|date',
            'guests' => 'required|numeric|min:1',
            // 'rooms' => 'required|numeric|min:0',
        ]);
        $guest= DB::table('guests')->select('id')
        ->where([
            ['first_name', '=', $request->first_name,],
            ['last_name', '=', $request->last_name],
            ['email_address', '=', $request->email_address],
            ['phone_number', '=', $request->phone_number],
            ['street_address', '=', $request->street_address]
        ])->get();
        if ($guest->count()==0) {
            // Store the guest
            $request->validate([
                'email_address' => 'unique:guests,email_address'
            ]);
            $saveGuest = DB::table('guests')
                ->insert([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'email_address' => $request->email_address,
                    'phone_number' => $request->phone_number,
                    'street_address' => $request->street_address,
                ]);
            if (!$saveGuest) {
                return redirect()->back();
            }

            // Get the guest
            $guest = DB::table('guests')->select('id')
                ->where([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                ])
                ->get();
       
        }
        $GuestId =$guest[0]->id;
        $existReservation = DB::table('reservations')->select('room_id')
            ->where([['check_in','<=',$request->check_in],['check_out','>=',$request->check_in],['room_id','=',$id]])
            ->orWhere([['check_in','<=',$request->check_out],['check_out','>=',$request->check_out],['room_id','=',$id]]);
           
            if($existReservation->get()->count()>=1){
                return redirect()->route('user.room', ['id' => $id,'checkIn'=>$request->check_in,'checkOut'=>$request->check_out])
                ->with('error', 'Room is not available from'.$request->check_in.'to'.$request->check_out);  
            }
        // Save the reservation details
        $reservation = DB::table('reservations')
            ->insert([
                'guest_id' => $GuestId,
                'room_id' => $id,
                'check_in' => $request->check_in,
                'check_out' => $request->check_out,
                'guests' => $request->guests,
                // 'rooms' => $request->rooms,
            ]);

        // Redirect the user!
        if ($guest && $reservation) {
            return redirect()->route('user.room', ['id' => $id,'checkIn'=>$request->check_in,'checkOut'=>$request->check_out])->with('success', 'Room reserved successfully!');
        } else {
            return "hoh";
        }
    }

    /**
     * Get the about us page.
     * 
     * @return view
     */
    public function aboutUs()
    {
        return view('user.pages.about');
    }

    /**
     * Get the contact us page.
     * 
     * @return view
     */
    public function contactUs()
    {
        return view('user.pages.contact');
    }
}
