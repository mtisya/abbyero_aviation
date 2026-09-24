<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\Flight;
use App\Models\User;
use App\Models\Dispatch;
use App\Models\BlockTimeRequest;
use App\Models\Logbook;

class FlightSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_id',
        'user_id',
        'instructor_id',
        'start_time',
        'end_time',
        'status',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time'   => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | FLIGHT
    |--------------------------------------------------------------------------
    */

    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }

    /*
    |--------------------------------------------------------------------------
    | USER / OWNER
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | INSTRUCTOR
    |--------------------------------------------------------------------------
    */

    public function instructor()
    {
        return $this->belongsTo(
            User::class,
            'instructor_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | DISPATCH
    |--------------------------------------------------------------------------
    */

    public function dispatch()
    {
        return $this->hasOne(
            Dispatch::class,
            'schedule_id'
        );
    }

    public function dispatches()
    {
        return $this->hasMany(
            Dispatch::class,
            'schedule_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | BLOCK TIME REQUESTS
    |--------------------------------------------------------------------------
    */

    public function blockTimeRequests()
    {
        return $this->hasMany(
            BlockTimeRequest::class,
            'schedule_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | LOGBOOKS
    |--------------------------------------------------------------------------
    */

    public function logbooks()
    {
        return $this->hasMany(
            Logbook::class,
            'schedule_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TIMEZONE
    |--------------------------------------------------------------------------
    |
    | Always store schedule times in UTC.
    |
    */

    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] =
            Carbon::parse($value)->utc();
    }

    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] =
            Carbon::parse($value)->utc();
    }
}
