<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id', // <-- Add this so you can store the owner of the maintenance record
        'aircraft_model',
        'registration_number',
        'manufacturer',
        'serial_number',
        'engine_type',
        'last_maintenance_hours',
        'maintenance_date',
        'next_due_date',
        'issue_description',
        'remarks',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
