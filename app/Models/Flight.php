<?php

// app/Models/Flight.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Flight extends Model
{
    protected $fillable = [
        'aircraft_model',
        'registration_number',
        'departure_time',
        'arrival_time',
        'departure_location',
        'arrival_location',
        'price',
        'status',
    ];
    // app/Models/User.php

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * A flight belongs to one instructor.
     */
    public function instructor()
    {
        return $this->belongsTo(Instructor::class, 'instructor_id');
    }


}
