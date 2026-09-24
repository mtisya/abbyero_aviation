<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'instructor_id',
        'total_hours',
        'completed_lessons',
        'status',
    ];

    /**
     * Student belongs to a user
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Student belongs to an instructor (User)
     */
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    /**
     * Student has many bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }
}