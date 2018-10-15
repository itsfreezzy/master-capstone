<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Customer;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;
    public $customer, $newpassword;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, $newpassword)
    {
        $this->customer = $customer;
        $this->newpassword = $newpassword;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Forgot Password')->markdown('emails.forgot-password');
    }
}
