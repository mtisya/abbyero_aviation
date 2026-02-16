<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class FlightBookedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;
    public $flight;
    public $user;
    public $booking;

    public function __construct($pdf, $flight, $user, $booking)
    {
        $this->pdf = $pdf;
        $this->flight = $flight;
        $this->user = $user;
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Your Flight Booking Confirmation')
                    ->markdown('emails.flight_booked')
                    ->attachData(
                        $this->pdf->output(),
                        "Ticket-{$this->booking->reference}.pdf"
                    );
    }
}