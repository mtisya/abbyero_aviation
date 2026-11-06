<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MaintenanceAlert extends Notification
{
    use Queueable;

    protected $aircraft;
    protected $maintenance;
    protected $status;
    protected $details;

    public function __construct(
        $aircraft,
        $maintenance,
        $status,
        $details = ''
    ) {
        $this->aircraft = $aircraft;
        $this->maintenance = $maintenance;
        $this->status = $status;
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        $isOverdue = $this->status === 'overdue';

        $message = $isOverdue
            ? "🚨 OVERDUE: {$this->maintenance} for aircraft {$this->aircraft} requires maintenance."
            : "⚠️ DUE SOON: {$this->maintenance} for aircraft {$this->aircraft} is approaching its maintenance limit.";

        if ($this->details) {
            $message .= " {$this->details}";
        }

        return [
            'type' => 'maintenance_alert',
            'level' => $isOverdue ? 'danger' : 'warning',
            'status' => $this->status,
            'aircraft' => $this->aircraft,
            'maintenance' => $this->maintenance,
            'details' => $this->details,
            'url' => '/aircraft-maintenance',
            'message' => $message,
        ];
    }
}
