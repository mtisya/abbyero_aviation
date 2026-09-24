<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FlightApplicationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $pdf;

    public function __construct($data, $pdf)
    {
        $this->data = $data;
        $this->pdf  = $pdf;
    }

    public function build()
    {
        return $this->subject('New Flight School Application')
                    ->markdown('emails.flight_application')
                    ->attachData(
                        $this->pdf->output(),
                        'FlightApplication-' . now()->format('Ymd-His') . '.pdf',
                        [
                            'mime' => 'application/pdf',
                        ]
                    );
    }
}