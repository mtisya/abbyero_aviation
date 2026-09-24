<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstructorRequestNotification extends Notification
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // 🔥 both email + DB
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Instructor Request')
            ->line('A student has requested you as instructor.')
            ->action('View Request', url('/instructor/dashboard'));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'instructor_request',

            'request_id' => $this->request->id,

            // 👇 student preview data
            'student_name' => optional($this->request->student->user)->name,
            'student_email' => optional($this->request->student->user)->email,

            // 👇 actions
            'accept_url' => '/instructor/request/accept/' . $this->request->id,
            'reject_url' => '/instructor/request/reject/' . $this->request->id,

            // 👇 optional view page
            'url' => '/instructor/requests/' . $this->request->id,

            'message' => 'You have a new instructor request from student Name',
        ];
    }
}
