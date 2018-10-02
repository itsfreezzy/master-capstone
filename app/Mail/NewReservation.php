<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Customer;
use App\Reservation;
use App\ReservationInfo, App\EventVenue, App\EventEquipment, App\ReservationContact;
use Illuminate\Support\Facades\DB;

class NewReservation extends Mailable
{
    use Queueable, SerializesModels;
    public $customer, $reservation, $reservationinfo, $eventvenues, $eventequips, $reservationcontacts;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, Reservation $reservation, $funcroomtype)
    {
        $this->customer = $customer;
        $this->reservation = $reservation;
        $this->reservationinfo = ReservationInfo::find($reservation->reservationinfoid);
        if ($funcroomtype == 'FH') {
            $this->eventvenues = DB::table('tbleventvenue')->join('tblfunctionhalls', 'tbleventvenue.venuecode', '=', 'tblfunctionhalls.code')->where('reservationcode', $reservation->code)->get();
        } elseif ($funcroomtype == 'MR') {
            $this->eventvenues = DB::table('tbleventvenue')->join('tblmeetingrooms', 'tbleventvenue.venuecode', '=', 'tblmeetingrooms.code')->where('reservationcode', $reservation->code)->get();
        }
        $this->eventequips = EventEquipment::where('reservationcode', $reservation->code)->get();
        $this->reservationcontacts = ReservationContact::where('reservationcode', $reservation->code)->get();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Reservation from ' . $this->customer->name)->markdown('emails.new-reservation')->from($this->customer->email, $this->customer->name);
    }
}
