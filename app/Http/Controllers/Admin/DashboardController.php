<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Initialize the class constructor.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
     * Get the dashboard page.
     * 
     * @return View
     */
    public function index()
    {
        return view('admin.dashboard', ['user' => Auth::user()]);
    }
}
