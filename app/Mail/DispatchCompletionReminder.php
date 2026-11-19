<?php

namespace App\Mail;

use App\Models\Dispatch;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DispatchCompletionReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $dispatch;

    public function __construct(Dispatch $dispatch)
    {
        $this->dispatch = $dispatch;
    }

    public function build()
    {
        return $this->subject('Reminder: Complete Your Flight Logbook')
            ->view('emails.dispatch-completion-reminder');
    }
}