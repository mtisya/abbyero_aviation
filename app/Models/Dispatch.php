<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dispatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'schedule_id',
        'aircraft_id',
        'pilot_id',
        'dispatch_no',
        'hobbs_out',
        'tach_out',
        'dispatch_time',
        'status',
        'remarks',
        'dispatched_by'
    ];
     protected $casts = [
        'dispatch_time' => 'datetime',
    ];

    public const STATUS_DISPATCHED = 'dispatched';
    public const STATUS_IN_FLIGHT = 'in_flight';
    public const STATUS_COMPLETED = 'completed';

    public function scopeActive($query)
    {
        return $query->whereIn('status', [
            self::STATUS_DISPATCHED,
            self::STATUS_IN_FLIGHT,
        ]);
    }

    public function scheduleold()
    {
        return $this->belongsTo(FlightSchedule::class);
    }

    public function schedule()
    {
        return $this->belongsTo(FlightSchedule::class, 'schedule_id');
    }

    public function aircraft()
    {
        return $this->belongsTo(Flight::class, 'aircraft_id');
    }

    public function pilot()
    {
        return $this->belongsTo(User::class, 'pilot_id');
    }

    public function dispatcher()
    {
        return $this->belongsTo(User::class, 'dispatched_by');
    }
}