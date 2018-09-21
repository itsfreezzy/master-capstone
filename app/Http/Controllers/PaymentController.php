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
        if ($payment->paymenttype == 'Reservation Fee') {
            $payment->status = 'Confirmed';

            $reservation = Reservation::where('code', $payment->reservationcode)->first();
            $reservation->status = 'Confirmed';
            $reservation->approvedby = Auth::id();
            
            $payment->save();
            $reservation->save();
        }

        $payment->status = 'Confirmed';
        $payment->save();

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
        $customer = Customer::where('code', $reservation->customercode)->select('email')->firstOrFail();

        \Mail::to($customer)->cc($reservation->eoemail)->send(new PaymentRejected($customer, $reservation, $payment));

        return redirect()->route('admin.payments.index')->with(['success' => 'Payment for reservation rejected.']);
    }
}
