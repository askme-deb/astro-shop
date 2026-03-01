<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Check for API login session
        if (!session()->has('auth.api_token') || !session()->has('auth.user')) {
            return redirect()->route('login'); // Use /login route
        }
        $user = session('auth.user');
        return view('dashboard', compact('user'));
    }
}
