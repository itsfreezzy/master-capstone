<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Filesystem\Filesystem;
use App\EventNature, App\EventSetup, App\EventVenue, App\EventEquipment, App\Amenity, App\Equipment, App\FunctionHall, App\MeetingRoom, App\Caterer, App\CatEmail, App\CatContact, App\CatContactPerson, App\Timeblock, App\Reservation, App\ReservationContact, App\ReservationInfo, App\Payment, App\Customer;
use Validator;
use PDF;
use App\Mail\NewPayment;
use App\Mail\NewReservation;
use App\Mail\UpdatePayment;
use App\Mail\UpdateReservation;
use App\Mail\Welcome;
use App\Mail\NewReservationToUser;
use App\User;
use Anam\Captcha\Captcha;

class ClientController extends Controller
{
    use AuthenticatesUsers;

    public function __construct() {
        $this->middleware('auth:customer');
    }
    
    public function index() {
        $reservation = Reservation::where([
            ['customercode', '=', Auth::guard('customer')->user()->code],
            ['balance', '<>', '0'],
            ['status', '<>', 'Pending'],
            ['status', '<>', 'Done'],
            ['status', '<>', 'Cancelled']
        ])->get();

        $totbal = Reservation::where([
            ['customercode', '=', Auth::guard('customer')->user()->code],
            ['balance', '<>', '0'],
            ['status', '=', 'Confirmed']
        ])->sum('balance');

        $nextevent = Reservation::where('status', '<>', ['Pending', 'Done'])->oldest('eventdate')->first();
        if ($nextevent) {
            $daystilnextevent = date_diff(date_create($nextevent->eventdate), date_create(now()))->days;
        } else {
            $daystilnextevent = 0;
        }

        return view('customer.index')->with([
            'reservation' => $reservation,
            'totbal' => $totbal,
            'daystilnextevent' => $daystilnextevent,
        ]);
    }

    public function goToReservationsPage() {
        $reservations = Reservation::withTrashed()->get();

        return view('customer.home')->with([
            'reservations' => $reservations,
        ]);
    }

    public function goToSettingsPage() {
        return view('customer.settings');
    }
    
    public function goToPaymentsPage() {
        $payments = Payment::join('tblreservations', 'tblreservations.code', '=', 'tblpayments.reservationcode')->where('tblreservations.customercode', Auth::guard('customer')->user()->code)->get();
        $customers = Customer::withTrashed()->get();
        $reservations = Reservation::where('customercode', Auth::guard('customer')->user()->code)->withTrashed()->get();

        return view('customer.payments')->with([
            'reservations' => $reservations,
            'customers' => $customers,
            'payments' => $payments,
        ]);
    }

    public function goToBalancePage() {
        $reservations = DB::table('tblreservations')
                        ->join('tblcustomers', 'tblcustomers.code', '=', 'tblreservations.customercode')
                        ->select('tblreservations.*')
                        ->where('customercode', '=', Auth::guard('customer')->user()->code)
                        ->where('status', 'Confirmed')
                        ->orderBy('created_at', 'DESC')
                        ->get();

        return view('customer.balance')->with([
            'reservations' => $reservations,
        ]);
    }

    public function goToProfilePage() {
        return view('customer.profile');
    }

    ############################################################################################################################
    ## New Reservation Functions
    ############################################################################################################################
    public function showReservationForm(){
        $meetingrooms = MeetingRoom::join('tbltimeblock', 'tbltimeblock.code', '=', 'tblmeetingrooms.timeblockcode')->select('tblmeetingrooms.*', 'tbltimeblock.timestart', 'tbltimeblock.timeend')->get();
        $functionhalls = FunctionHall::all();
        $equipments = Equipment::all();
        $eventnatures = EventNature::all();
        $eventsetups = EventSetup::all();
        $caterers = Caterer::all();
        $timeblocks = Timeblock::all();

        return view('customer.reservation')->with([
            'meetingrooms' => $meetingrooms,
            'functionhalls' => $functionhalls,
            'equipments' => $equipments,
            'eventnatures' => $eventnatures,
            'eventsetups' => $eventsetups,
            'caterers' => $caterers,
            'timeblocks' => $timeblocks,
        ]);
    }
    
    public function submitReservationForm(Request $request, Captcha $captcha){
        // dd($request->PrefFuncRooms[0]);

        $response = $captcha->check($request);
        if (! $response->isVerified()) {
            return redirect()->route('client.reservationform')->withInput()->with(['error' => $response->errors()]);
        }

        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            dd($validator);
            return redirect()
                    ->route('client.reservationform')
                    ->withErrors($validator)
                    ->withInput();
        }

        $reservationexists = DB::table('tblreservations')
                                ->join('tblreservationinfo', 'tblreservations.reservationinfoid', '=', 'tblreservationinfo.id')
                                ->join('tbleventvenue', 'tblreservations.code', '=', 'tbleventvenue.reservationcode')
                                ->select('tblreservations.*', 'tblreservationinfo.*')
                                ->where('tblreservations.status', 'like', 'Confirmed')
                                ->where('tblreservations.eventdate', 'like', $request->EventDate)
                                ->whereIn('tbleventvenue.venuecode', $request->PrefFuncRooms)
                                ->get();

        if (count($reservationexists) > 0) {
            return redirect()
                ->route('client.reservationform')
                ->withErrors($validator)
                ->withInput()
                ->with(['error' => 'There is an already existing reservation for the date and functiom room(s) you want. Please try again.']);
        }

        
        // if (explode('-', $request->PrefFuncRooms[0])[0] == 'MR') {
        //     dd('MR');
        // } else {
        //     dd('FH');
        // }
        
