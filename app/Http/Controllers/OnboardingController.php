<?php

namespace App\Http\Controllers;

use App\Mail\AdminApplicationCompleted;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

use App\Models\OnboardingToken;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OnboardingController extends Controller
{
    /**
     * ---------------------------------------------------------------
     * Display onboarding welcome page
     * ---------------------------------------------------------------
     */
    public function show(string $token)
    {
        $onboardingToken = $this->getValidToken($token);

        if (!$onboardingToken) {
            return view('onboarding.expired');
        }

        $application = $onboardingToken->application;

        return view(
            'onboarding.form',
            compact(
                'application',
                'token'
            )
        );
    }


    /**
     * ---------------------------------------------------------------
     * Start onboarding process
     * ---------------------------------------------------------------
     */
    public function store(
        Request $request,
        string $token
    ): RedirectResponse {

        $onboardingToken = $this->getValidToken($token);

        if (!$onboardingToken) {
            return redirect()
                ->route('onboarding.show', $token)
                ->with(
                    'error',
                    'This onboarding link has expired or has already been used.'
                );
        }

        $application = $onboardingToken->application;

        if (!$application) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Mark onboarding as started
        |--------------------------------------------------------------------------
        */

        if ($application->status === 'onboarding_sent') {

            $application->update([
                'status' => 'onboarding_started',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Continue to detailed onboarding
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'onboarding.details',
            $token
        );
    }


    /**
     * ---------------------------------------------------------------
     * Display detailed onboarding form
     * ---------------------------------------------------------------
     */
    public function details(string $token)
    {
        $onboardingToken = $this->getValidToken($token);

        if (!$onboardingToken) {
            return view('onboarding.expired');
        }

        $application = $onboardingToken->application;

        if (!$application) {
            abort(404);
        }

        /*
        |--------------------------------------------------------------------------
        | Existing onboarding information
        |--------------------------------------------------------------------------
        */

        $onboardingDetail = $application->onboardingDetail;

        return view(
            'onboarding.details',
            compact(
                'application',
                'onboardingDetail',
                'token'
            )
        );
    }


    /**
     * ---------------------------------------------------------------
     * Save detailed onboarding information
     * ---------------------------------------------------------------
     */
    public function saveDetails(
        Request $request,
        string $token
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate token
        |--------------------------------------------------------------------------
        */

        $onboardingToken = $this->getValidToken($token);

        if (!$onboardingToken) {

            return redirect()
                ->route('onboarding.show', $token)
                ->with(
                    'error',
                    'This onboarding link has expired or has already been used.'
                );
        }

        $application = $onboardingToken->application;

        if (!$application) {
            abort(404);
        }


        /*
        |--------------------------------------------------------------------------
        | VALIDATION
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            /*
            |--------------------------------------------------------------------------
            | Personal Information
            |--------------------------------------------------------------------------
            */

            'middle_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'date_of_birth' => [
                'nullable',
                'date',
                'before:today',
            ],

            'nationality' => [
                'nullable',
                'string',
                'max:100',
            ],

            'gender' => [
                'nullable',
                'string',
                'max:50',
            ],

            'address' => [
                'nullable',
                'string',
                'max:255',
            ],

            'city' => [
                'nullable',
                'string',
                'max:100',
            ],

            'state' => [
                'nullable',
                'string',
                'max:100',
            ],

            'postal_code' => [
                'nullable',
                'string',
                'max:30',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],


            /*
            |--------------------------------------------------------------------------
            | Identification
            |--------------------------------------------------------------------------
            */

            'id_type' => [
                'nullable',
                'string',
                'max:50',
            ],

            'id_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'passport_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'passport_country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'passport_expiry' => [
                'nullable',
                'date',
            ],


            /*
            |--------------------------------------------------------------------------
            | Emergency Contact
            |--------------------------------------------------------------------------
            */

            'emergency_contact_name' => [
                'required',
                'string',
                'max:255',
            ],

            'emergency_contact_relationship' => [
                'required',
                'string',
                'max:100',
            ],

            'emergency_contact_phone' => [
                'required',
                'string',
                'max:50',
            ],

            'emergency_contact_email' => [
                'nullable',
                'email',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Pilot Certificate
            |--------------------------------------------------------------------------
            */

            'pilot_certificate' => [
                'nullable',
                'string',
                'max:100',
            ],

            'certificate_number' => [
                'nullable',
                'string',
                'max:100',
            ],

            'certificate_country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'certificate_issue_date' => [
                'nullable',
                'date',
            ],


            /*
            |--------------------------------------------------------------------------
            | Medical Certificate
            |--------------------------------------------------------------------------
            */

            'medical_certificate_class' => [
                'nullable',
                'string',
                'max:100',
            ],

            'medical_certificate_issue_date' => [
                'nullable',
                'date',
            ],

            'medical_certificate_expiry' => [
                'nullable',
                'date',
            ],

            'medical_issuer' => [
                'nullable',
                'string',
                'max:255',
            ],


            /*
            |--------------------------------------------------------------------------
            | Flight Experience
            |--------------------------------------------------------------------------
            */

            'total_flight_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'pilot_in_command_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'dual_instruction_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'solo_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'cross_country_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'night_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'instrument_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'multi_engine_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],


            /*
            |--------------------------------------------------------------------------
            | Training History
            |--------------------------------------------------------------------------
            */

            'previous_flight_school' => [
                'nullable',
                'string',
                'max:255',
            ],

            'previous_instructor' => [
                'nullable',
                'string',
                'max:255',
            ],

            'previous_training' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'ratings_endorsements' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'training_goals' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'additional_information' => [
                'nullable',
                'string',
                'max:5000',
            ],


            /*
            |--------------------------------------------------------------------------
            | Confirmation
            |--------------------------------------------------------------------------
            */

            'information_confirmed' => [
                'required',
                'accepted',
            ],


            /*
            |--------------------------------------------------------------------------
            | Documents
            |--------------------------------------------------------------------------
            */

            'identity_document' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],

            'pilot_certificate_document' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],

            'medical_certificate_document' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],

            'logbook_document' => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Remove checkbox from database data
        |--------------------------------------------------------------------------
        |
        | `information_confirmed` is only a confirmation control.
        | Do not save it unless the database contains that column.
        |
        */

        unset(
            $validated['information_confirmed']
        );


        /*
        |--------------------------------------------------------------------------
        | SAVE EVERYTHING
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use ($request, $application, $onboardingToken, $validated) {

            $documents = [
                'identity_document' => 'identity_document_path',
                'pilot_certificate_document' => 'pilot_certificate_document_path',
                'medical_certificate_document' => 'medical_certificate_document_path',
                'logbook_document' => 'logbook_document_path',
            ];

            foreach ($documents as $input => $databaseField) {

                if ($request->hasFile($input)) {

                    $validated[$databaseField] = $request
                        ->file($input)
                        ->store(
                            'onboarding/' . $application->id,
                            'public'
                        );
                }
            }

            $application->onboardingDetail()->updateOrCreate(
                [
                    'application_id' => $application->id,
                ],
                $validated
            );


            $application->update([
                'status' => 'onboarding_completed',
                'onboarding_completed_at' => now(),
            ]);


            /*
            |--------------------------------------------------------------------------
            | Mark token as used
            |--------------------------------------------------------------------------
            */

            $onboardingToken->update([
                'used_at' => now(),
            ]);
            
            });
            $adminEmails = User::where('role', 'admin')
                ->whereNotNull('email')
                ->pluck('email')
                ->filter()
                ->unique()
                ->values()
                ->all();

            if (!empty($adminEmails)) {

                Mail::to(config('mail.from.address'))
                    ->bcc($adminEmails)
                    ->send(
                        new AdminApplicationCompleted(
                            $application->fresh([
                                'onboardingDetail',
                            ])
                        )
                    );
            }

        return redirect()
            ->route('onboarding.completed')
            ->with(
                'success',
                'Your onboarding information has been successfully submitted.'
            );
    }


    /**
     * ---------------------------------------------------------------
     * Validate onboarding token
     * ---------------------------------------------------------------
     */
    private function getValidToken(
        string $token
    ): ?OnboardingToken {

        /*
        |--------------------------------------------------------------------------
        | Hash provided token
        |--------------------------------------------------------------------------
        */

        $tokenHash = hash(
            'sha256',
            $token
        );


        /*
        |--------------------------------------------------------------------------
        | Find token
        |--------------------------------------------------------------------------
        */

        $onboardingToken = OnboardingToken::with(
            'application.onboardingDetail'
        )
            ->where(
                'token_hash',
                $tokenHash
            )
            ->first();


        if (!$onboardingToken) {
            return null;
        }


        /*
        |--------------------------------------------------------------------------
        | Check expiration / usage
        |--------------------------------------------------------------------------
        */

        if (!$onboardingToken->isValid()) {
            return null;
        }


        return $onboardingToken;
    }
}