<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Reservation, App\Customer;

class NonPaymentOfReservation extends Mailable
{
    use Queueable, SerializesModels;
    public $reservation, $customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, Customer $customer)
    {
        $this->reservation = $reservation;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Non-payment of Reservation Fee for ' . $this->reservation->eventtitle)->markdown('emails.non-payment-reservation');
    }
}