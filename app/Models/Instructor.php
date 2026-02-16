<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    // Fields allowed for mass assignment
    protected $fillable = [
        'name',
        'license_number',
        'specialization',
        'phone',
        'email',
        'bio',
        'photo',
        'active',
    ];

    /**
     * An instructor can have many flights assigned.
     */
    public function flights()
    {
        return $this->hasMany(Flight::class, 'instructor_id');
    }
}
