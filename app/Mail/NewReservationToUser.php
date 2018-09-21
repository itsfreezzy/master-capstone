<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Reservation;
use App\Customer;
use App\ReservationInfo, App\EventVenue, App\EventEquipment, App\ReservationContact;
use Illuminate\Support\Facades\DB;

class NewReservationToUser extends Mailable
{
    use Queueable, SerializesModels;
    public $reservation, $customer, $reservationinfo, $eventvenues, $eventequips, $reservationcontacts;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Reservation $reservation, Customer $customer, $funcroomtype)
    {
        $this->reservation = $reservation;
        $this->customer = $customer;
        $this->reservationinfo = ReservationInfo::find($reservation->reservationinfoid);
        if ($funcroomtype == 'FH') {
            $this->eventvenues = DB::table('tbleventvenue')->join('tblfunctionhalls', 'tbleventvenue.venuecode', '=', 'tblfunctionhalls.code')->where('reservationcode', $reservation->code)->get();
        } elseif ($funcroomtype == 'MR') {
            $this->eventvenues = DB::table('tbleventvenue')->join('tblfunctionhalls', 'tbleventvenue.venuecode', '=', 'tblmeetingrooms.code')->where('reservationcode', $reservation->code)->get();
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
        return $this->subject('New Reservation - ULBC')->markdown('emails.new-reservation-to-user');
    }
}