        $reservationinfo = $this->saveReservationInfo($request);
        $reservation = $this->saveReservation($request, $reservationinfo);
        $customer = Customer::where('code', $reservation->customercode)->firstOrFail();
        $eventvenues = $this->saveReservationVenue($request, $reservation);
        $eventequipments = $this->saveReservationEquipment($request, $reservation);
        $reservationcontacts = $this->saveReservationContact($request, $reservation);
        $res = $this->computeTotalPrice($reservation, $reservationinfo); //returned an instance of current reservation
        $this->notifyReservationToAdmins($reservation, $customer);

        $funcroom = EventVenue::where('reservationcode', $reservation->code)->firstOrFail();
        $funcroom = explode('-', $funcroom->venuecode);
        if ($funcroom[0] == 'FH') {
            \Mail::to($customer->email)->send(new NewReservationToUser($reservation, $customer, 'FH'));
            \Mail::to($reservation->eoemail)->send(new NewReservationToUser($reservation, $customer, 'FH'));
        } elseif ($funcroom[0] == 'MR') {
            \Mail::to($customer->email)->send(new NewReservationToUser($reservation, $customer, 'MR'));
            \Mail::to($reservation->eoemail)->send(new NewReservationToUser($reservation, $customer, 'FH'));
        }
        
        return redirect()->route('client.landingpage')->with(['success' => 'Reservation form successfully submitted. Please wait pay the reservation fee ASAP to guarantee your reservation.']);
    }

    public function computeTotalPrice(Reservation $reservation, ReservationInfo $reservationinfo) {
        $equipgrandtotal = 0;
        $eventgrandtotal = 0;
        $eventequipments = DB::table('tbleventequipments')
                            ->join('tblequipments', 'tbleventequipments.equipmentcode', '=', 'tblequipments.code')
                            ->where('tbleventequipments.reservationcode', $reservation->code)
                            ->get();
        foreach ($eventequipments as $eventequipment) {
            $equipgrandtotal += $eventequipment->totalprice;
        }
        $prefix = explode("-", EventVenue::where('reservationcode', $reservation->code)->first()->venuecode);
        if ($prefix[0] == 'FH') {
            $eventvenues = EventVenue::join('tblfunctionhalls', 'tbleventvenue.venuecode', '=', 'tblfunctionhalls.code')
                            ->where('reservationcode', $reservation->code)
                            ->get();

            $price = array();
            foreach ($eventvenues as $ev) {
                array_push($price, $ev->venuecode);
            }
            $price = implode("|", $price);
            $discountedPrice = DB::table('tblfunchallsdiscount')->where('code', $price)->first();

            if ($discountedPrice) {
                if (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 10) {
                    $eventgrandtotal += $discountedPrice->wholedayrate;
                    $eventgrandtotal += $discountedPrice->hourlyexcessrate * (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h - 10);
                } elseif (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 5) {
                    $eventgrandtotal += $discountedPrice->wholedayrate;
                } elseif (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 1) {
                    $eventgrandtotal += $discountedPrice->halfdayrate;
                }
            } else {
                if (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 10) {
                    foreach ($eventvenues as $eventvenue) {
                        $eventgrandtotal += $eventvenue->wholedayrate;
                    }

                    foreach ($eventvenues as $eventvenue) {
                        $eventgrandtotal += $eventvenue->hourlyexcessrate * (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h - 10);
                    }
                } elseif (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 5) {
                    foreach ($eventvenues as $eventvenue) {
                        $eventgrandtotal += $eventvenue->wholedayrate;
                    }
                } elseif (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 1) {
                    foreach ($eventvenues as $eventvenue) {
                        $eventgrandtotal += $eventvenue->halfdayrate;
                    }
                }
            }

            
        } elseif ($prefix[0] == 'MR') {
            $eventvenues = EventVenue::join('tblmeetingrooms', 'tbleventvenue.venuecode', '=', 'tblmeetingrooms.code')
                            ->where('reservationcode', $reservation->code)
                            ->get();

            $price = array();
            foreach ($eventvenues as $ev) {
                array_push($price, $ev->venuecode);
            }
            $price = implode("|", $price);
            $discountedPrice = DB::table('tblmeetroomdiscount')->where('code', $price)->first();

            if ($discountedPrice) {
                $eventgrandtotal += $discountedPrice->rateperblock;
            } else {
                foreach ($eventvenues as $eventvenue) {
                    $eventgrandtotal += $eventvenue->rateperblock;
                }
            }
            
            
        } else {
            return dd("ERROR");
        }

        if ($discountedPrice) {
            if (date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->i < 45 && date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->ingress))->h == 0) {

            }
            elseif (date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->i >= 45 && date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->h == 0) {
                $eventgrandtotal += $discountedPrice->ineghourlyrate;
            } else {
                $eventgrandtotal += $discountedPrice->ineghourlyrate * date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->h;
            }

            if (date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->i < 45 && date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->h == 0) {

            }
            elseif (date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->i >= 45 && date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->h == 0) {
                $eventgrandtotal += $discountedPrice->ineghourlyrate;
            } else {
                $eventgrandtotal += $discountedPrice->ineghourlyrate * date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->h;
            }
        } else {
            foreach ($eventvenues as $eventvenue) {
                if (date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->i < 45 && date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->ingress))->h == 0) {

                }
                elseif (date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->i >= 45 && date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->h == 0) {
                    $eventgrandtotal += $eventvenue->ineghourlyrate;
                } else {
                    $eventgrandtotal += $eventvenue->ineghourlyrate * date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->h;
                }
            }

            foreach ($eventvenues as $eventvenue) {
                if (date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->i < 45 && date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->h == 0) {

                }
                elseif (date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->i >= 45 && date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->h == 0) {
                    $eventgrandtotal += $eventvenue->ineghourlyrate;
                } else {
                    $eventgrandtotal += $eventvenue->ineghourlyrate * date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->h;
                }
            }
        }

        // session(['eventtotal' => $eventgrandtotal, 'equiptotal' => $equipgrandtotal]);

        $reservation->total = ($eventgrandtotal + $equipgrandtotal + 15000) * 1.12;
        $reservation->balance = ($eventgrandtotal + $equipgrandtotal + 15000) * 1.12;
        $reservation->paid = 0;
        $reservation->save();

        return $reservation;
    }

    public function saveReservationInfo(Request $request) {
        $reservationinfo = new ReservationInfo;
        $reservationinfo->numofattendees = $request->NumAttendees;
        $reservationinfo->timestart = $request->TimeStart;
        $reservationinfo->timeend = $request->TimeEnd;
        $reservationinfo->timeingress = $request->IngressTime;
        $reservationinfo->timeeggress = $request->EggressTime;
        $reservationinfo->dateingress = $request->IngressDate;
        $reservationinfo->dateeggress = $request->EggressDate;
        $reservationinfo->eventsetup = $request->EventSetup;
        if (is_array($request->EventNature)) {
            $reservationinfo->eventnature = implode(",", $request->EventNature);
        } else {
            $reservationinfo->eventnature = $request->EventNature;
        }
        $reservationinfo->caterer = $request->CatererName;
        $reservationinfo->isaccredited = $request->isAccredited;
        $reservationinfo->save();

        return $reservationinfo;
    }

    public function saveReservation(Request $request, $reservationinfo) {
        $reservation = new Reservation;
        $reservation->reservationinfoid = $reservationinfo->id;
        $reservation->customercode = Auth::guard('customer')->user()->code;
        // $reservation->datefiled = $request->DateFiled;
        $reservation->status = 'Pending';
        $reservation->eventorganizer = $request->EventOrganizer;
        $reservation->eocontactno = $request->EventOrganizerContactNo;
        $reservation->eoemail = $request->EventOrganizerEmail;
        $reservation->eventdate = $request->EventDate;
        $reservation->eventtitle = $request->EventTitle;
        $reservation->save();
        $reservation->code = sprintf('RES-%04d', $reservation->id);
        $reservation->save();

        return $reservation;
    }

    public function saveReservationVenue(Request $request, $reservation) {

        if (count($request->PrefFuncRooms) == 1) {
            foreach (explode('|', $request->PrefFuncRooms[0]) as $preffuncroom) {
                $eventvenue = new EventVenue;
                $eventvenue->reservationcode = $reservation->code;
                $eventvenue->venuecode = $preffuncroom;
                $eventvenue->save();
            }
        } else {
            foreach ($request->PrefFuncRooms as $preffuncroom) {
                $eventvenue = new EventVenue;
                $eventvenue->reservationcode = $reservation->code;
                $eventvenue->venuecode = $preffuncroom;
                $eventvenue->save();
            }
        }

        return EventVenue::where('reservationcode', $reservation->code)->get();
    }

    public function saveReservationEquipment(Request $request, $reservation) {
        $equipments = $request->equipments;
        $quantity = $request->quantity;
        $total = $request->total;
        
        for ($ctr = 0; $ctr < count($equipments); $ctr++) {
            $eventeq = new EventEquipment;
            $eventeq->reservationcode = $reservation->code;
            $eventeq->equipmentcode = $equipments[$ctr];
            $eventeq->qty = $quantity[$ctr];
            $eventeq->totalprice = $total[$ctr];
            $eventeq->save();
        }

        return EventEquipment::where('reservationcode', $reservation->code)->get();
    }

    public function saveReservationContact(Request $request, $reservation) {
        $reservationcontact = new ReservationContact;
        $reservationcontact->reservationcode = $reservation->code;
        $reservationcontact->contactname = $request->primcontactinfo['name'];
        $reservationcontact->telno = $request->primcontactinfo['telno'];
        $reservationcontact->mobno = $request->primcontactinfo['mobno'];
        $reservationcontact->email = $request->primcontactinfo['email'];
        $reservationcontact->address = $request->primcontactinfo['address'];
        $reservationcontact->save();
        
        if ($request->seccontactinfo['name'] != NULL) {
            $reservationcontact = new ReservationContact;
            $reservationcontact->reservationcode = $reservation->code;
            $reservationcontact->contactname = $request->seccontactinfo['name'];
            $reservationcontact->telno = $request->seccontactinfo['telno'];
            $reservationcontact->mobno = $request->seccontactinfo['mobno'];
            $reservationcontact->email = $request->seccontactinfo['email'];
            $reservationcontact->address = $request->seccontactinfo['address'];
            $reservationcontact->save();
        }

        return ReservationContact::where('reservationcode', $reservation->code);
    }

    public function notifyReservationToAdmins(Reservation $reservation, Customer $customer) {
        $users = User::all();
        $cc = array();
        foreach ($users as $user) {
            array_push($cc, $user->email);
        }
        
        $funcroom = EventVenue::where('reservationcode', $reservation->code)->firstOrFail();
        $funcroom = explode('-', $funcroom->venuecode);
        if ($funcroom[0] == 'FH') {
            \Mail::to($cc)->send(new NewReservation($customer, $reservation, 'FH'));
        } elseif ($funcroom[0] == 'MR') {
            \Mail::to($cc)->send(new NewReservation($customer, $reservation, 'MR'));
        }
    }
    ############################################################################################################################
    ## //New Reservation Functions
    ############################################################################################################################


    ############################################################################################################################
    ## Payment Functions
    ############################################################################################################################
    public function submitNewPayment(Request $request)
    {
        $messages = [

        ];

        $validator = Validator::make($request->all(), [
            'reservationcode' => 'required',
            'paymenttype' => 'required',
            'paymentamount' => 'required',
            'paymentdate' => 'required',
            'paymentproof' => 'required|max:2',
            'paymentproof.*' => 'image|max:19999',
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('client.payments')
                    ->with([
                        'showAddModal' => true
                    ])
                    ->withErrors($validator)
                    ->withInput();
        }
        
        $files = '';
        $links = '';
        // Handle File Upload
        if($request->hasFile('paymentproof')) {
            foreach ($request->paymentproof as $proof) {
                // Get file name with extension
                $fileNameWithExt = $proof->getClientOriginalName();
                // Get file name without extension
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // Get file extension
                $extension = $proof->clientExtension();
                // New file name to store to DB
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // // Upload the image
                // // $path = $proof->storeAs($this->destinationPath(), $fileNameToStore);
                $s3 = \Storage::disk('s3');
                $filePath = $this->destinationPath() . '/' . $fileNameToStore;
                $s3->put($filePath, file_get_contents($proof, 'public'));
                $files .= $s3->url($filePath, file_get_contents($proof, 'public')) . '|';
                $links .= $filePath . '|';
            }
        }
        
        $payment = new Payment;
        $payment->reservationcode = $request->reservationcode;
        $payment->paymenttype = $request->paymenttype;
        $payment->paymentdate = $request->paymentdate;
        $payment->amount = $request->paymentamount;
        $payment->status = 'Pending';
        $payment->proof = $files;
        $payment->proofdir = $links;
        $payment->save();
        $payment->paymentcode = sprintf('PMT-%05d', $payment->id);
        $payment->save();

        $users = User::all();
        $cc = array();
        foreach ($users as $user) {
            array_push($cc, $user->email);
        }
        $reservation = Reservation::where('code', $payment->reservationcode)->firstOrFail();
        $customer = Customer::where('code', $reservation->customercode)->firstOrFail();
        \Mail::to($cc)->send(new NewPayment($reservation, $customer, $payment));
        
        return redirect()->route('client.payments')->with(['success' => 'Payment successfully added. Wait for the confirmation of the admin to finalize the payment.']);
    }

    public function updatePayment(Request $request, $id)
    {
        $messages = [

        ];

        $validator = Validator::make($request->all(), [
            'editpaymentamount' => 'required',
            'editpaymentdate' => 'required',
            'editpaymentproof' => 'required|max:2',
            'editpaymentproof.*' => 'image|max:19999',
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('client.payments')
                    ->with([
                        'showAddModal' => true
                    ])
                    ->withErrors($validator)
                    ->withInput();
        }

        $payment = Payment::find($id);
        $payment->paymentdate = $request->editpaymentdate;
        $payment->amount = $request->editpaymentamount;
        $payment->status = 'Pending';
        
        foreach (explode("|", $payment->proofdir) as $dir) {
            if ($dir != '' || $dir != null) {
                Storage::disk('s3')->delete($dir);
            }
        }

        $files = '';
        $links = '';
        // Handle File Upload
        if($request->hasFile('editpaymentproof')) {
            foreach ($request->editpaymentproof as $proof) {
                // Get file name with extension
                $fileNameWithExt = $proof->getClientOriginalName();
                // Get file name without extension
                $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                // Get file extension
                $extension = $proof->clientExtension();
                // New file name to store to DB
                $fileNameToStore = $filename.'_'.time().'.'.$extension;
                // Upload the image
                // $path = $proof->storeAs($this->destinationPath(), $fileNameToStore);
                $s3 = \Storage::disk('s3');
                $filePath = $this->destinationPath() . '/' . $fileNameToStore;
                $s3->put($filePath, file_get_contents($proof, 'public'));
                $files .= $s3->url($filePath, file_get_contents($proof, 'public')) . '|';
                $links .= $filePath . '|';
            }
        } else {
            $payment->save();

            return redirect()->route('client.payments')->with(['success' => 'Payment successfully updated. Wait for the confirmation of the admin to finalize the payment.']);
        }
        
        $payment->proof = $files;
        $payment->proofdir = $links;
        $payment->save();

        $users = User::all();
        $cc = array();
        foreach ($users as $user) {
            array_push($cc, $user->email);
        }
        $reservation = Reservation::where('code', $payment->reservationcode)->firstOrFail();
        $customer = Customer::where('code', $reservation->customercode)->firstOrFail();
        \Mail::to($cc)->send(new UpdatePayment($reservation, $customer, $payment));

        return redirect()->route('client.payments')->with(['success' => 'Payment successfully updated. Wait for the confirmation of the admin to finalize the payment.']);
    }
    ############################################################################################################################
    ## //Paymentt Functions
    ############################################################################################################################


    public function cancelReservation($id)
    {
        $reservation = Reservation::find($id);
        $reservation->status = "Cancelled";
        $reservation->save();
        $reservation->delete();
            
        return redirect()->route('client.landingpage')->with(['success' => 'Reservation successfully cancelled.']);
    }

    public function undoReservationCancellation($id)
    {
        $reservation = Reservation::find($id);
        $isConfirmed = false;
        $reservation->status = "Pending";
        
        foreach (Payment::all() as $payment) {
            if ($payment->reservationcode == $reservation->code) {
                $isConfirmed = true;
                $reservation->status = "Confirmed";
                break;
            }
        }

        $reservation->save();
            
        return redirect()->route('client.landingpage')->with(['success' => 'Reservation successfully restored.']);
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

        if ( Auth::guard('customer')->user()->code != $reservation->customercode) {
            return redirect()->route('client.landingpage')->with(['error' => 'Cannot access reservation information of other clients.']);
        }

        return view('customer.view-reservation')->with([
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

    public function editReservationInfo($id)
    {
        $reservation = Reservation::find($id);
        $reservationinfo = ReservationInfo::where('id', $reservation->reservationinfoid)->first();
        $eventvenues = EventVenue::where('reservationcode', $reservation->code)->get();
        $eventequipments = EventEquipment::where('reservationcode', $reservation->code)->get();
        $reservationcontacts = ReservationContact::where('reservationcode', $reservation->code)->get();
        $contacts = $this->getReservationContacts($reservationcontacts);
        $ctrEquip = count($eventequipments);

        $grandtot = 0;
        foreach ($eventequipments as $eq) {
            $grandtot += $eq->totalprice;
        }

        $meetingrooms = MeetingRoom::all();
        $functionhalls = FunctionHall::all();
        $equipments = Equipment::all();
        $eventnatures = EventNature::all();
        $eventsetups = EventSetup::all();
        $caterers = Caterer::all();
        $payments = Payment::where('reservationcode', $reservation->code)->get();

        if ( Auth::guard('customer')->user()->code != $reservation->customercode) {
            return redirect()->route('client.landingpage')->with(['error' => 'Cannot access reservation information of other clients.']);
        }


        return view('customer.edit-reservation')->with([
            'meetingrooms' => $meetingrooms,
            'functionhalls' => $functionhalls,
            'equipments' => $equipments,
            'eventnatures' => $eventnatures,
            'eventsetups' => $eventsetups,
            'caterers' => $caterers,
            'contacts' => $contacts,
            'reservation' => $reservation,
            'reservationinfo' => $reservationinfo,
            'eventvenues' => $eventvenues,
            'eventequipments' => $eventequipments,
            'ctrEquip' => $ctrEquip,
            'grandtot' => $grandtot,
            'payments' => $payments,
        ]);
    }

    public function updateReservationInfo(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'DateFiled' => 'required|',
            'EventOrganizer' => 'required|',
            'EventOrganizerContactNo' => 'required|digits:11',
            'EventOrganizerEmail' => 'required|email',
            'EventDate' => 'required|after:+3 months|',
            'EventTitle' => 'required|unique:tblreservations,eventtitle,' . $id,
            'PrefFuncRooms' => 'required|',
            'CatererName' => 'required|',
            'isAccredited' => 'required|',
            'NumAttendees' => 'required|',
            'TimeStart' => 'required|',
            'TimeEnd' => 'required|',
            'IngressTime' => 'required|before:TimeStart',
            'EggressTime' => 'required|after:TimeEnd',
            'IngressDate' => 'sometimes|nullable|before:EventDate',
            'EggressDate' => 'sometimes|nullable|after:EventDate',
            'EventNature' => 'required|',
            'equipments' => 'required|',
            'primcontactinfo.name' => 'required|',
            'primcontactinfo.address' => 'required|',
            'primcontactinfo.mobno' => 'digits:11',
            'primcontactinfo.telno' => 'sometimes|nullable|digits:7',
            'primcontactinfo.email' => 'email',
            'seccontactinfo.name' => 'sometimes|nullable|required_with:seccontactinfo.telno|required_with:seccontactinfo.mobno|required_with:seccontactinfo.email|required_with:seccontactinfo.address',
            'seccontactinfo.telno' => 'sometimes|nullable|digits:7',
            'seccontactinfo.mobno' => 'sometimes|nullable|required_with:seccontactinfo.name|digits:11',
            'seccontactinfo.email' => 'sometimes|nullable|required_with:seccontactinfo.name|email',
            'seccontactinfo.address' => 'sometimes|nullable|required_with:seccontactinfo.name|alpha_dash',
            'consent' => 'accepted',
        ], $this->messages());

        if ($validator->fails()) {
            return redirect()
                    ->route('client.edit.reservationinfo', ['id' => $id])
                    ->withErrors($validator)
                    ->withInput();
        }

        $reservationexists = DB::table('tblreservations')
                                ->join('tblreservationinfo', 'tblreservations.reservationinfoid', '=', 'tblreservationinfo.id')
                                ->join('tbleventvenue', 'tblreservations.code', '=', 'tbleventvenue.reservationcode')
                                ->select('tblreservations.*', 'tblreservationinfo.*')
                                ->where('tblreservations.status', 'like', 'Confirmed')
                                ->where('tblreservations.eventdate', 'like', $request->EventDate)
                                ->whereIn('tbleventvenue.venuecode', $request->PrefFuncRooms)
                                ->where('tblreservations.id', '!=', $id)
                                ->get();
        
        if (count($reservationexists) > 0) {
            return redirect()
                    ->route('client.edit.reservationinfo', ['id' => $id])
                    ->withErrors($validator)
                    ->withInput()
                    ->with(['error' => 'There is an already existing reservation for the date and functiom room(s) you want. Please try again.']);
            
        }

        $reservationinfo = ReservationInfo::find($id);
        $reservationinfo->numofattendees = $request->NumAttendees;
        $reservationinfo->timestart = $request->TimeStart;
        $reservationinfo->timeend = $request->TimeEnd;
        $reservationinfo->timeingress = $request->IngressTime;
        $reservationinfo->timeeggress = $request->EggressTime;
        $reservationinfo->dateingress = $request->IngressDate;
        $reservationinfo->dateeggress = $request->EggressDate;
        $reservationinfo->eventsetup = $request->EventSetup;
        if (is_array($request->EventNature)) {
            $reservationinfo->eventnature = implode(",", $request->EventNature);
        } else {
            $reservationinfo->eventnature = $request->EventNature;
        }
        $reservationinfo->caterer = $request->CatererName;
        $reservationinfo->isaccredited = $request->isAccredited;
        $reservationinfo->save();
        
        $reservation = Reservation::find($id);
        $reservation->reservationinfoid = $reservationinfo->id;
        $reservation->customercode = Auth::guard('customer')->user()->code;
        $reservation->datefiled = $request->DateFiled;
        $reservation->eventorganizer = $request->EventOrganizer;
        $reservation->eocontactno = $request->EventOrganizerContactNo;
        $reservation->eoemail = $request->EventOrganizerEmail;
        $reservation->eventdate = $request->EventDate;
        $reservation->eventtitle = $request->EventTitle;
        $reservation->save();

        EventVenue::where('reservationcode', $reservation->code)->delete();
        foreach ($request->PrefFuncRooms as $preffuncroom) {
            $eventvenue = new EventVenue;
            $eventvenue->reservationcode = $reservation->code;
            $eventvenue->venuecode = $preffuncroom;
            $eventvenue->save();
        }

        $equipments = $request->equipments;
        $quantity = $request->quantity;
        $total = $request->total;
        EventEquipment::where('reservationcode', $reservation->code)->delete();
        for ($ctr = 0; $ctr < count($equipments); $ctr++) {
            $eventeq = new EventEquipment;
            $eventeq->reservationcode = $reservation->code;
            $eventeq->equipmentcode = $equipments[$ctr];
            $eventeq->qty = $quantity[$ctr];
            $eventeq->totalprice = $total[$ctr];
            $eventeq->save();
        }
        
        $reservationcontact = ReservationContact::find($request->primcontactinfo['id']);
        $reservationcontact->reservationcode = $reservation->code;
        $reservationcontact->contactname = $request->primcontactinfo['name'];
        $reservationcontact->telno = $request->primcontactinfo['telno'];
        $reservationcontact->mobno = $request->primcontactinfo['mobno'];
        $reservationcontact->email = $request->primcontactinfo['email'];
        $reservationcontact->address = $request->primcontactinfo['address'];
        $reservationcontact->save();
        
        if ($request->seccontactinfo['name'] != NULL && $request->seccontactinfo['id'] != NULL) {
            $reservationcontact = ReservationContact::find($request->seccontactinfo['id']);
            $reservationcontact->reservationcode = $reservation->code;
            $reservationcontact->contactname = $request->seccontactinfo['name'];
            $reservationcontact->telno = $request->seccontactinfo['telno'];
            $reservationcontact->mobno = $request->seccontactinfo['mobno'];
            $reservationcontact->email = $request->seccontactinfo['email'];
            $reservationcontact->address = $request->seccontactinfo['address'];
            $reservationcontact->save();
        } elseif ($request->seccontactinfo['name'] != NULL && $request->seccontactinfo['id'] == NULL) {
            $reservationcontact = new ReservationContact;
            $reservationcontact->reservationcode = $reservation->code;
            $reservationcontact->contactname = $request->seccontactinfo['name'];
            $reservationcontact->telno = $request->seccontactinfo['telno'];
            $reservationcontact->mobno = $request->seccontactinfo['mobno'];
            $reservationcontact->email = $request->seccontactinfo['email'];
            $reservationcontact->address = $request->seccontactinfo['address'];
            $reservationcontact->save();
        } elseif ($request->seccontactinfo['name'] == NULL && $request->seccontactinfo['id'] != NULL) {
            ReservationContact::find($request->seccontactinfo['id'])->delete();
        }

        $users = User::all();
        $cc = array();
        foreach ($users as $user) {
            array_push($cc, $user->email);
        }
        $customer = Customer::where('code', $reservation->customercode)->firstOrFail();
        \Mail::to($cc)->send(new UpdateReservation($reservation, $customer));

        return redirect()->route('client.landingpage')->with(['success' => 'Reservation information successfully updated.']);
    }

    public function markReservationAsDone(Request $request, $id)
    {
        $reservation = Reservation::find($id);

        $validator = Validator::make($request->all(), [
            'posteventcharge' => 'required|numeric',
            'datereceived' => 'required|date|after_or_equal:' . $reservation->eventdate,
        ]);

        if ($validator->fails()) {
            return redirect()
                    ->route('client.landingpage')
                    ->withErrors($validator)
                    ->withInput();
        }

        $reservation = Reservation::find($id);
        $reservation->isDone = 1;
        $reservation->dateMarkedAsDone = date('Y-m-d H:i:s', time());
        $reservation->status = 'Done';
        $reservation->save();

        $payment = new Payment;
        $payment->reservationcode = $reservation->code;
        $payment->paymenttype = 'Post-Event Security Deposit Charge';
        $payment->paymentdate = $request->datereceived;
        $payment->amount = $request->posteventcharge;
        $payment->status = 'Pending';
        $payment->proof = '';
        $payment->save();
        $payment->code = sprintf("PMT-0%5d", $payment->id);
        $payment->save();

        return redirect()->route('client.landingpage')->with(['success' => 'Event/reservation successfully marked as done. Thank you for choosing the UNILAB Bayanihan Center to host your events.']);
    }

    public function printReservationInfo($id)
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

        if ( Auth::guard('customer')->user()->code != $reservation->customercode) {
            return redirect()->route('client.landingpage')->with(['error' => 'Cannot access reservation information of other clients.']);
        }

        $pdf = PDF::loadView('customer.printreservationform', compact('contacts', 'reservation', 'reservationinfo', 'eventvenues', 'eventequipments', 'functionhalls', 'meetingrooms', 'equipments', 'grandtot'))->setPaper('letter'); 
        return $pdf->stream();
    }

    public function printBillingStatement($id) {
        $reservation = Reservation::where('id', $id)->first();
        $reservationinfo = ReservationInfo::where('id', $reservation->reservationinfoid)->first();
        $customer = Customer::find(Auth::user()->id);
        $equipgrandtotal = 0;
        $eventgrandtotal = 0;
        $eventequipments = DB::table('tbleventequipments')
                            ->join('tblequipments', 'tbleventequipments.equipmentcode', '=', 'tblequipments.code')
                            ->where('tbleventequipments.reservationcode', $reservation->code)
                            ->get();
        foreach ($eventequipments as $eventequipment) {
            $equipgrandtotal += $eventequipment->totalprice;
        }
        $prefix = explode("-", EventVenue::where('reservationcode', $reservation->code)->first()->venuecode);
        if ($prefix[0] == 'FH') {
            $eventvenues = EventVenue::join('tblfunctionhalls', 'tbleventvenue.venuecode', '=', 'tblfunctionhalls.code')
                            ->where('reservationcode', $reservation->code)
                            ->get();

            $price = array();
            foreach ($eventvenues as $ev) {
                array_push($price, $ev->venuecode);
            }
            $price = implode("|", $price);
            $discountedPrice = DB::table('tblfunchallsdiscount')->where('code', $price)->first();

            if ($discountedPrice) {
                if (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 10) {
                    $eventgrandtotal += $discountedPrice->wholedayrate;
                    $eventgrandtotal += $discountedPrice->hourlyexcessrate * (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h - 10);
                } elseif (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 5) {
                    $eventgrandtotal += $discountedPrice->wholedayrate;
                } elseif (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 1) {
                    $eventgrandtotal += $discountedPrice->halfdayrate;
                }
            } else {
                if (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 10) {
                    foreach ($eventvenues as $eventvenue) {
                        $eventgrandtotal += $eventvenue->wholedayrate;
                    }

                    foreach ($eventvenues as $eventvenue) {
                        $eventgrandtotal += $eventvenue->hourlyexcessrate * (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h - 10);
                    }
                } elseif (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 5) {
                    foreach ($eventvenues as $eventvenue) {
                        $eventgrandtotal += $eventvenue->wholedayrate;
                    }
                } elseif (date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 1) {
                    foreach ($eventvenues as $eventvenue) {
                        $eventgrandtotal += $eventvenue->halfdayrate;
                    }
                }
            }

            
        } elseif ($prefix[0] == 'MR') {
            $eventvenues = EventVenue::join('tblmeetingrooms', 'tbleventvenue.venuecode', '=', 'tblmeetingrooms.code')
                            ->where('reservationcode', $reservation->code)
                            ->get();

            $price = array();
            foreach ($eventvenues as $ev) {
                array_push($price, $ev->venuecode);
            }
            $price = implode("|", $price);
            $discountedPrice = DB::table('tblmeetroomdiscount')->where('code', $price)->first();

            if ($discountedPrice) {
                $eventgrandtotal += $discountedPrice->rateperblock;
            } else {
                foreach ($eventvenues as $eventvenue) {
                    $eventgrandtotal += $eventvenue->rateperblock;
                }
            }
            
            
        } else {
            return dd("ERROR");
        }

        if ($discountedPrice) {
            if (date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->i < 45 && date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->ingress))->h == 0) {

            }
            elseif (date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->i >= 45 && date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->h == 0) {
                $eventgrandtotal += $discountedPrice->ineghourlyrate;
            } else {
                $eventgrandtotal += $discountedPrice->ineghourlyrate * date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->h;
            }

            if (date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->i < 45 && date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->h == 0) {

            }
            elseif (date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->i >= 45 && date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->h == 0) {
                $eventgrandtotal += $discountedPrice->ineghourlyrate;
            } else {
                $eventgrandtotal += $discountedPrice->ineghourlyrate * date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->h;
            }
        } else {
            foreach ($eventvenues as $eventvenue) {
                if (date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->i < 45 && date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->ingress))->h == 0) {

                }
                elseif (date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->i >= 45 && date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->h == 0) {
                    $eventgrandtotal += $eventvenue->ineghourlyrate;
                } else {
                    $eventgrandtotal += $eventvenue->ineghourlyrate * date_diff(date_create($reservationinfo->timestart), date_create($reservationinfo->timeingress))->h;
                }
            }

            foreach ($eventvenues as $eventvenue) {
                if (date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->i < 45 && date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->h == 0) {

                }
                elseif (date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->i >= 45 && date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->h == 0) {
                    $eventgrandtotal += $eventvenue->ineghourlyrate;
                } else {
                    $eventgrandtotal += $eventvenue->ineghourlyrate * date_diff(date_create($reservationinfo->timeeggress), date_create($reservationinfo->timeend))->h;
                }
            }
        }

        $title = $reservation->code . '_' . time() . '.pdf';

        $pdf = PDF::loadView('forms.billing-statement', compact('customer', 'reservation', 'reservationinfo', 'eventvenues', 'eventequipments', 'equipgrandtotal', 'eventgrandtotal', 'title', 'prefix', 'discountedPrice'));
        return $pdf->stream($reservation->code . '_' . time() . '.pdf');
    }

    public function updateProfile(Request $request) {
        $validator = Validator::make($request->all(), [
            'clientname' => 'required|string|max:150|unique:tblcustomers,name,'.Auth::guard('customer')->user()->id,
            'username' => 'required|string|max:50|unique:tblcustomers,username,'.Auth::guard('customer')->user()->id,
            'email' => 'required|string|email|max:191|unique:tblcustomers,email,'.Auth::guard('customer')->user()->id,
            'customertype' => 'required|',
            'password' => 'required|',
            'tinnumber' => 'required|unique:tblcustomers,tinnumber,'.Auth::guard('customer')->user()->id,
            'contactnumber' => 'required|digits:11|unique:tblcustomers,contactnumber,'.Auth::guard('customer')->user()->id,
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('client.show.profile')
                        ->withErrors($validator);
        }

        if (!password_verify($request->password, Auth::guard('customer')->user()->password)) {
            return redirect()->route('client.show.profile')->with(['error' => 'You have entered an incorrect password!']);
        }

        $customer = Customer::find(Auth::guard('customer')->user()->id);
        $customer->name = $request->clientname;
        $customer->username = $request->username;
        $customer->email = $request->email;
        $customer->type = $request->customertype;
        $customer->contactnumber = $request->contactnumber;

        if($customer->isDirty()) {
            $customer->save();

            return redirect()->route('client.show.profile')->with(['success' => 'Your profile has been updated!']);
        }

        return redirect()->route('client.show.profile')->with(['warning' => 'No changes have been made.']);
    }

    public function updatePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'newpassword' => 'required|string|min:5|confirmed',
            'oldpassword' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                        ->route('client.show.profile')
                        ->withErrors($validator);
        }
        
        if (!password_verify($request->oldpassword, Auth::guard('customer')->user()->password)) {
            return redirect()->route('client.show.profile')->with(['error' => 'You have entered an incorrect password!']);
        }

        $customer = Customer::find(Auth::guard('customer')->user()->id);
        $customer->password = Hash::make($request->newpassword);
        if ($customer->isDirty()) {
            $customer->save();
            
            return redirect()->route('client.show.profile')->with(['success' => 'Your password has been updated!']);
        }

        return redirect()->route('client.show.profile')->with(['warning' => 'No changes have been made.']);
    }
    
    public function destinationPath()
    {
        return 'public/' . Auth::guard('customer')->user()->code;
    }

    public function getReservationContacts($reservationcontacts) 
    {
        $contacts[0]['id'] = $reservationcontacts[0]['id'];
        $contacts[0]['name'] = $reservationcontacts[0]['contactname'];
        $contacts[0]['telno'] = $reservationcontacts[0]['telno'];
        $contacts[0]['mobno'] = $reservationcontacts[0]['mobno'];
        $contacts[0]['email'] = $reservationcontacts[0]['email'];
        $contacts[0]['address'] = $reservationcontacts[0]['address'];
        if (count($reservationcontacts) > 1) {
            $contacts[1]['id'] = $reservationcontacts[1]['id'];
            $contacts[1]['name'] = $reservationcontacts[1]['contactname'];
            $contacts[1]['telno'] = $reservationcontacts[1]['telno'];
            $contacts[1]['mobno'] = $reservationcontacts[1]['mobno'];
            $contacts[1]['email'] = $reservationcontacts[1]['email'];
            $contacts[1]['address'] = $reservationcontacts[1]['address'];
        }

        return $contacts;
    }

    public function rules()
    {
        return [
            'DateFiled' => 'required|',
            'EventOrganizer' => 'required|',
            'EventOrganizerContactNo' => 'required|digits:11',
            'EventOrganizerEmail' => 'required|email',
            'EventDate' => 'required|after:+3 months|',
            'EventTitle' => 'required|unique:tblreservations,eventtitle',
            'PrefFuncRooms' => 'required|',
            'CatererName' => 'required|',
            'isAccredited' => 'required|',
            'NumAttendees' => 'required|',
            'TimeStart' => 'required|',
            'TimeEnd' => 'required',
            'IngressTime' => 'required|before:TimeStart',
            'EggressTime' => 'required|after:TimeEnd',
            'IngressDate' => 'sometimes|nullable|required_with:EggressDate|before:EventDate',
            'EggressDate' => 'sometimes|nullable|required_with:IngressDate|after:EventDate',
            'EventNature' => 'required|',
            'equipments' => 'required|',
            'primcontactinfo.name' => 'required|',
            'primcontactinfo.address' => 'required|',
            'primcontactinfo.mobno' => 'digits:11',
            'primcontactinfo.telno' => 'sometimes|nullable|digits:7',
            'primcontactinfo.email' => 'email',
            'seccontactinfo.name' => 'sometimes|nullable|required_with:seccontactinfo.telno|required_with:seccontactinfo.mobno|required_with:seccontactinfo.email|required_with:seccontactinfo.address',
            'seccontactinfo.telno' => 'sometimes|nullable|digits:7',
            'seccontactinfo.mobno' => 'sometimes|nullable|required_with:seccontactinfo.name|digits:11',
            'seccontactinfo.email' => 'sometimes|nullable|required_with:seccontactinfo.name|email',
            'seccontactinfo.address' => 'sometimes|nullable|required_with:seccontactinfo.name|alpha_dash',
            'consent' => 'accepted',
        ];
    }

    public function messages()
    {
        return [
            '' => '',
        ];
    }

    public function test() {
        $meetingrooms = MeetingRoom::join('tbltimeblock', 'tbltimeblock.code', '=', 'tblmeetingrooms.timeblockcode')->select('tblmeetingrooms.*', 'tbltimeblock.timestart', 'tbltimeblock.timeend')->get();
        $functionhalls = FunctionHall::all();
        $equipments = Equipment::all();
        $eventnatures = EventNature::all();
        $eventsetups = EventSetup::all();
        $caterers = Caterer::all();
        $timeblocks = Timeblock::all();
        $meetrmdiscount = DB::table('tblmeetroomdiscount')->join('tbltimeblock', 'tbltimeblock.code', '=', 'tblmeetroomdiscount.timeblockcode')->select('tblmeetroomdiscount.*', 'tbltimeblock.timestart', 'tbltimeblock.timeend')->get();


        return view('customer.test')->with([
            'meetingrooms' => $meetingrooms,
            'functionhalls' => $functionhalls,
            'equipments' => $equipments,
            'eventnatures' => $eventnatures,
            'eventsetups' => $eventsetups,
            'caterers' => $caterers,
            'timeblocks' => $timeblocks,
            'meetrmdiscount' => $meetrmdiscount,
        ]);
    }
}
