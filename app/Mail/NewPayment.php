<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Reservation;
use App\Customer;
use App\Payment;

class NewPayment extends Mailable
{
    use Queueable, SerializesModels;
    public $reservation, $customer, $payment;

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
        return $this->subject('New Payment from ' . $this->customer->name)->markdown('emails.new-payment')->from($this->customer->email, $this->customer->name);
    }
}
