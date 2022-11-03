<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = DB::table('rooms')
                    ->join('room_types', 'room_types.id', 'rooms.room_type_id')
                    ->select(
                        'rooms.id', 'rooms.name', 'rooms.amount', 'rooms.photo',
                        'room_types.name AS type', 'room_types.price', 'room_types.size',
                        'room_types.capacity', 'room_types.bed_type',
                    )
                    ->paginate(3);
        
        return $rooms;
    }

    public function all()
    {
        $rooms = DB::table('rooms')
                    ->join('room_types', 'room_types.id', 'rooms.room_type_id')
                    ->select(
                        'rooms.id', 'rooms.name', 'rooms.amount', 'rooms.photo',
                        'room_types.name AS type', 'room_types.price', 'room_types.size',
                        'room_types.capacity', 'room_types.bed_type',
                    )
                    ->get();
        
        return $rooms;
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
            'name' => 'required|string|max:255',
            'room_type_id' => 'required|numeric',
            'amount' => 'required|numeric|min:0',
            'photo' => 'nullable|string',
        ]);

        // handles photo upload
        if ( $request->photo != null ) {
            $extension = explode('/', $request->photo)[1];
            $extension = explode(';', $extension)[0];
            $photo = time() . '.' . $extension;

            \Image::make($request->photo)->save(public_path('img/uploads/rooms/') . $photo);
            
            $request->merge([
                'photo' => $photo,
            ]);
        }

        $room = DB::table('rooms')
            ->insert([
                'name' => $request->name,
                'room_type_id' => $request->room_type_id,
                'amount' => $request->amount,
                'photo' => $request->photo,
            ]);

        return $room;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $room = Room::find($id);

        return $room;
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
            'name' => 'required|string',
            'room_type_id' => 'required|numeric',
            'amount' => 'required|numeric|min:0',
            'photo' => 'sometimes|string',
        ]);

        $room = Room::find($id);

        $roomPhoto = $room->photo;

        // handles photo update
        if ( $roomPhoto != $request->photo ) {
            $extension = explode('/', $request->photo)[1];
            $extension = explode(';', $extension)[0];
            $photo = time() . '.' . $extension;
            
            \Image::make($request->photo)->save(public_path('img/uploads/rooms/') . $photo);

            // delete the previous picture
            if ( file_exists(public_path('img/uploads/rooms/') . $photo) ) {
                @unlink(public_path('img/uploads/rooms') . $photo);
            }

            $room->photo = $photo;
            $room->save();
        }

        $room->name = $request->name;
        $room->room_type_id = $request->room_type_id;
        $room->amount = $request->amount;
        $room->save();

        return $room;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $room = DB::table('rooms')
                    ->where('id', $id)
                    ->delete();

        return $room;
    }
}
