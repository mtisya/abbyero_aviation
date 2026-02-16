<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
    ];

    /**
     * The attributes that should be hidden for arrays (e.g., JSON responses).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

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

    // app/Models/User.php

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // app/Models/User.php

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
    // app/Models/User.php
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function skydiveBookings()
    {
        return $this->hasMany(SkydiveBooking::class, 'user_id'); 
    }
}
