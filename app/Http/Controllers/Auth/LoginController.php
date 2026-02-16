<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request and redirect based on user role.
     */
    public function login1(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect user based on role
            $role = Auth::user()->role;

            switch ($role) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard');
                case 'instructor':
                    return redirect()->intended('/instructor/dashboard');
                case 'user':
                default:
                    return redirect()->intended('/dashboard');
            }
            
        }

        // dd(Auth::user()->role);


        return back()->withErrors([
            'email' => 'Invalid login credentials.',
        ])->onlyInput('email');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // ⛔ Block users whose email is NOT verified
            if (! $user->hasVerifiedEmail()) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your email is not verified. Please check your inbox.',
                ])->onlyInput('email');
            }

            // ⛔ Block unapproved users
            if ($user->status !== 'approved') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is pending approval by admin.',
                ])->onlyInput('email');
            }

            // Redirect user based on role
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin/dashboard');

                case 'instructor':
                    return redirect()->intended('/instructor/dashboard');

                default:
                    return redirect()->intended('/dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid login credentials.',
        ])->onlyInput('email');
    }


    /**
     * Log the user out.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have been logged out.');
    }
}
