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
use App\EventVenue;
use Illuminate\Support\Facades\DB;
use PDF;
use Auth;
use Crypt;
use App\Mail\PaymentVerified, App\Mail\NewReservationToUser;
use App\Payment, Artisan, Storage, Log, Alert, Session;

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
        $reservations = Reservation::join('tblreservationinfo', 'tblreservations.reservationinfoid', '=', 'tblreservationinfo.id')->where('status', '=', 'Confirmed')->get();
        $reservationcontacts = ReservationContact::all();
        $reservationinfos = ReservationInfo::all();
        $customers = Customer::all();
        $pendingreservations = Reservation::where('status', 'Pending')->count();
        $timeblocks = Timeblock::all();
        $funchalls = FunctionHall::all();
        $meetrooms = MeetingRoom::all();
        $meetrmdiscount = DB::table('tblmeetroomdiscount')->get();
        $fhdiscount = DB::table('tblfunchallsdiscount')->get();

        $events = [];
        foreach ($reservations as $reservation) {
            $events[] = \Calendar::event(
                $reservation->eventtitle,
                false,
                $reservation->eventdate . ' ' . $reservation->timestart,
                $reservation->eventdate . ' ' . $reservation->timeend,
                $reservation->id,
                [
                    // 'url' => '/admin/reservations'
                ]
            );
        }

        $calendar = \Calendar::addEvents($events)
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

    
    public function test(Request $request) {
        // $reservation = Reservation::where('code', 'RES-0003')->first();
        // $customer = Customer::where('code', $reservation->customercode)->first();
        // $reservationinfo = ReservationInfo::where('id', $reservation->reservationinfoid)->first();
        // $eventequipments = DB::table('tbleventequipments')
        //                     ->join('tblequipments', 'tbleventequipments.equipmentcode', '=', 'tblequipments.code')
        //                     ->where('tbleventequipments.reservationcode', $reservation->code)
        //                     ->get();
        // $equipgrandtotal = 0;
        // foreach ($eventequipments as $eventequipment) {
        //     $equipgrandtotal += $eventequipment->totalprice;
        // }
        // $eventgrandtotal = 0;
        
        // $prefix = explode("-", EventVenue::where('reservationcode', $reservation->code)->first()->venuecode);
        // if ($prefix[0] == 'FH') {
        //     $eventvenues = EventVenue::join('tblfunctionhalls', 'tbleventvenue.venuecode', '=', 'tblfunctionhalls.code')
        //                     ->where('reservationcode', $reservation->code)
        //                     ->get();
        // } elseif ($prefix[0] == 'MR') {
        //     $eventvenues = EventVenue::join('tblfunctionhalls', 'tbleventvenue.venuecode', '=', 'tblfunctionhalls.code')
        //                     ->where('reservationcode', $reservation->code)
        //                     ->get();
        // } else {
        //     return dd("ERROR");
        // }
        
        
        // if (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 5) {
        //     foreach ($eventvenues as $eventvenue) {
        //         $eventgrandtotal += $eventvenue->wholedayrate;
        //     }
        // } else {
        //     foreach ($eventvenues as $eventvenue) {
        //         $eventgrandtotal += $eventvenue->half;
        //     }
        // }

        // $title = $reservation->code . '_' . time() . '.pdf';

        // $pdf = PDF::loadView('forms.billing-statement', compact('customer', 'reservation', 'reservationinfo', 'eventvenues', 'eventequipments', 'equipgrandtotal', 'eventgrandtotal', 'title'));
        // return $pdf->stream();
        // return view('test');
        dd(Auth::guard('customer'));
        Session::flush();
        dd(session()->all());

        return $request->url();

        try {
            // start the backup process
            Artisan::call('backup:run');
            $output = Artisan::output();
            // log the results
            Log::info("Backpack\BackupManager -- new backup started from admin interface \r\n" . $output);
            // return the results as a response to the ajax call
            // Alert::success('New backup created');
            return '1';
        } catch (Exception $e) {
            Flash::error($e->getMessage());
            return $e->getMessage();
        }


        dd((session('equiptotal') + session('eventtotal') + 15000));

        $reservation = Reservation::where('code', 'RES-0001')->firstOrFail();
        $reservationinfo = ReservationInfo::where('id', $reservation->reservationinfoid)->first();
        dd(date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h);
        // DB::table('tblreservations')->decrement('balance', 10000);
        // DB::table('tblreservations')->increment('paid', 10000);
        $customer = Customer::where('code', $reservation->customercode)->firstOrFail();
        $payment = Payment::where('reservationcode', $reservation->code)->where('status', '!=', 'Rejected')->orderBy('created_at', 'DESC')->firstOrFail();
        return (new PaymentVerified($customer, $reservation, $payment))->render();
    }


    public function getRoomsAvailability(Request $request) {
        if ($request->type == 'FH') {
            $date = date('Y-m-d', strtotime($request->date));
            $reservedfunchalls = DB::table('tbleventvenue')
                                ->join('tblreservations', 'tblreservations.code', '=', 'tbleventvenue.reservationcode')
                                ->where('tblreservations.status', 'like', 'Confirmed')
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
}