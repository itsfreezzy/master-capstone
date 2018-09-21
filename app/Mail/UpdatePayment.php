<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Customer;
use App\Reservation;
use App\Payment;

class UpdatePayment extends Mailable
{
    use Queueable, SerializesModels;
    public $customer, $reservation, $payment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, Customer $customer, Payment $payment)
    {
        $this->reservation = $reservation;
        $this->customer = $customer;
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Payment Update to ' . $this->reservation->eventtitle)->markdown('emails.update-payment')->from($this->customer->email, $this->customer->name);
    }
}
