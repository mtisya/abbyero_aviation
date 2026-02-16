<?php

namespace App\Http\Controllers;

use App\Models\SkydiveBooking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Skydiving;

class SkydiveBookingController extends Controller
{
    public function book($id)
    {
        $skydiving = Skydiving::findOrFail($id);
        $userId = Auth::id();

        // Check if user already booked this skydive
        $existingBooking = SkydiveBooking::where('skydiving_id', $skydiving->id)
                                         ->where('user_id', $userId)
                                         ->first();
        if ($existingBooking) {
            return redirect()->route('skydiving.index')
                             ->with('error', 'You have already booked this skydive.');
        }

        // Check if slots are available
        if ($skydiving->available_slots <= 0) {
            return redirect()->route('skydiving.index')
                             ->with('error', 'No slots available.');
        }

        // Create booking
        SkydiveBooking::create([
            'skydiving_id' => $skydiving->id,
            'user_id'      => $userId,
            'slots_booked' => 1,
        ]);

        // Decrease available slots
        $skydiving->decrement('available_slots', 1);

        return redirect()->route('skydiving.index')
                         ->with('success', 'Booking successful! Visit your account to view booked skydive 🎉');
    }
}


