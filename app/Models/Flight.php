<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MaintenanceSchedule;


class Flight extends Model
{

   protected $fillable = [
        'aircraft_model',
        'registration_number',
        'current_tach',
        'current_hobbs',
        'maintenance_status',
        'hourly_rate',
        'status',
    ];


    protected static function booted()
    {
        static::created(function ($flight) {

            MaintenanceSchedule::create([
                'aircraft_id' => $flight->id,
                'maintenance_type' => 'Oil Change',
                'interval_hours' => 25,
                'next_due_tach' => 
                    ($flight->current_tach ?? 0) + 25,
            ]);

        });
    }



    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }



    public function instructor()
    {
        return $this->belongsTo(
            Instructor::class,
            'instructor_id'
        );
    }



    public function schedules()
    {
        return $this->hasMany(
            FlightSchedule::class,
            'flight_id'
        );
    }



    public function dispatches()
    {
        return $this->hasMany(
            Dispatch::class,
            'aircraft_id'
        );
    }



    public function maintenanceSchedules()
    {
        return $this->hasMany(
            MaintenanceSchedule::class,
            'aircraft_id'
        );
    }



    /**
     * Get nearest maintenance remaining hours
     */
    public function getHoursRemainingAttribute()
    {

        $schedule = $this->maintenanceSchedules
            ->filter(function ($item) {

                return !is_null(
                    $item->next_due_tach
                );

            })
            ->sortBy('next_due_tach')
            ->first();


        if (!$schedule) {

            return null;

        }


        return round(
            $schedule->next_due_tach - $this->current_tach,
            1
        );

    }



    /**
     * Aircraft maintenance status
     */
   public function getMaintenanceStatusAttribute($value)
{
    $remaining = $this->hours_remaining;


    if (is_null($remaining)) {
        return $value ?? 'serviceable';
    }


    if ($remaining <= 0) {
        return 'maintenance';
    }


    if ($remaining <= 10) {
        return 'due_soon';
    }


    return 'serviceable';
}

}