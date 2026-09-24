<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AircraftHoursReached extends Notification
{
    use Queueable;

    protected $aircraft;
    protected $hours;
    protected $level;

    public function __construct($aircraft, $hours, $level = 'warning')
{
    $this->aircraft = $aircraft;
    $this->hours = $hours;
    $this->level = $level; // ✅ NEW (warning | danger)
}

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(
                $this->level === 'danger'
                ? '🚨 CRITICAL Aircraft Alert'
                : '⚠️ Aircraft Warning'
            )
            ->line("Aircraft {$this->aircraft} has reached {$this->hours} hours.")
            ->line(
                $this->level === 'danger'
                ? 'Immediate maintenance required!'
                : 'Maintenance should be scheduled soon.'
            )
            ->action('View Dashboard', url('/admin/dashboard'));
    }

    public function toArray($notifiable)
    {
        return [
            'type' => 'aircraft_alert',
            'level' => $this->level, // ✅ important
            'aircraft' => $this->aircraft,
            'hours' => $this->hours,
            'url' => '/admin/dashboard',
            'message' => "Aircraft {$this->aircraft} has reached {$this->hours} hours " 
                . ($this->level === 'danger' ? '🚨 CRITICAL' : '⚠️ Warning'),
        ];
    }
}