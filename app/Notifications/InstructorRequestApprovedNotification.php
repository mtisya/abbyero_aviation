<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InstructorRequestApprovedNotification extends Notification
{
    use Queueable;

    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Instructor Request Approved')
            ->greeting('Hello ' . $notifiable->name)
            ->line('Your instructor request has been approved.')
            ->action('Go to Dashboard', url('/student/dashboard'));
    }

    public function toArray($notifiable)
    {
        return [
            'request_id' => $this->request->id,
            'url' => '/student/dashboard',
            'message' => 'Your instructor request has been approved by ' .
                optional($this->request->instructor)->name,
        ];
    }
}