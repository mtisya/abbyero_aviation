<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class LogbookApproved extends Notification
{
    use Queueable;

    protected $logbook;

    public function __construct($logbook)
    {
        $this->logbook = $logbook;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // send both email and dashboard notification
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Your Logbook Entry Was Approved')
                    ->greeting('Hello ' . $notifiable->name . ',')
                    ->line('Your logbook entry for ' . $this->logbook->flight_date . ' has been approved by your instructor.')
                    ->action('View Logbook', url('/student/dashboard'))
                    ->line('Thank you for keeping your logbook up to date!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'logbook_id' => $this->logbook->id,
            'url' => '/student/logbook/' . $this->logbook->id,
            'message' => 'Your logbook entry for ' . $this->logbook->flight_date . ' was approved by the instructor.',
        ];
    }
}