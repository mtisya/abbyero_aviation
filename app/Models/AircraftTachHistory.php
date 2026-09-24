<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AircraftTachHistory extends Model
{
    use HasFactory;

     protected $fillable = [
        'aircraft_id',
        'old_tach',
        'new_tach',
        'logbook_id',
        'updated_by',
        'reason',
    ];

    public function aircraft()
    {
        return $this->belongsTo(Flight::class, 'aircraft_id');
    }

    public function logbook()
    {
        return $this->belongsTo(Logbook::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}