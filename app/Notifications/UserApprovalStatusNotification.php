<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserApprovalStatusNotification extends Notification
{
    use Queueable;

    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Account Status Update')
            ->greeting('Hello ' . $notifiable->name)
            ->line(
                $this->status === 'approved'
                    ? '🎉 Your account has been approved. You can now access all features.'
                    : '⚠️ Your account has been set back to pending.'
            )
            ->action('Go to Dashboard', url('/login'));
    }

    public function toArray($notifiable)
    {
        return [
            'status' => $this->status,
            'type' => 'user_status',
            'url' => '/dashboard',
            'message' => $this->status === 'approved'
                ? '🎉 Your account has been approved'
                : '⚠️ Your account is now pending',
        ];
    }
}