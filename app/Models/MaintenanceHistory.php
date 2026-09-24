<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceHistory extends Model
{
    protected $fillable = [
        'aircraft_id',
        'maintenance_schedule_id',
        'maintenance_type',
        'performed_date',
        'performed_tach',
        'performed_hobbs',
        'performed_by',
        'engineer_license',
        'cost',
        'work_performed',
        'parts_replaced',
        'remarks',
        'recorded_by',
    ];

    protected $casts = [
        'performed_date' => 'date',
        'performed_tach' => 'decimal:1',
        'performed_hobbs' => 'decimal:1',
        'cost' => 'decimal:2',
    ];

    public function aircraft()
    {
        return $this->belongsTo(Flight::class, 'aircraft_id');
    }

    public function maintenanceSchedule()
    {
        return $this->belongsTo(MaintenanceSchedule::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
    public function maintenanceType()
    {
        return $this->belongsTo(
            MaintenanceType::class
        );
    }
    public function schedule()
    {
        return $this->belongsTo(
            MaintenanceSchedule::class,
            'maintenance_schedule_id'
        );
    }

}