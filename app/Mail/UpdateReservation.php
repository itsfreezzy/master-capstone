<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Reservation;
use App\Customer;

class UpdateReservation extends Mailable
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
        return $this->subject('Reservation ' . $this->reservation->code . ' has been updated')->markdown('emails.update-reservation')->from($this->customer->email, $this->customer->name);
    }
}
