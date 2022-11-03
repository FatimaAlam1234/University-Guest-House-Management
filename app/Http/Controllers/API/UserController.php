<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = DB::table('users')
                ->paginate(3);

        return $users;
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
            'username' => 'required|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email_address' => 'required|email|unique:users,email_address',
            'password' => 'required|string|min:4',
        ]);

        $user = DB::table('users')->insert([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email_address' => $request->email_address,
            'password' => Hash::make($request->password),
        ]);

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $username
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = DB::table('users')
                ->where('id', $id)
                ->select('id', 'username', 'first_name', 'last_name', 'email_address', 'level', 'profile_picture', 'background_picture')
                ->get();

        return $user;
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
            'username' => 'required|string|max:255|unique:users,username,'.$request->id,
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email_address' => 'required|email|unique:users,email_address,'.$request->id,
            'password' => 'sometimes|nullable|min:4',
            'level' => 'sometimes|string',
            'profile_picture' => 'sometimes|nullable|string',
            'background_picture' => 'sometimes|nullable|string',
        ]);

        $user = User::find($id);
        
        $userProfilePicture = $user->profile_picture;
        $userBackgroundPicture = $user->background_picture;
        
        // handles profile picture update
        if ( $request->profile_picture != null && $request->profile_picture != $userProfilePicture ) {
            // extract the extension and rename the image with unique name.
            $extension = explode('/', $request->profile_picture)[1];
            $extension = explode(';', $extension)[0];
            $profile_picture = time() . '.' . $extension;
            
            // upload the image
            \Image::make($request->profile_picture)->save(public_path('img/uploads/profile_pictures/') . $profile_picture);

            // find the stored image in db and unlink it.
            if (file_exists(public_path('img/uploads/profile_pictures/') . $userProfilePicture)) {
                @unlink(public_path('img/uploads/profile_pictures/') . $userProfilePicture);
            }
            
            $user->profile_picture = $profile_picture;
            $user->save();
        }
        
        // handles background picture update
        if ( $request->background_picture != null && $request->background_picture != $userBackgroundPicture ) {
            // extract the extension and rename the image with unique name.
            $extension = explode('/', $request->background_picture)[1];
            $extension = explode(';', $extension)[0];
            $background_picture = time() . '.' . $extension;
            
            // upload the image
            \Image::make($request->background_picture)->save(public_path('img/uploads/background_pictures/') . $background_picture);

            // find the stored image in db and unlink it.
            if (file_exists(public_path('img/uploads/background_pictures') . $userBackgroundPicture)) {
                @unlink(public_path('img/uploads/background_pictures') . $userBackgroundPicture);
            }
            
            $user->background_picture = $background_picture;
            $user->save();
        }
        
        if ( $request->password != "" ) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        $user->username = $request->username;
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email_address = $request->email_address;
        $user->level = $request->level;
        $user->save();

        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = DB::table('users')
                ->where('id', $id)
                ->delete();
                
        return $user;
    }
}
