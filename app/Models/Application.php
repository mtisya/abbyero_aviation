<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Application extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'program',
        'preferred_start_date',
        'message',
        'status',
        'onboarding_completed_at',
    ];

    protected $casts = [
        'preferred_start_date' => 'date',
        'onboarding_completed_at' => 'datetime',
    ];

    public function onboardingToken(): HasOne
    {
        return $this->hasOne(OnboardingToken::class);
    }

    public function onboardingDetail(): HasOne
    {
        return $this->hasOne(OnboardingDetail::class);
    }
    public function onboardingDocuments(): HasMany
    {
        return $this->hasMany(OnboardingDocument::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

}