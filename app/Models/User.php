<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\UserBlockTime;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // This allows mass assignment of role
        'status', // This allows mass assignment of status
        'profile_image', // This allows mass assignment of profile_image
    ];

    /**
     * The attributes that should be hidden for arrays (e.g., JSON responses).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    public function flightSchedules()
    {
        return $this->hasMany(FlightSchedule::class, 'user_id');
    }

    /**
     * The attributes that should be type-cast.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Automatically hash password on set.
     */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function skydiveBookings()
    {
        return $this->hasMany(SkydiveBooking::class, 'user_id');
    }

    public function student()
    {
        return $this->hasOne(Student::class);

    }
    public function studentProfile()
    {
        return $this->hasOne(Student::class, 'user_id');
    }
    public function instructor()
    {
        return $this->hasOne(Instructor::class);
    }

    public function application(): HasOne
    {
        return $this->hasOne(Application::class);
    }
    public function blockTime()
    {
        return $this->hasOne(
            UserBlockTime::class,
            'user_id'
        );
    }
    public function userBlockTime()
    {
        return $this->hasOne(UserBlockTime::class);
    }

    public function dispatches(): HasMany
    {
        return $this->hasMany(
            Dispatch::class,
            'pilot_id'
        );
    }

    public function logbooks(): HasMany
    {
        return $this->hasMany(
            Logbook::class,
            'user_id'
        );
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
