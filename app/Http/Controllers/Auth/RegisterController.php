<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    /**
     * Get the register page.
     * 
     * @return view
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Store the newly created user.
     * 
     * @return redirect
     */
    public function register(Request $request)
    {
        // Validate the requests ...
        $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'email_address' => 'required|email|unique:users',
            'password' => 'required|string|min:4|confirmed',
            'password_confirmation' => 'required|string|min:4',
        ]);

        // Validation success then insert records to database ...
        DB::table('users')->insert([
            'username' => $request->username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email_address' => $request->email_address,
            'password' => Hash::make($request->password),
        ]);

        // Redirect the user to the login page ...
        return redirect()->route('login')->with('status', 'Account created successfully!');
    }
}
