<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewUserRegisteredNotification extends Notification
{
    use Queueable;

    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New User Registration')
            ->line('A new user has just registered on the Abbyero Aviation Website.')
            ->line('Name: ' . $this->user->name)
            ->line('Email: ' . $this->user->email)
            ->action('Review User', url('/admin/users'))
            ->line('Please verify and approve the account.');
    }
    public function toArray($notifiable)
    {
        return [
            'type' => 'user_registration',

            'user_id' => $this->user->id,
            'name' => $this->user->name,
            'email' => $this->user->email,

            'message' => 'New user registered: ' . $this->user->name,

            // ✅ Use named routes properly
            'approve_url' => route('users.approve', $this->user->id),
            'reject_url'  => route('users.disapprove', $this->user->id),

            // optional (useful for redirect if needed)
            'url' => route('users.list'),
        ];
    }
}