<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('onboarding_details', function (Blueprint $table) {

            $table->id();

            $table->foreignId('application_id')
                ->unique()
                ->constrained('applications')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | PERSONAL INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->string('middle_name')->nullable();

            $table->date('date_of_birth')->nullable();

            $table->string('nationality')->nullable();

            $table->string('gender')->nullable();

            $table->string('address')->nullable();

            $table->string('city')->nullable();

            $table->string('state')->nullable();

            $table->string('postal_code')->nullable();

            $table->string('country')->nullable();


            /*
            |--------------------------------------------------------------------------
            | PASSPORT / IDENTIFICATION
            |--------------------------------------------------------------------------
            */

            $table->string('id_type')->nullable();

            $table->string('id_number')->nullable();

            $table->string('passport_number')->nullable();

            $table->string('passport_country')->nullable();

            $table->date('passport_expiry')->nullable();


            /*
            |--------------------------------------------------------------------------
            | EMERGENCY CONTACT
            |--------------------------------------------------------------------------
            */

            $table->string('emergency_contact_name')->nullable();

            $table->string('emergency_contact_relationship')->nullable();

            $table->string('emergency_contact_phone')->nullable();

            $table->string('emergency_contact_email')->nullable();


            /*
            |--------------------------------------------------------------------------
            | PILOT CERTIFICATION
            |--------------------------------------------------------------------------
            */

            $table->string('pilot_certificate')->nullable();

            $table->string('certificate_number')->nullable();

            $table->string('certificate_country')->nullable();

            $table->date('certificate_issue_date')->nullable();


            /*
            |--------------------------------------------------------------------------
            | MEDICAL
            |--------------------------------------------------------------------------
            */

            $table->string('medical_certificate_class')->nullable();

            $table->date('medical_certificate_issue_date')->nullable();

            $table->date('medical_certificate_expiry')->nullable();

            $table->string('medical_issuer')->nullable();


            /*
            |--------------------------------------------------------------------------
            | FLIGHT EXPERIENCE
            |--------------------------------------------------------------------------
            */

            $table->decimal('total_flight_hours', 8, 1)
                ->nullable();

            $table->decimal('pilot_in_command_hours', 8, 1)
                ->nullable();

            $table->decimal('dual_instruction_hours', 8, 1)
                ->nullable();

            $table->decimal('solo_hours', 8, 1)
                ->nullable();

            $table->decimal('cross_country_hours', 8, 1)
                ->nullable();

            $table->decimal('night_hours', 8, 1)
                ->nullable();

            $table->decimal('instrument_hours', 8, 1)
                ->nullable();

            $table->decimal('multi_engine_hours', 8, 1)
                ->nullable();


            /*
            |--------------------------------------------------------------------------
            | TRAINING HISTORY
            |--------------------------------------------------------------------------
            */

            $table->string('previous_flight_school')->nullable();

            $table->string('previous_instructor')->nullable();

            $table->text('previous_training')->nullable();

            $table->text('ratings_endorsements')->nullable();

            $table->text('training_goals')->nullable();


            /*
            |--------------------------------------------------------------------------
            | ADDITIONAL INFORMATION
            |--------------------------------------------------------------------------
            */

            $table->text('additional_information')->nullable();

            $table->boolean('information_confirmed')
                ->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('onboarding_details');
    }
};