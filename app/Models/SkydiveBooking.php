<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SkydiveBooking extends Model
{
    // Explicit table name
    protected $table = 'skydivebooking';

    // Allow mass assignment
    protected $fillable = [
        'skydiving_id',
        'user_id',
        'slots_booked',
        'status', // optional if you want to track booking status
    ];

    /**
     * Booking belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Booking belongs to a skydiving event
     */
    public function skydiving()
    {
        return $this->belongsTo(Skydiving::class, 'skydiving_id');
    }
}



