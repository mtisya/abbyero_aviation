<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlightSchedule extends Model
{
    protected $fillable = [
        'flight_id',
        'user_id',
        'start_time',
        'end_time',
        'status'
    ];

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

