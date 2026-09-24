<?php

namespace App\Http\Controllers;

use App\Mail\OnboardingInvitation;
use App\Models\Application;
use App\Models\OnboardingToken;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;


class ApplicationController extends Controller
{
    /**
     * Display all flight school applications.
     */
    public function index()
    {
        $applications = Application::with('onboardingDetail')
            ->latest()
            ->paginate(10);

        return view(
            'application.index',
            compact('applications')
        );
    }


    /**
     * Display a complete application profile.
     */
    public function show(Application $application)
    {
        $application->load([
            'onboardingDetail',
            'onboardingToken',
        ]);

        return view(
            'application.show',
            compact('application')
        );
    }


    /**
     * Approve a flight school application.
     */
    public function approve(Application $application): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate approval
        |--------------------------------------------------------------------------
        */

        if ($application->status === 'approved') {
            return back()->with(
                'error',
                'This application has already been approved.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Approve application
        |--------------------------------------------------------------------------
        */

        $application->update([
            'status' => 'approved',
        ]);


        return back()->with(
            'success',
            'Flight school application approved successfully.'
        );
    }


    /**
     * Reject a flight school application.
     */
    public function reject(
        Application $application,
        Request $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Prevent rejecting an already approved application
        |--------------------------------------------------------------------------
        */

        if ($application->status === 'approved') {
            return back()->with(
                'error',
                'An approved application cannot be rejected.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Optional rejection reason
        |--------------------------------------------------------------------------
        */

        $request->validate([
            'rejection_reason' => [
                'nullable',
                'string',
                'max:2000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | Reject application
        |--------------------------------------------------------------------------
        */

        $application->update([
            'status' => 'rejected',
        ]);


        return back()->with(
            'success',
            'Flight school application rejected successfully.'
        );
    }
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:50',
            ],

            'program' => [
                'nullable',
                'string',
                'max:255',
            ],

            'preferred_start_date' => [
                'nullable',
                'date',
            ],

            'message' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ]);


        /*
        |--------------------------------------------------------------------------
        | CREATE APPLICATION + ONBOARDING TOKEN
        |--------------------------------------------------------------------------
        */

        [$application, $plainToken] = DB::transaction(function () use ($validated) {

            /*
            |--------------------------------------------------------------------------
            | CREATE APPLICATION
            |--------------------------------------------------------------------------
            */

            $application = Application::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'program' => $validated['program'] ?? null,
                'preferred_start_date' => $validated['preferred_start_date'] ?? null,
                'message' => $validated['message'] ?? null,
                'status' => 'submitted',
            ]);


            /*
            |--------------------------------------------------------------------------
            | GENERATE SECURE ONBOARDING TOKEN
            |--------------------------------------------------------------------------
            */

            $plainToken = Str::random(64);

            OnboardingToken::create([
                'application_id' => $application->id,

                'token_hash' => hash(
                    'sha256',
                    $plainToken
                ),

                'expires_at' => now()->addDays(7),
            ]);


            return [
                $application,
                $plainToken,
            ];
        });


        /*
        |--------------------------------------------------------------------------
        | BUILD ONBOARDING URL
        |--------------------------------------------------------------------------
        */

        $onboardingUrl = URL::route(
            'onboarding.show',
            [
                'token' => $plainToken,
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | SEND ONBOARDING EMAIL
        |--------------------------------------------------------------------------
        */

        Mail::to($application->email)->send(
            new OnboardingInvitation(
                $application,
                $onboardingUrl
            )
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE APPLICATION STATUS
        |--------------------------------------------------------------------------
        */

        $application->update([
            'status' => 'onboarding_sent',
        ]);


        /*
        |--------------------------------------------------------------------------
        | AJAX / JSON RESPONSE
        |--------------------------------------------------------------------------
        */

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' =>
                    'Thank you! Your application has been received. '
                    . 'Please check your email for your secure onboarding link.',
            ], 201);
        }


        /*
        |--------------------------------------------------------------------------
        | NORMAL FORM SUBMISSION
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->back()
            ->with(
                'success',
                'Thank you! Your application has been received. '
                . 'Please check your email for your secure onboarding link.'
            );
    }
}