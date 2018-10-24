<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\EventNature;
use App\EventSetup;
use App\Amenity;
use App\Equipment;
use App\FunctionHall;
use App\MeetingRoom;
use App\Caterer;
use App\CatEmail;
use App\CatContact;
use App\CatContactPerson;
use App\Timeblock;
use App\Reservation;
use App\ReservationContact;
use App\ReservationInfo;
use App\Customer;
use App\User;
use App\EventVenue;
use Illuminate\Support\Facades\DB;
use PDF;
use Auth;
use Crypt;
use App\Mail\PaymentVerified, App\Mail\NewReservationToUser;
use App\Payment, Artisan, Storage, Log, Alert, Session;
use App\Mail\NonPaymentOfReservation, App\Mail\NonPaymentOfDP, App\Mail\PaymentReminder;

class WebNavigationController extends Controller
{
    public function index()
    {
        return view('website.index');
    }


    public function goToAmenities()
    {
        $amenities = Amenity::all();

        return view('website.amenities')->with([
            'amenities' => $amenities,
        ]);
    }


    public function goToCaterers()
    {
        $caterers = Caterer::all();
        $catemails = CatEmail::all();
        $catcontacts = CatContact::all();
        $catcontactpersons = CatContactPerson::all();

        return view('website.caterers')->with([
            'caterers' => $caterers,
            'catemails' => $catemails,
            'catcontacts' => $catcontacts,
            'catcontactpersons' => $catcontactpersons,
        ]);
    }


    public function goToAboutUs()
    {
        return view('website.aboutus');
    }


    public function goToContactUs()
    {
        return view('website.contactus');
    }


    public function goToSchedules()
    {
        $reservations = Reservation::join('tblreservationinfo', 'tblreservations.reservationinfoid', '=', 'tblreservationinfo.id')->where('status', '=', 'Confirmed')->orWhere('status', '=', 'Pending')->get();
        $reservationcontacts = ReservationContact::all();
        $reservationinfos = ReservationInfo::all();
        $customers = Customer::all();
        $pendingreservations = Reservation::where('status', 'Pending')->count();
        $timeblocks = Timeblock::all();
        $funchalls = FunctionHall::all();
        $meetrooms = MeetingRoom::where('name', 'not like', '%old%')->get();
        $meetrmdiscount = DB::table('tblmeetroomdiscount')->get();
        $fhdiscount = DB::table('tblfunchallsdiscount')->get();

        $events = [];
        foreach ($reservations as $reservation) {
            switch($reservation->status) {
                case 'Pending':
                    $color = '#C0C0C0';// array_push($colors, '#f9f9f9');
                    break;
                case 'Confirmed':
                    $color = '#428bca';// array_push($colors, '#428bca');
                    break;
                case 'Done':
                    $color = '#5cb85c';// array_push($colors, '#5cb85c');
                    break;
                case 'Cancelled':
                    $color = '#d9534f';// array_push($colors, '#d9534f');
                    break;
            }

            $events[] = \Calendar::event(
                $reservation->eventtitle,
                false,
                $reservation->eventdate . ' ' . $reservation->timestart,
                $reservation->eventdate . ' ' . $reservation->timeend,
                $reservation->id,
                [
                    'color' => $color
                ]
            );
        }

        $calendar = \Calendar::addEvents($events)
                ->setOptions([
                    'header' => [
                        'left' => 'today prev,next',
                        'center' => 'title',
                        'right' => 'month, agendaWeek',
                    ],
                ])
                ->setCallbacks([
                    'dayClick' => 'function(date, jsEvent, view) {
                        var date = new Date(date);
                        var options = { weekday: "long", year: "numeric", month: "long", day: "numeric" };

                        $("#seldate").val(date.toLocaleDateString("en-US"));
                        $("#dispdate").val(date.toLocaleDateString("en-US", options));
                        $("#modalShow").modal("show");
                    }'
                ]);

