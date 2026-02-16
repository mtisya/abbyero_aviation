<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{

    /**
     * Where to redirect users after registration based on role.
     */
    protected function redirectTo()
    {
        $role = auth()->user()->role;

        return match ($role) {
            'admin' => '/admin/dashboard',
            'instructor' => '/instructor/dashboard',
            'user' => '/dashboard',
            default => '/',
        };
    }

    /**
     * Show registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());

        event(new Registered($user));

        $this->guard()->login($user);

        return $this->registered($request, $user) ?: redirect($this->redirectPath());
    }



    /**
     * Validate registration data.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'role'     => ['required', 'in:user,admin,instructor'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance.
     */
    protected function create(array $data)
    {
        return User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'role'     => $data['role'],
        'password' => $data['password'],  
    ]);
    }
    protected function guard()
    {
        return Auth::guard();
    }

    protected function registered(Request $request, $user)
    {
        // Send email verification link
        $user->sendEmailVerificationNotification();

        return redirect()->route('verification.notice')
            ->with('message', 'Please check your email to verify your account.');
    }

}
