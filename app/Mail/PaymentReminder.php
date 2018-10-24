<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Reservation, App\Customer;

class PaymentReminder extends Mailable
{
    use Queueable, SerializesModels;
    public $reservation, $paymenttype, $customer, $days;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, $paymenttype, $days, Customer $customer)
    {
        $this->reservation = $reservation;
        $this->paymenttype = $paymenttype;
        $this->days = $days;
        $this->customer = $customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('REMINDER - Payment of ' . $this->paymenttype . ' for ' . $this->reservation->eventtitle)->markdown('emails.payment-reminder');
    }
}
