<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SkydiveBooking;

use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user(); // ✅ define user once

        $bookedFlights = $user->bookings()
            ->with('flight')
            ->latest()
            ->get();

        $maintenances = $user->maintenances()
            ->orderBy('maintenance_date', 'desc')
            ->get();

        $orders = $user->orders()
            ->with('items')
            ->latest()
            ->get();
// User's skydiving bookings
        $bookings = SkydiveBooking::with('skydiving')
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        return view('user.dashboard', compact('bookedFlights', 'maintenances', 'orders', 'bookings'));
    }

    // List all users with pagination
    public function list()
{
    $users = User::paginate(20);

    if ($users->isEmpty() && $users->currentPage() > 1) {
        return redirect()->route('users.list', ['page' => $users->lastPage()]);
    }

    return view('user.list', compact('users'));
}


    // Approve user
    public function approve(User $user)
    {
        $user->status = 'approved';
        $user->save();

        return back()->with('success', 'User approved successfully.');
    }

    // Disapprove user
    public function disapprove(User $user)
    {
        $user->status = 'pending';
        $user->save();

        return back()->with('success', 'User disapproved successfully.');
    }
}