<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Logbook extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'student_id',
        'instructor_id',

        'schedule_id',
        'flight_id',
        'flight_date',
        'aircraft',
        'route',

        'hobbs_start',
        'hobbs_end',
        'tach_start',
        'tach_end',

        'hours',
        'tach_hours',
        'block_time',

        'fuel_added',
        'oil_added',

        'type',

        'approved',
        'approved_by',

        'remarks',
        'deleted_by',

        'override_edit',
        'override_by',
        'override_reason',
    ];

    public function getScheduledMinutesAttribute()
    {
        if (!$this->schedule) {
            return 0;
        }

        return Carbon::parse(
            $this->schedule->start_time
        )->diffInMinutes(
            Carbon::parse(
                $this->schedule->end_time
            )
        );
    }


   public function getRemainingMinutesAttribute()
    {
        if (!$this->schedule_id) {
            return 0;
        }

        $initialBlockTime = (float) (
            self::where('schedule_id', $this->schedule_id)
                ->whereNotNull('block_time')
                ->orderBy('id')
                ->value('block_time') ?? 0
        );

        $additionalBlockTime = BlockTimeRequest::where(
            'schedule_id',
            $this->schedule_id
        )
            ->where('status', 'approved')
            ->sum('hours');

        $approvedFlownTime = self::where(
            'schedule_id',
            $this->schedule_id
        )
            ->where('approved', true)
            ->sum('hours');

        $remainingHours = max(
            0,
            $initialBlockTime
                + (float) $additionalBlockTime
                - (float) $approvedFlownTime
        );

        return $remainingHours * 60;
    }

    public function getRemainingBlockTimeAttribute()
    {
        return $this->user?->userBlockTime?->remaining_block_time ?? 0;
    }

    public function getRemainingBlockMinutesAttribute()
    {
        return (int) round($this->remaining_block_time * 60);
    }
    public function getHourlyRateAttribute()
    {
        return $this->flight?->hourly_rate ?? 0;
    }

    public function getFlightCostAttribute()
    {
        return round($this->hours * $this->hourly_rate, 2);
    }

    public function getHobbsTimeAttribute()
    {
        if (
            $this->hobbs_start !== null &&
            $this->hobbs_end !== null
        ) {
            return round(
                $this->hobbs_end - $this->hobbs_start,
                1
            );
        }

        return 0;
    }


    public function getTachTimeAttribute()
    {
        if (
            $this->tach_start !== null &&
            $this->tach_end !== null
        ) {
            return round(
                $this->tach_end - $this->tach_start,
                1
            );
        }

        return 0;
    }

    public function user()
    {
        return $this->belongsTo(User::class); // creator
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function schedule()
    {
        return $this->belongsTo(FlightSchedule::class, 'schedule_id');
    }
    public function flight()
    {
        return $this->belongsTo(Flight::class, 'flight_id');
    }

    public function aiAnalysis()
    {
        return $this->hasOne(AiLogbookAnalysis::class);
    }
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
