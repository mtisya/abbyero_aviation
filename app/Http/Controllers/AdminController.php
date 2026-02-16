<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function index()
    {

        $bookedFlights = Auth::user()
            ->bookings()
            ->with('flight')
            ->latest()
            ->get();

        $maintenances = Auth::user()
            ->maintenances()
            ->orderBy('maintenance_date', 'desc')
            ->get();
        return view('admin.dashboard', compact('bookedFlights', 'maintenances'));
    }
}

