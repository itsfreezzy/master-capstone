<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\UserLog;
use App\User;
use App\Reservation;
use App\ReservationContact;
use App\ReservationInfo;
use App\Customer;
use App\Payment;
use App\Equipment;
use App\FunctionHall, App\MeetingRoom;
use App\EventVenue, App\EventEquipment;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Mail\CancelReservation;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::withTrashed()
                            ->join('tblcustomers', 'tblcustomers.code', '=', 'tblreservations.customercode')
                            ->join('tblreservationinfo', 'tblreservationinfo.id', '=', 'tblreservations.reservationinfoid')
                            ->select('tblreservations.*', 'tblcustomers.name', 'tblreservationinfo.*')
                            ->orderBy('tblreservations.created_at', 'DESC')
                            ->get();
                            
        $reservationcontacts = ReservationContact::all();
        $customers = Customer::all();

        return view('admin.reservations')->with([
            'reservations' => $reservations,
            'reservationcontacts' => $reservationcontacts,
            // 'reservationinfos' => $reservationinfos,
            'customers' => $customers,
        ]);
    }


    public function showReservationInfo($id) 
    {
        $reservation = Reservation::find($id);
        $reservationinfo = ReservationInfo::where('id', $reservation->reservationinfoid)->first();
        $eventvenues = EventVenue::where('reservationcode', $reservation->code)->get();
        $eventequipments = EventEquipment::where('reservationcode', $reservation->code)->get();
        $reservationcontacts = ReservationContact::where('reservationcode', $reservation->code)->get();
        $contacts = $this->getReservationContacts($reservationcontacts);

        $grandtot = 0;
        foreach ($eventequipments as $eq) {
            $grandtot += $eq->totalprice;
        }

        $equipments = Equipment::all();
        $functionhalls = FunctionHall::all();
        $meetingrooms = MeetingRoom::all();
        
        return view('admin.view-reservation')->with([
            'contacts' => $contacts,
            'reservation' => $reservation,
            'reservationinfo' => $reservationinfo,
            'eventvenues' => $eventvenues,
            'eventequipments' => $eventequipments,
            'functionhalls' => $functionhalls,
            'meetingrooms' => $meetingrooms,
            'equipments' => $equipments,
            'grandtot' => $grandtot,
        ]);
    }
    

    public function cancelReservation(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        $reservation->cancelGrounds = $request->cancelGrounds;
        $reservation->status = 'Cancelled';
        $reservation->save();
        $customer = Customer::where('code', $reservation->customercode)->firstOrFail();
        $reservation->delete();

        UserLog::create([
            'userid' => Auth::guard('web')->user()->id,
            'action' => 'Cancelled Reservation - ' . $reservation->code . ' - ' . $reservation->eventtitle,
            'date' => date('Y-m-d h:i:s'),
        ]);

        try {
            \Mail::to($customer->email)->send(new CancelReservation($reservation, $customer));
            \Mail::to($reservation->eoemail)->send(new CancelReservation($reservation, $customer));
        } catch (Exception $e) {
            return redirect()->route('admin.reservation')->with(['error' => $e]);
        }

        return redirect()->route('admin.reservation')->with(['success' => 'Reservation successfully cancelled. The client will be notified on their cancellation of reservation.']);
    }


    public function releaseBilling($id)
    {
        $reservation = Reservation::find($id);
        $customer = Customer::where('code', $reservation->customercode)->first();
        $reservationinfo = ReservationInfo::where('id', $reservation->reservationinfoid)->first();
        $eventequipments = DB::table('tbleventequipments')
                            ->join('tblequipments', 'tbleventequipments.equipmentcode', '=', 'tblequipments.code')
                            ->where('tbleventequipments.reservationcode', $reservation->code)
                            ->get();
        $equipgrandtotal = 0;
        foreach ($eventequipments as $eventequipment) {
            $equipgrandtotal += $eventequipment->totalprice;
        }
        $eventgrandtotal = 0;
        
        $prefix = explode("-", EventVenue::where('reservationcode', $reservation->code)->first()->venuecode);
        if ($prefix[0] == 'FH') {
            $eventvenues = EventVenue::join('tblfunctionhalls', 'tbleventvenue.venuecode', '=', 'tblfunctionhalls.code')
                            ->where('reservationcode', $reservation->code)
                            ->get();
        } elseif ($prefix[0] == 'MR') {
            $eventvenues = EventVenue::join('tblfunctionhalls', 'tbleventvenue.venuecode', '=', 'tblfunctionhalls.code')
                            ->where('reservationcode', $reservation->code)
                            ->get();
        }
        
        
        if (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 5) {
            foreach ($eventvenues as $eventvenue) {
                $eventgrandtotal += $eventvenue->wholedayrate;
            }
        } else {
            foreach ($eventvenues as $eventvenue) {
                $eventgrandtotal += $eventvenue->half;
            }
        }

        $title = $reservation->code . '_' . time() . '.pdf';

        return view('admin.reservation-contract', compact('customer', 'reservation', 'reservationinfo', 'eventvenues', 'eventequipments', 'equipgrandtotal', 'eventgrandtotal', 'title'));
    }


    public function submitBilling(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        $reservation->billingComment = $request->comment;
        $reservation->hasBilling = 1;
        $reservation->save();

        return redirect()->route('admin.reservation')->with(['success' => 'Billing statement for reservation ' . $reservation->code . ' successfully released.']);
    }


    public function cancelAndDeleteReservation($id)
    {
        $reservation = Reservation::find($id);
        $reservation->approvedBy = null;
        $reservation->delete();
    }


    public function getReservationContacts($reservationcontacts) 
    {
        $contacts[0]['name'] = $reservationcontacts[0]['contactname'];
        $contacts[0]['telno'] = $reservationcontacts[0]['telno'];
        $contacts[0]['mobno'] = $reservationcontacts[0]['mobno'];
        $contacts[0]['email'] = $reservationcontacts[0]['email'];
        $contacts[0]['address'] = $reservationcontacts[0]['address'];
        if (count($reservationcontacts) > 1) {
            $contacts[1]['name'] = $reservationcontacts[1]['contactname'];
            $contacts[1]['telno'] = $reservationcontacts[1]['telno'];
            $contacts[1]['mobno'] = $reservationcontacts[1]['mobno'];
            $contacts[1]['email'] = $reservationcontacts[1]['email'];
            $contacts[1]['address'] = $reservationcontacts[1]['address'];
        }

        return $contacts;
    }
}
