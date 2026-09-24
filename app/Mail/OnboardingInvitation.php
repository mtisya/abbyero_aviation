<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OnboardingInvitation extends Mailable
{
    use Queueable, SerializesModels;

    public Application $application;

    public string $onboardingUrl;

    public function __construct(
        Application $application,
        string $onboardingUrl
    ) {
        $this->application = $application;
        $this->onboardingUrl = $onboardingUrl;
    }

    public function build()
    {
        return $this
            ->subject(
                'Complete Your Abbyero Aviation Onboarding'
            )
            ->view('emails.onboarding-invitation');
    }
}