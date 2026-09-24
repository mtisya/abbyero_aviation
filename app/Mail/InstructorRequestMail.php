<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class InstructorRequestMail extends Mailable
{
    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function build()
    {
        return $this->subject('Instructor Request')
            ->view('emails.instructor_request');
    }
}