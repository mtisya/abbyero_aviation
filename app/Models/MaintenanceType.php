<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaintenanceType extends Model
{
    protected $fillable = [

        'name',

        'code',

        'category',

        'default_interval_hours',

        'default_interval_days',

        'description',

        'is_active',

    ];

    public function schedules()
    {
        return $this->hasMany(
            MaintenanceSchedule::class
        );
    }

    public function histories()
    {
        return $this->hasMany(
            MaintenanceHistory::class
        );
    }
}