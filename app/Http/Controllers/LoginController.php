<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validate input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Attempt login
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // Redirect back to intended page or dashboard
            return redirect()->intended('/dashboard');
        }

        // Return back with error
        return back()->withErrors([
            'email' => 'Invalid login credentials',
        ]);
    }
}
