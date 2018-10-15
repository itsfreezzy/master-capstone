<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Reservation;
use App\Customer;
use App\Payment;
use Auth;
use Illuminate\Support\Facades\DB;
use App\Mail\PaymentVerified;
use App\Mail\PaymentRejected;
use App\Mail\SlotTaken;
use App\EventVenue;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = Payment::withTrashed()->orderBy('created_at', 'DESC')->get();
        $reservations = Reservation::join('tblcustomers', 'tblcustomers.code', '=', 'tblreservations.customercode')->select('tblreservations.*', 'tblcustomers.name')->withTrashed()->get();
        $customers = Customer::withTrashed()->get();

        return view('admin.payments')->with([
            'reservations' => $reservations,
            'customers' => $customers,
            'payments' => $payments,
        ]);
    }


    public function confirm($id)
    {
        $payment = Payment::find($id);
        $eventvenues = array();
        if ($payment->paymenttype == 'Reservation Fee') {
            $payment->status = 'Confirmed';

            $reservation = Reservation::where('code', $payment->reservationcode)->first();
            $reservation->status = 'Confirmed';
            $reservation->approvedby = Auth::id();

            foreach (EventVenue::where('reservationcode', $reservation->code)->get() as $ev) {
                array_push($eventvenues, $ev->venuecode);
            }
            
            $reservationsToBeCancelled = Reservation::join('tbleventvenue', 'tblreservations.code', '=', 'tbleventvenue.reservationcode')
                ->join('tblcustomers', 'tblreservations.customercode', '=', 'tblcustomers.code')
                ->select('tblreservations.code', 'tblreservations.eoemail', 'tblcustomers.email as custemail')
                ->whereIn('tbleventvenue.venuecode', $eventvenues)
                ->where('tblreservations.eventdate', $reservation->eventdate)
                ->groupBy('tblreservations.code', 'tblreservations.eoemail', 'custemail')
                ->where('reservationcode', '!=', $reservation->code)
                ->get();
            
            foreach ($reservationsToBeCancelled as $res) {
                \Mail::to($res->custemail)->cc($res->eoemail)->send(new SlotTaken($res));
                $event = Reservation::where('code', $res->code)->first();
                $event->cancelGrounds = 'Slot Taken';
                $event->status = "Cancelled";
                $event->save();
                $event->delete();
            }
            
            $payment->save();
            $reservation->save();
        }

        $payment->status = 'Confirmed';
        $payment->save();
        $reservation = Reservation::where('code', $payment->reservationcode)->first();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Confirmed Payment - ' . $payment->paymentcode . ' for ' . $payment->reservationcode . ' - ' . $reservation->eventtitle,
            'date' => date('Y-m-d h:i:s'),
        ]);

        DB::table('tblreservations')->where('code', $payment->reservationcode)->decrement('balance', $payment->amount);
        DB::table('tblreservations')->where('code', $payment->reservationcode)->increment('paid', $payment->amount);
        $reservation = Reservation::where('code', $payment->reservationcode)->firstOrFail();
        $customer = Customer::where('code', $reservation->customercode)->select('email')->firstOrFail();
        
        \Mail::to($customer)->cc($reservation->eoemail)->send(new PaymentVerified($customer, $reservation, $payment));

        return redirect()->route('admin.payments.index')->with(['success' => 'Payment for reservation confirmed.']);
    }
    

    public function reject($id)
    {
        $payment = Payment::find($id);
        $payment->status = 'Rejected';
        $payment->save();

        $reservation = Reservation::where('code', $payment->reservationcode)->firstOrFail();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Rejected Payment - ' . $payment->paymentcode . ' for ' . $payment->reservationcode . ' - ' . $reservation->eventtitle,
            'date' => date('Y-m-d h:i:s'),
        ]);

        $customer = Customer::where('code', $reservation->customercode)->select('email')->firstOrFail();

        \Mail::to($customer)->cc($reservation->eoemail)->send(new PaymentRejected($customer, $reservation, $payment));

        return redirect()->route('admin.payments.index')->with(['success' => 'Payment for reservation rejected.']);
    }
}
