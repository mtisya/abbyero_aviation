<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewUserRegisteredNotification;
use Illuminate\Support\Facades\Notification;


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
            'student' => '/student/dashboard',
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
            'role'     => ['required', 'in:user,admin,instructor,student'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance.
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'role'     => $data['role'],
            'password' => $data['password'],
        ]);

        // ✅ Auto create student profile
        if ($data['role'] === 'student') {
            Student::create([
                'user_id' => $user->id,
            ]);
        }

        return $user;
    }
    protected function guard()
    {
        return Auth::guard();
    }


    protected function registered1(Request $request, $user)
    {
        // ✅ Send email verification
        $user->sendEmailVerificationNotification();

        // 🔥 Notify all admins
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $admin->notify(new NewUserRegisteredNotification($user));
        }

        return redirect()->route('verification.notice')
            ->with('message', 'Please check your email to verify your account.');
    }

    protected function registered(Request $request, $user)
    {
        // ✅ Send email verification
        $user->sendEmailVerificationNotification();

        // 🔥 Notify all admins
        $admins = User::where('role', 'admin')->get();

        Notification::send(
            $admins,
            new NewUserRegisteredNotification($user)
        );

        return redirect()->route('verification.notice')
            ->with('message', 'Please check your email to verify your account.');
    }

}
