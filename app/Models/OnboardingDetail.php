<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingDetail extends Model
{
    protected $fillable = [
        'application_id',

        'middle_name',
        'date_of_birth',
        'nationality',
        'gender',
        'address',
        'city',
        'state',
        'postal_code',
        'country',

        'id_type',
        'id_number',
        'passport_number',
        'passport_country',
        'passport_expiry',

        'emergency_contact_name',
        'emergency_contact_relationship',
        'emergency_contact_phone',
        'emergency_contact_email',

        'pilot_certificate',
        'certificate_number',
        'certificate_country',
        'certificate_issue_date',

        'medical_certificate_class',
        'medical_certificate_issue_date',
        'medical_certificate_expiry',
        'medical_issuer',

        'total_flight_hours',
        'pilot_in_command_hours',
        'dual_instruction_hours',
        'solo_hours',
        'cross_country_hours',
        'night_hours',
        'instrument_hours',
        'multi_engine_hours',

        'previous_flight_school',
        'previous_instructor',
        'previous_training',
        'ratings_endorsements',
        'training_goals',

        'identity_document_path',
        'pilot_certificate_document_path',
        'medical_certificate_document_path',
        'logbook_document_path',

        'additional_information',
    ];
    protected $casts = [
        'date_of_birth' => 'date',
        'passport_expiry' => 'date',
        'certificate_issue_date' => 'date',
        'medical_certificate_issue_date' => 'date',
        'medical_certificate_expiry' => 'date',

        'total_flight_hours' => 'decimal:1',
        'pilot_in_command_hours' => 'decimal:1',
        'dual_instruction_hours' => 'decimal:1',
        'solo_hours' => 'decimal:1',
        'cross_country_hours' => 'decimal:1',
        'night_hours' => 'decimal:1',
        'instrument_hours' => 'decimal:1',
        'multi_engine_hours' => 'decimal:1',

        'information_confirmed' => 'boolean',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }
}