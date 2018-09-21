<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Auth;
use App\ReservationInfo;
use App\ReservationContact;
use App\Reservation;
use App\Customer;
use App\EventVenue;
use App\EventEquipment;
use App\FunctionHall;
use App\MeetingRoom;
use PDF;
use App\User;
use Illuminate\Support\Facades\DB;
use App\Mail\Welcome;
use App\Mail\NewReservation;
use App\Mail\NewReservationToUser;

class ReservationInfoController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules(), $this->messages());

        if ($validator->fails()) {
            return redirect()
                    ->route('web.reservation')
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
                ->route('web.reservation')
                ->withErrors($validator)
                ->withInput()
                ->with(['error' => 'There is an already existing reservation for the date and functiom room(s) you want. Please try again.']);
        }
        
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

        $customer = new Customer;
        $customer->type = $request->customertype;
        $customer->name = $request->customername;
        $customer->tinnumber = $request->customertin;
        $customer->contactnumber = $request->customercontactno;
        $customer->username = $request->customerusername;
        $customer->email = $request->customeremailadd;
        $customer->password = bcrypt($request->customerpassword);
        $customer->save();
        $customer->code = sprintf('CUST-%04d', $customer->id);
        $customer->save();
        
        $reservation = new Reservation;
        $reservation->reservationinfoid = $reservationinfo->id;
        
        if (Auth::guard('customer')->guest()) {
            $reservation->customercode = $customer->code;
        } else {
            $reservation->customercode = Auth::guard('customer')->user()->code;
        }
        $reservation->datefiled = $request->DateFiled;
        $reservation->status = 'Pending';
        $reservation->eventorganizer = $request->EventOrganizer;
        $reservation->eocontactno = $request->EventOrganizerContactNo;
        $reservation->eoemail = $request->EventOrganizerEmail;
        $reservation->eventdate = $request->EventDate;
        $reservation->eventtitle = $request->EventTitle;
        $reservation->save();
        $reservation->code = sprintf('RES-%04d', $reservation->id);
        $reservation->save();

        foreach ($request->PrefFuncRooms as $preffuncroom) {
            $eventvenue = new EventVenue;
            $eventvenue->reservationcode = $reservation->code;
            $eventvenue->venuecode = $preffuncroom;
            $eventvenue->save();
        }

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
        
        $users = User::all();
        $cc = array();
        foreach ($users as $user) {
            array_push($cc, $user->email);
        }

        \Mail::to($customer->email)->send(new Welcome($customer));
        \Mail::to($cc)->send(new NewReservation($customer, $reservation));
        \Mail::to($customer->email)->send(new NewReservationToUser($reservation, $customer));

        return redirect()->route('web.reservation')->with(['success' => 'Reservation form successfully submitted. Please wait pay the reservation fee ASAP to guarantee your reservation. You may now log-in to your account.']);
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
            'customername' => 'required|unique:tblcustomers,name',
            'customertin' => 'required|unique:tblcustomers,tinnumber',
            'customercontactno' => 'required|unique:tblcustomers,contactnumber',
            'customeremailadd' => 'required|unique:tblcustomers,email',
            'customerusername' => 'required|unique:tblcustomers,username',
            'customerpassword' => 'required|confirmed',
            'consent' => 'accepted',
        ];
    }

    public function messages()
    {
        return [
            '' => '',
        ];
    }
}
