<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Customer;
use App\Reservation;
use App\Payment;

class PaymentRejected extends Mailable
{
    use Queueable, SerializesModels;
    public $customer, $reservation, $payment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, Reservation $reservation, Payment $payment)
    {
        $this->customer = $customer;
        $this->reservation = $reservation;
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Payment for ' . $this->reservation->eventtitle . ' is rejected')->markdown('emails.payment-rejected');
    }
}
