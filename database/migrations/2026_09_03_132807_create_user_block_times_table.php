<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBlockTime extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'initial_block_time',
    ];

    protected $casts = [
        'initial_block_time' => 'decimal:1',
    ];

    /*
    |--------------------------------------------------------------------------
    | USER
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVED ADDITIONAL BLOCK TIME
    |--------------------------------------------------------------------------
    */

    public function getApprovedAdditionalBlockTimeAttribute()
    {
        return round(
            (float) BlockTimeRequest::where('user_id', $this->user_id)
                ->where('status', 'approved')
                ->sum('hours'),
            1
        );
    }

    /*
    |--------------------------------------------------------------------------
    | APPROVED FLOWN TIME
    |--------------------------------------------------------------------------
    */

    public function getApprovedFlownTimeAttribute()
    {
        return round(
            (float) Logbook::where('user_id', $this->user_id)
                ->where('approved', true)
                ->sum('hours'),
            2
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TOTAL AVAILABLE BLOCK TIME
    |--------------------------------------------------------------------------
    */

    public function getTotalAvailableBlockTimeAttribute()
    {
        return round(
            (float) $this->initial_block_time
            + $this->approved_additional_block_time,
            1
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REMAINING BLOCK TIME
    |--------------------------------------------------------------------------
    */

    public function getRemainingBlockTimeAttribute()
    {
        return max(
            0,
            round(
                $this->total_available_block_time
                - $this->approved_flown_time,
                1
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | REMAINING BLOCK MINUTES
    |--------------------------------------------------------------------------
    */

    public function getRemainingBlockMinutesAttribute()
    {
        return (int) round(
            $this->remaining_block_time * 60
        );
    }
}