<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockTimeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'flight_id',
        'schedule_id',
        'requested_by',
        'hours',
        'status',
        'approved_by',
        'approved_at',
        'reason',
        'admin_note',
    ];

    protected $casts = [
        'hours'       => 'decimal:1',
        'approved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | STATUS
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING  = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';

    /*
    |--------------------------------------------------------------------------
    | FLIGHT
    |--------------------------------------------------------------------------
    */

    public function flight()
    {
        return $this->belongsTo(
            Flight::class,
            'flight_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCHEDULE
    |--------------------------------------------------------------------------
    */

    public function schedule()
    {
        return $this->belongsTo(
            FlightSchedule::class,
            'schedule_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | USER WHO REQUESTED THE BLOCK TIME
    |--------------------------------------------------------------------------
    */

    public function requester()
    {
        return $this->belongsTo(
            User::class,
            'requested_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | ADMIN / USER WHO APPROVED OR REJECTED
    |--------------------------------------------------------------------------
    */

    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approved_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    public function scopePending($query)
    {
        return $query->where(
            'status',
            self::STATUS_PENDING
        );
    }

    public function scopeApproved($query)
    {
        return $query->where(
            'status',
            self::STATUS_APPROVED
        );
    }

    public function scopeRejected($query)
    {
        return $query->where(
            'status',
            self::STATUS_REJECTED
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}