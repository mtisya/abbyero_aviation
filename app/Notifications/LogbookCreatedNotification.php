<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LogbookCreatedNotification extends Notification
{
    use Queueable;

    protected $logbook;

    public function __construct($logbook)
    {
        $this->logbook = $logbook;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // 🔥 same as instructor request
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Logbook Entry Submitted')
            ->line('A student has submitted a new logbook entry for approval.')
            ->action('Review Logbook', url('/instructor/dashboard'));
    }

    public function toArray($notifiable)
    {
        return [
            'logbook_id' => $this->logbook->id,
            'url' => '/instructor/logbook/' . $this->logbook->id,
            'approve_url' => '/logbook/approve/' . $this->logbook->id,
            'message' => optional($this->logbook->student->user)->name .
                ' submitted a logbook for ' . $this->logbook->flight_date,
        ];
    }
}