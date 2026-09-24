<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffLogbook extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',

        'flight_date',
        'aircraft',
        'route',

        'hobbs_start',
        'hobbs_end',

        'tach_start',
        'tach_end',

        'hours',
        'tach_hours',

        'remarks',
    ];

 
    public function getHobbsTimeAttribute1()
    {
        if ($this->hobbs_start && $this->hobbs_end) {
            return round(
                $this->hobbs_end - $this->hobbs_start,
                1
            );
        }

        return 0;
    }
    public function getHobbsTimeAttribute()
    {
        if (
            $this->hobbs_start !== null &&
            $this->hobbs_end !== null
        ) {
            return round(
                $this->hobbs_end - $this->hobbs_start,
                1
            );
        }

        return 0;
    }

    public function getTachTimeAttribute1()
    {
        if ($this->tach_start && $this->tach_end) {
            return round(
                $this->tach_end - $this->tach_start,
                1
            );
        }

        return 0;
    }

    public function getTachTimeAttribute()
    {
        if (
            $this->tach_start !== null &&
            $this->tach_end !== null
        ) {
            return round(
                $this->tach_end - $this->tach_start,
                1
            );
        }

        return 0;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log belongs to a student
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function schedule()
    {
        return $this->belongsTo(FlightSchedule::class);
    }

    /**
     * Log approved by instructor (User)
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Optional flight relation
     */
    public function flight()
    {
        return $this->belongsTo(Flight::class);
    }
    public function aiAnalysis()
    {
        return $this->hasOne(AiLogbookAnalysis::class);
    }
}
