<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $types = DB::table('room_types')
            ->paginate(5);

        return $types;
    }

    public function all()
    {
        $types = DB::table('room_types')
            ->get();
    
        return $types;
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
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'size' => 'required|numeric|min:0',
            'capacity' => 'required|numeric|min:0',
            'bed_type' => 'required|string'
        ]);

        $type = DB::table('room_types')
            ->insert([
                'name' => $request->name,
                'price' => $request->price,
                'size' => $request->size,
                'capacity' => $request->capacity,
                'bed_type' => $request->bed_type,
            ]);

        return $type;
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
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'size' => 'required|numeric|min:0',
            'capacity' => 'required|numeric|min:0',
            'bed_type' => 'required|string'
        ]);

        $type = DB::table('room_types')
            ->where('id', $id)
            ->update([
                'name' => $request->name,
                'price' => $request->price,
                'size' => $request->size,
                'capacity' => $request->capacity,
                'bed_type' => $request->bed_type,
            ]);

        return $type;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = DB::table('room_types')
                    ->where('id', $id)
                    ->delete();

        return $type;
    }
}
