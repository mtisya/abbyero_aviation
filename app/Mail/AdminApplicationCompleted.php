<?php

namespace App\Mail;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminApplicationCompleted extends Mailable
{
    use Queueable, SerializesModels;

    public Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Flight School Onboarding Completed - '
                . $this->application->name
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin.application-completed'
        );
    }

    public function attachments(): array
    {
        return [];
    }
}