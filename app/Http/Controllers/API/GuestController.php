<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $guests = DB::table('guests')
                        ->paginate(5);
        
        return $guests;
    }

    public function all()
    {
        $guests = DB::table('guests')
                        ->get();

        return $guests;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Validates the requests ...
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email_address' => 'required|email|unique:guests',
            'phone_number' => 'required|regex:/(0)[0-9]/|not_regex:/[a-z]/|max:10',
            'street_address' => 'required|string',
        ]);

        $guest = DB::table('guests')
                        ->insert([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'email_address' => $request->email_address,
                            'phone_number' => $request->phone_number,
                            'street_address' => $request->street_address,
                        ]);

        return $guest;
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email_address' => 'required|email|unique:guests,email_address,'.$request->id,
            'phone_number' => 'required|regex:/(0)[0-9]/|not_regex:/[a-z]/|max:10',
            'street_address' => 'required|string',
        ]);

        $guest = DB::table('guests')
                        ->where('id', $id)
                        ->update([
                            'first_name' => $request->first_name,
                            'last_name' => $request->last_name,
                            'email_address' => $request->email_address,
                            'phone_number' => $request->phone_number,
                            'street_address' => $request->street_address,
                        ]);

        return $guest;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = DB::table('guests')
                    ->where('id', $id)
                    ->delete();
        
        return $user;
    }
}
