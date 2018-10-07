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
use App\Charts\SampleChart;
use Illuminate\Support\Facades\DB;
use App\FunctionHall, App\MeetingRoom, App\EventVenue;

class AdminNavigationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::join('tblreservationinfo', 'tblreservations.reservationinfoid', '=', 'tblreservationinfo.id')->where('status', '=', 'Confirmed')->get();
        $functionhalls = FunctionHall::all();
        $meetingrooms = MeetingRoom::all();
        $eventvenues = EventVenue::all();
        $reservationcontacts = ReservationContact::all();
        $customers = Customer::all();
        $pendingreservations = Reservation::where('status', 'Pending')->count();

        $events = [];
        foreach ($reservations as $reservation) {
            $events[] = \Calendar::event(
                $reservation->eventtitle,
                false,
                $reservation->eventdate . ' ' . $reservation->timestart,
                $reservation->eventdate . ' ' . $reservation->timeend,
                $reservation->id,
                [
                    'url' => '/admin/reservations/view/' . $reservation->id,
                ]
            );
        }

        $calendar = \Calendar::addEvents($events);

        return view('admin.dashboard')->with([
            'reservations' => $reservations,
            'reservationcontacts' => $reservationcontacts,
            'customers' => $customers,
            'calendar' => $calendar,
            'pendingreservations' => $pendingreservations,
            'monthreservations' => $this->thisMonthReservations(),
            'functionhalls' => $functionhalls,
            'meetingrooms' => $meetingrooms,
            'eventvenues' => $eventvenues,
        ]);
    }

    
    public function customers()
    {
        $customers = Customer::withTrashed()->orderBy('created_at', 'DESC')->get();

        return view('admin.customers')->with([
            'customers' => $customers,
        ]);
    }


    public function backupandrestore()
    {
        return view('admin.backupandrestore');
    }


    public function balance()
    {
        $reservations = DB::table('tblreservations')
                        ->join('tblcustomers', 'tblcustomers.code', '=', 'tblreservations.customercode')
                        ->where('tblreservations.status', 'Confirmed')
                        ->get();
                        
        return view('admin.balance')->with([
            'reservations' => $reservations,
        ]);
    }


    public function userlog()
    {
        $userlogs = UserLog::join('users', 'users.id', '=', 'userlog.userid')->get();
        return view('admin.user-log')->with([
            'userlogs' => $userlogs,
        ]);
    }

    public function goToProfilePage() 
    {
        return view('admin.profile');
    }
    

    public function thisMonthReservations()
    {
        return DB::table('tblreservations')
                    ->select('*')
                    ->where('eventdate', '>=', date('Y-m-01'))
                    ->where('eventdate', '<=', date('Y-m-t'))
                    ->where('status', '!=', 'Pending')
                    ->where('status', '!=', 'Done')
                    ->count();
    }

    public function getCustomer(Request $request)
    {
        return Customer::withTrashed()->where('id', $request->id)->firstOrFail();
    }
}