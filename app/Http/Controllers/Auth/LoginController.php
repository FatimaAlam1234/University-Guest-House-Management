<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /**
     * Initialize the class constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
    }
    
    /**
     * Get the login page.
     * 
     * @return View
     */
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    /**
     * Authenticates the user.
     * 
     * @return redirect
     */
    public function login(Request $request)
    {
        $request->validate([
            'email_address' => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email_address', $request->email_address)
                ->first();
        
        if ( $user ) {
            $credentials = $request->only(['email_address', 'password']);

            if (Auth::attempt($credentials)) {
                return redirect()->intended('dashboard')->with('status', 'Welcome back, ' . Auth::user()->username);
            } else {
                return redirect()->back()->with('error', 'The credentials did not match our records.');
            }
        } else {
            return redirect()->back()->with('error', 'The user is not registered yet.');
        }
    }
}