        return view('website.schedules')->with([
            'reservations' => $reservations,
            'reservationcontacts' => $reservationcontacts,
            'reservationinfos' => $reservationinfos,
            'customers' => $customers,
            'calendar' => $calendar,
            'pendingreservations' => $pendingreservations,
            'monthreservations' => $this->thisMonthReservations(),
            'timeblocks' => $timeblocks,
            'meetrooms' => $meetrooms,
            'funchalls' => $funchalls,
            'meetrmdiscount' => $meetrmdiscount,
            'fhdiscount' => $fhdiscount,
        ]);
    }


    public function goToRates()
    {
        $meetingrooms = MeetingRoom::all();
        $functionhalls = FunctionHall::all();
        $equipments = Equipment::all();
        $timeblocks = Timeblock::all();

        return view('website.rates')->with([
            'meetingrooms' => $meetingrooms,
            'functionhalls' => $functionhalls,
            'equipments' => $equipments,
            'timeblocks' => $timeblocks,
        ]);
    }
    

    public function goToReservation()
    {   
        if (!Auth::guard('customer')->guest()) {
            return redirect()->route('client.reservationform');
        }
        
        $meetingrooms = DB::table('tblmeetingrooms')
                            ->join('tbltimeblock', 'tblmeetingrooms.timeblockcode', '=', 'tbltimeblock.code')
                            ->select(DB::raw('tblmeetingrooms.*, tbltimeblock.timestart, tbltimeblock.timeend'))
                            ->get();
        $functionhalls = FunctionHall::all();
        $equipments = Equipment::all();
        $eventnatures = EventNature::all();
        $eventsetups = EventSetup::all();
        $caterers = Caterer::all();
        $timeblocks = Timeblock::all();

        return view('website.reservation')->with([
            'meetingrooms' => $meetingrooms,
            'functionhalls' => $functionhalls,
            'equipments' => $equipments,
            'eventnatures' => $eventnatures,
            'eventsetups' => $eventsetups,
            'caterers' => $caterers,
            'timeblocks' => $timeblocks,
        ]);
    }


    public function getReservedRooms(Request $request)
    {
        $unavailablerooms = DB::table('tbleventvenue')
                                ->join('tblreservations', 'tblreservations.code', '=', 'tbleventvenue.reservationcode')
                                ->join('tblreservationinfo', 'tblreservations.reservationinfoid', '=', 'tblreservationinfo.id')
                                ->select('venuecode', 'timestart', 'timeend')
                                ->where('tblreservations.status', 'like', 'Confirmed')
                                ->where('tblreservations.eventdate', 'like', $request->input('date'))
                                ->get()->toArray();

        return $unavailablerooms;
    }


    public function getOnEditReservedRooms(Request $request)
    {
        $unavailablerooms = DB::table('tbleventvenue')
                                ->join('tblreservations', 'tblreservations.code', '=', 'tbleventvenue.reservationcode')
                                ->select('venuecode')
                                ->where('tblreservations.status', 'like', 'Confirmed')
                                ->where('tblreservations.eventdate', 'like', $request->input('date'))
                                ->where('tblreservations.id', '!=', $request->reservationid)
                                ->get()->toArray();

        return $unavailablerooms;
    }


    public function thisMonthReservations()
    {
        return DB::table('tblreservations')
                    ->select('*')
                    ->where('eventdate', '>=', date('Y-m-01'))
                    ->where('eventdate', '<=', date('Y-m-t'))
                    ->where('status', '=', 'Pending')
                    ->count();
    }


    public function getRoomsAvailability(Request $request) {
        if ($request->type == 'FH') {
            $date = date('Y-m-d', strtotime($request->date));
            $reservedfunchalls = DB::table('tbleventvenue')
                                ->join('tblreservations', 'tblreservations.code', '=', 'tbleventvenue.reservationcode')
                                // ->where('tblreservations.status', 'like', 'Confirmed')
                                ->where('tblreservations.eventdate', $date)
                                ->where('tbleventvenue.venuecode', 'like', 'FH%')
                                ->get();

            return $reservedfunchalls;
        } else if ($request->type == 'MR') {
            $date = date('Y-m-d', strtotime($request->date));
            $times = Timeblock::select('timestart', 'timeend')->where('code', $request->timeblock)->firstOrFail();

            $reservedmeetrooms = DB::table('tbleventvenue')
                                ->join('tblreservations', 'tblreservations.code', '=', 'tbleventvenue.reservationcode')
                                ->join('tblreservationinfo', 'tblreservations.reservationinfoid', '=', 'tblreservationinfo.id')
                                ->where('tblreservations.status', 'like', 'Confirmed')
                                ->where('tblreservations.eventdate', $date)
                                ->where('tbleventvenue.venuecode', 'like', 'MR%')
                                ->where('tblreservationinfo.timestart', $times->timestart)
                                ->where('tblreservationinfo.timeend', $times->timeend)
                                ->get();

            return $reservedmeetrooms;
        } else {
            return 'error';
        }
    }

    function random_color_part() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }
    
    function random_color() {
        return '#' . $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }

    
    public function test() {
        $reservations = Reservation::all();
        $admins = User::select('email')->get();

        foreach ($reservations as $reservation) {
            $customer = Customer::where('code', $reservation->customercode)->first();  
            $date = strtotime(date('Y-m-d h:i:s', strtotime($reservation->created_at)));
            $date = strtotime('+7 days', $date);

            if ($reservation->status == 'Pending') {
                $days = 8 - date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days;
                
                if (date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days <= 0) {
                    $reservation->status = 'Cancelled';
                    $reservation->cancelGrounds = 'Non-payment of reservation fee';
                    $reservation->delete();

                    \Mail::to($customer->email)->cc($admins->toArray())->send(new NonPaymentOfReservation($reservation, $customer));
                } else if (date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days <= 7 && date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days > 0){ 
                    \Mail::to($customer->email)->cc($admins->toArray())->send(new PaymentReminder($reservation, 'Reservation Fee', $days, $customer));
                }
            } else if ($reservation->status == 'Confirmed') {
                $dp = true;
                $fp = true;
                $sd = true;
                $deadline = strtotime(date('Y-m-d h:i:s', strtotime($reservation->created_at)));
                $deadline = strtotime('+30 days', $deadline);
                $deadline = date('Y-m-d h:i:s', $deadline);

                if (count( Payment::where('reservationcode', $reservation->code)->where('paymenttype', '50% Downpayment')->get() ) == 0) {
                    $dp = false;

                    if (date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days > 30 && date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->invert == 0) {
                        $reservation->status = 'Cancelled';
                        $reservation->cancelGrounds = 'Non-payment of 50% Downpayment';
                        $reservation->delete();
                        
                        \Mail::to($customer->email)->cc($admins->toArray())->send(new NonPaymentOfDP($reservation, $customer));
                    } else if (date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days <= 30 && date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days > 23) {
                        $days = 30 - date_diff(date_create($reservation->created_at), date_create(date('Y-m-d h:i:s')))->days;
                        \Mail::to($customer->email)->cc($admins->toArray())->send(new PaymentReminder($reservation, '50% Down Payment', $days, $customer));
                    }
                }

                if ($dp && count( Payment::where('reservationcode', $reservation->code)->where('paymenttype', '50% Full Payment')->get() ) == 0) {
                    $deadline = strtotime(date('Y-m-d h:i:s', strtotime($reservation->eventdate)));
                    $deadline = strtotime('-30 days', $deadline);
                    $deadline = date('Y-m-d h:i:s', $deadline);
                    $fp = false;

                    if (date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days <= 7 && date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days >= 0 && date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->invert == 1) {
                        $days = date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days;
                        \Mail::to($customer->email)->cc($admins->toArray())->send(new PaymentReminder($reservation, '50% Full Payment', $days, $customer));
                    }
                }

                if ($dp && $fp && count( Payment::where('reservationcode', $reservation->code)->where('paymenttype', 'Security Deposit')->get() ) == 0) {
                    $deadline = strtotime(date('Y-m-d h:i:s', strtotime($reservation->eventdate)));
                    $deadline = strtotime('-15 days', $deadline);
                    $deadline = date('Y-m-d h:i:s', $deadline);
                    $sd = false;

                    if (date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days <= 7 && date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days >= 0 && date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->invert == 1) {
                        $days = date_diff(date_create($deadline), date_create(date('Y-m-d h:i:s')))->days;
                        \Mail::to($customer->email)->cc($admins->toArray())->send(new PaymentReminder($reservation, 'Security Deposit', $days, $customer));
                    }
                }
            }
        }
    }
}