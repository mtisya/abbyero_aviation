<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'aircraft_id',
        'maintenance_type_id',
        'description',
        'interval_hours',
        'interval_days',
        'last_done_tach',
        'last_done_date',
        'next_due_tach',
        'next_due_date',
        'status',
    ];

    protected $casts = [
        'last_done_date' => 'date',
        'next_due_date' => 'date',
        'interval_hours' => 'decimal:1',
        'next_due_tach' => 'decimal:1',
        'last_done_tach' => 'decimal:1',
    ];

    /**
     * Aircraft relationship
     */
    public function aircraft()
    {
        return $this->belongsTo(Flight::class, 'aircraft_id');
    }

    /**
     * Maintenance Type relationship (FIXED VERSION)
     */
   public function maintenanceType()
{
    return $this->belongsTo(
        MaintenanceType::class,
        'maintenance_type_id'
    );
}
    public function isOverdue()
    {
        if ($this->next_due_date && now()->gt($this->next_due_date)) {
            return true;
        }

        if ($this->next_due_tach && $this->aircraft->current_tach > $this->next_due_tach) {
            return true;
        }

        return false;
    }

    public function isDueSoon()
    {
        if ($this->next_due_date && now()->addDays(7)->gt($this->next_due_date)) {
            return true;
        }

        return false;
    }
    public function maintenanceHistory()
    {
        return $this->hasMany(
            MaintenanceHistory::class,
            'maintenance_schedule_id'
        );
    }
}