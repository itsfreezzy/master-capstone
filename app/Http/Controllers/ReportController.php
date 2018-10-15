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
use App\EventVenue;
use App\EventNature;
use App\MeetingRoom, App\FunctionHall;
use Illuminate\Support\Facades\DB;
use Auth;
use Validator;
use PDF;

class ReportController extends Controller
{
    public function reservation() {
        //###############################################################################################################
        // Reservations Per Function Room
        //###############################################################################################################
        $resperfunchall = DB::table('tbleventvenue')
                    ->join('tblreservations', 'tbleventvenue.reservationcode', '=', 'tblreservations.code')
                    ->join('tblreservationinfo', 'tblreservationinfo.id', '=', 'tblreservations.reservationinfoid')
                    ->join('tblfunctionhalls', 'tblfunctionhalls.code', '=', 'tbleventvenue.venuecode')
                    ->select(DB::raw('COUNT(*) as reservationctr, tblfunctionhalls.name, tbleventvenue.venuecode'))
                    ->groupBy('tbleventvenue.venuecode', 'tblfunctionhalls.name')
                    ->orderBy('reservationctr', 'DESC')
                    ->take(5)
                    ->get();
        $respermr = DB::table('tbleventvenue')
                    ->join('tblreservations', 'tbleventvenue.reservationcode', '=', 'tblreservations.code')
                    ->join('tblreservationinfo', 'tblreservationinfo.id', '=', 'tblreservations.reservationinfoid')
                    ->join('tblmeetingrooms', 'tblmeetingrooms.code', '=', 'tbleventvenue.venuecode')
                    ->select(DB::raw('COUNT(*) as reservationctr, tblmeetingrooms.name, tbleventvenue.venuecode'))
                    ->groupBy('tbleventvenue.venuecode', 'tblmeetingrooms.name')
                    ->orderBy('reservationctr', 'DESC')
                    ->take(5)
                    ->get();

        $funcrooms = array();
        $numofbookings = array();
        $colors = array();

        $resperfuncroomchart = new SampleChart;
        $resperfuncroomchart->displayAxes(false);

        foreach ($resperfunchall as $q) {
            array_push($funcrooms, $q->name);
            array_push($numofbookings, $q->reservationctr);
            array_push($colors, $this->random_color());
        }
        foreach ($respermr as $q) {
            array_push($funcrooms, $q->name);
            array_push($numofbookings, $q->reservationctr);
            array_push($colors, $this->random_color());
        }
        $resperfuncroomchart->labels($funcrooms);
        $resperfuncroomchart->dataset('SAMPLE', 'pie', $numofbookings, $colors)->backgroundColor($colors);


        //###############################################################################################################
        // Reservations Per Event Nature
        //###############################################################################################################
        $eventnatures = array();
        $reseventnatureondb = ReservationInfo::take(5)->get();//join('tblreservations', 'tblreservationinfo.id', '=', 'tblreservations.reservationinfoid')->where('status', '!=', 'Pending')->take(5)->get();
        foreach ($reseventnatureondb as $eventnature) {
            foreach (explode(",", $eventnature->eventnature) as $events) {
                $eventnatures[$events] = 0;
            }
        }

        foreach ($eventnatures as $key => $value) {
            foreach ($reseventnatureondb as $eventnature) {
                foreach (explode(",", $eventnature->eventnature) as $events) {
                    if ($key == $events) {
                        $eventnatures[$key] += 1;
                    }
                }
            }
        }

        $en = array();
        $res = array();
        $colors = array();
        $respereventnaturechart = new SampleChart;
        foreach ($eventnatures as $key => $value) {
            array_push($en, $key);
            array_push($res, $value);
            array_push($colors, $this->random_color()); 
        }
        
        $respereventnaturechart->labels($en);
        $respereventnaturechart->dataset($key, 'pie', $res)->backgroundColor($colors);

        //###############################################################################################################
        // Reservations Per Status
        //###############################################################################################################
        $resstatondb = DB::table('tblreservations')
                        ->select(DB::raw('status, count(*) as ctr'))
                        ->groupBy('status')
                        ->get();

        $status = array();
        $bookings = array();
        $colors = array();
        
        foreach ($resstatondb as $q) {
            array_push($status, $q->status);
            array_push($bookings, $q->ctr);
            array_push($colors, $this->random_color()); 
        }

        $resperstatchart = new SampleChart;
        $resperstatchart->displayAxes(false);
        $resperstatchart->labels($status);
        $resperstatchart->dataset('SAMPLE', 'pie', $bookings)->backgroundColor($colors);


        $en = ReservationInfo::select('eventnature')->get();
        $natures = [];
        foreach ($en as $e) {
            if (strpos($e, ',') !== false) {
                foreach(explode(',', $e->eventnature) as $nature) {
                    if (!in_array($nature, $natures)) {
                        array_push($natures, $nature);
                    }
                }
            } else {
                if (!in_array($e->eventnature, $natures)) {
                    array_push($natures, $e->eventnature);
                }
            }
        }

        $functionhalls = EventVenue::join('tblfunctionhalls', 'tblfunctionhalls.code', '=', 'tbleventvenue.venuecode')->select('name')->where('venuecode', 'LIKE', 'FH%')->groupBy('name')->get();
        $meetingrooms = EventVenue::join('tblmeetingrooms', 'tblmeetingrooms.code', '=', 'tbleventvenue.venuecode')->select('name')->where('venuecode', 'LIKE', 'MR%')->groupBy('name')->get();

        if($resperfuncroomchart->datasets[0]->values != NULL && $respereventnaturechart->datasets[0]->values != NULL && $resperstatchart->datasets[0]->values != NULL) {
            return view('admin.reports-reservation')->with([
                'resperfuncroomchart' => $resperfuncroomchart, 
                'respereventnaturechart' => $respereventnaturechart,
                'resperstatchart' => $resperstatchart,
                'natures' => $natures,
                'functionhalls' => $functionhalls,
                'meetingrooms' => $meetingrooms,
                'colors' => $colors,
            ]);
        } else {
            return redirect()->back()->with(['error' => 'Insufficient data to access reports.']);
        }
    }

    public function updateReservationReport(Request $request) {
        $validator = Validator::make($request->all(), [
            'daterange' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.reports.reservation')->withErrors($validator)->withInput()->with(['error' => 'Please select a date range.']);
        }

        $dates = explode('|', $request->daterange);
        $dates[0] = $dates[0]; //. ' 00:00:00';
        $dates[1] = $dates[1]; //. ' 23:59:00';

        //###############################################################################################################
        // Reservations Per Function Room
        //###############################################################################################################
        $resperfunchall = DB::table('tbleventvenue')
                    ->join('tblreservations', 'tbleventvenue.reservationcode', '=', 'tblreservations.code')
                    ->join('tblreservationinfo', 'tblreservationinfo.id', '=', 'tblreservations.reservationinfoid')
                    ->join('tblfunctionhalls', 'tblfunctionhalls.code', '=', 'tbleventvenue.venuecode')
                    ->select(DB::raw('COUNT(*) as reservationctr, tblfunctionhalls.name, tbleventvenue.venuecode'))
                    ->whereRaw('tblreservations.eventdate >= ? AND tblreservations.eventdate <= ?', $dates)
                    ->groupBy('tbleventvenue.venuecode', 'tblfunctionhalls.name')
                    ->orderBy('reservationctr', 'DESC')
                    ->take(5)
                    ->get();
        $respermr = DB::table('tbleventvenue')
                    ->join('tblreservations', 'tbleventvenue.reservationcode', '=', 'tblreservations.code')
                    ->join('tblreservationinfo', 'tblreservationinfo.id', '=', 'tblreservations.reservationinfoid')
                    ->join('tblmeetingrooms', 'tblmeetingrooms.code', '=', 'tbleventvenue.venuecode')
                    ->select(DB::raw('COUNT(*) as reservationctr, tblmeetingrooms.name, tbleventvenue.venuecode'))
                    ->whereRaw('tblreservations.eventdate >= ? AND tblreservations.eventdate <= ?', $dates)
                    ->groupBy('tbleventvenue.venuecode', 'tblmeetingrooms.name')
                    ->orderBy('reservationctr', 'DESC')
                    ->take(5)
                    ->get();

        $funcrooms = array();
        $numofbookings = array();
        $colors = array();

        $resperfuncroomchart = new SampleChart;
        $resperfuncroomchart->displayAxes(false);

        foreach ($resperfunchall as $q) {
            array_push($funcrooms, $q->name);
            array_push($numofbookings, $q->reservationctr);
            array_push($colors, $this->random_color());
        }
        foreach ($respermr as $q) {
            array_push($funcrooms, $q->name);
            array_push($numofbookings, $q->reservationctr);
            array_push($colors, $this->random_color());
        }
        $resperfuncroomchart->labels($funcrooms);
        $resperfuncroomchart->dataset('SAMPLE', 'pie', $numofbookings); 


        //###############################################################################################################
        // Reservations Per Event Nature
        //###############################################################################################################
        $eventnatures = array();
        $reseventnatureondb = ReservationInfo::join('tblreservations', 'tblreservations.reservationinfoid', '=', 'tblreservationinfo.id')->whereRaw('tblreservations.eventdate >= ? AND tblreservations.eventdate <= ?', $dates)->take(5)->get();

        foreach ($reseventnatureondb as $eventnature) {
            foreach (explode(",", $eventnature->eventnature) as $events) {
                $eventnatures[$events] = 0;
            }
        }

        foreach ($eventnatures as $key => $value) {
            foreach ($reseventnatureondb as $eventnature) {
                foreach (explode(",", $eventnature->eventnature) as $events) {
                    if ($key == $events) {
                        $eventnatures[$key] += 1;
                    }
                }
            }
        }

        $respereventnaturechart = new SampleChart;
        foreach ($eventnatures as $key => $value) {
            $respereventnaturechart->dataset($key, 'bar', array($value));
        }
        // dd($respereventnaturechart);


        //###############################################################################################################
        // Reservations Per Status
        //###############################################################################################################
        $resstatondb = DB::table('tblreservations')
                        ->select(DB::raw('status, count(*) as ctr'))
                        ->whereRaw('tblreservations.eventdate >= ? AND tblreservations.eventdate <= ?', $dates)
                        ->groupBy('status')
                        ->get();

        $status = array();
        $bookings = array();
        
        foreach ($resstatondb as $q) {
            array_push($status, $q->status);
            array_push($bookings, $q->ctr);   
        }

        $resperstatchart = new SampleChart;
        $resperstatchart->displayAxes(false);
        $resperstatchart->labels($status);
        $resperstatchart->dataset('SAMPLE', 'pie', $bookings);

        $en = ReservationInfo::select('eventnature')->get();
        $natures = [];
        foreach ($en as $e) {
            if (strpos($e, ',') !== false) {
                foreach(explode(',', $e->eventnature) as $nature) {
                    if (!in_array($nature, $natures)) {
                        array_push($natures, $nature);
                    }
                }
            } else {
                if (!in_array($e->eventnature, $natures)) {
                    array_push($natures, $e->eventnature);
                }
            }
        }

        $functionhalls = EventVenue::join('tblfunctionhalls', 'tblfunctionhalls.code', '=', 'tbleventvenue.venuecode')->select('name')->where('venuecode', 'LIKE', 'FH%')->groupBy('name')->get();
        $meetingrooms = EventVenue::join('tblmeetingrooms', 'tblmeetingrooms.code', '=', 'tbleventvenue.venuecode')->select('name')->where('venuecode', 'LIKE', 'MR%')->groupBy('name')->get();

        if($resperfuncroomchart->datasets[0]->values != NULL && $respereventnaturechart->datasets[0]->values != NULL && $resperstatchart->datasets[0]->values != NULL) {
            return view('admin.reports-reservation')->with([
                'resperfuncroomchart' => $resperfuncroomchart, 
                'respereventnaturechart' => $respereventnaturechart,
                'resperstatchart' => $resperstatchart,
                'natures' => $natures,
                'functionhalls' => $functionhalls,
                'meetingrooms' => $meetingrooms,
            ]);
        } else {
            return redirect()->back()->with(['error' => 'No data exists on the specified parameters.']);
        }
    }

    public function generateReservationReport(Request $request) {
        $validator = Validator::make($request->all(), [
            'daterange' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.reports.reservation')->withErrors($validator)->withInput()->with(['error' => 'Please select a date range.']);
        }

        $dates = explode('|', $request->daterange);
        $dates[0] = $dates[0] . ' 00:00:00';
        $dates[1] = $dates[1] . ' 23:59:59';

        //###############################################################################################################
        // Reservations Per Function Room
        //###############################################################################################################
        $resperfunchall = DB::table('tbleventvenue')
                    ->join('tblreservations', 'tbleventvenue.reservationcode', '=', 'tblreservations.code')
                    ->join('tblreservationinfo', 'tblreservationinfo.id', '=', 'tblreservations.reservationinfoid')
                    ->join('tblfunctionhalls', 'tblfunctionhalls.code', '=', 'tbleventvenue.venuecode')
                    ->select(DB::raw('COUNT(*) as reservationctr, tblfunctionhalls.name, tbleventvenue.venuecode'))
                    ->whereRaw('tblreservations.datefiled >= ? AND tblreservations.datefiled <= ?', $dates)
                    ->groupBy('tbleventvenue.venuecode', 'tblfunctionhalls.name')
                    ->orderBy('reservationctr', 'DESC')
                    ->get();
        $respermr = DB::table('tbleventvenue')
                    ->join('tblreservations', 'tbleventvenue.reservationcode', '=', 'tblreservations.code')
                    ->join('tblreservationinfo', 'tblreservationinfo.id', '=', 'tblreservations.reservationinfoid')
                    ->join('tblmeetingrooms', 'tblmeetingrooms.code', '=', 'tbleventvenue.venuecode')
                    ->select(DB::raw('COUNT(*) as reservationctr, tblmeetingrooms.name, tbleventvenue.venuecode'))
                    ->whereRaw('tblreservations.datefiled >= ? AND tblreservations.datefiled <= ?', $dates)
                    ->groupBy('tbleventvenue.venuecode', 'tblmeetingrooms.name')
                    ->orderBy('reservationctr', 'DESC')
                    ->get();

        //###############################################################################################################
        // Reservations Per Event Nature
        //###############################################################################################################
        $eventnatures = array();
        $reseventnatureondb = ReservationInfo::join('tblreservations', 'tblreservations.reservationinfoid', '=', 'tblreservationinfo.id')
                ->whereRaw('tblreservations.datefiled >= ? AND tblreservations.datefiled <= ?', $dates)
                ->get();

        foreach ($reseventnatureondb as $eventnature) {
            foreach (explode(",", $eventnature->eventnature) as $events) {
                $eventnatures[$events] = 0;
            }
        }

        foreach ($eventnatures as $key => $value) {
            foreach ($reseventnatureondb as $eventnature) {
                foreach (explode(",", $eventnature->eventnature) as $events) {
                    if ($key == $events) {
                        $eventnatures[$key] += 1;
                    }
                }
            }
        }


        //###############################################################################################################
        // Reservations Per Status
        //###############################################################################################################
        $resstatondb = DB::table('tblreservations')
                        ->select(DB::raw('status, count(*) as ctr'))
                        ->whereRaw('tblreservations.datefiled >= ? AND tblreservations.datefiled <= ?', $dates)
                        ->groupBy('status')
                        ->get();

        $en = ReservationInfo::select('eventnature')->get();
        $natures = [];
        foreach ($en as $e) {
            if (strpos($e, ',') !== false) {
                foreach(explode(',', $e->eventnature) as $nature) {
                    if (!in_array($nature, $natures)) {
                        array_push($natures, $nature);
                    }
                }
            } else {
                if (!in_array($e->eventnature, $natures)) {
                    array_push($natures, $e->eventnature);
                }
            }
        }

        $functionhalls = FunctionHall::withTrashed()->get();//EventVenue::join('tblfunctionhalls', 'tblfunctionhalls.code', '=', 'tbleventvenue.venuecode')->select('name')->where('venuecode', 'LIKE', 'FH%')->groupBy('name')->get();
        $meetingrooms = MeetingRoom::withTrashed()->where('name', 'NOT LIKE', '%old%')->get();//EventVenue::join('tblmeetingrooms', 'tblmeetingrooms.code', '=', 'tbleventvenue.venuecode')->select('name')->where('venuecode', 'LIKE', 'MR%')->groupBy('name')->get();
        $totalreservations = Reservation::withTrashed()->count();
        $resperstatus = Reservation::withTrashed()->select(DB::raw('count(*) as total, status'))->groupBy('status')->get();

        $arrMeetingRooms = array();
        foreach ($meetingrooms as $mr) {
            $arrMeetingRooms[$mr->name] = 0;
            foreach ($respermr as $res) {
                if ($mr->code == $res->venuecode) {
                    $arrMeetingRooms[$mr->name] = $res->reservationctr;
                    break;
                }
            }
        }

        $arrFunctionHalls= array();
        foreach ($functionhalls as $fh) {
            $arrFunctionHalls[$fh->name] = 0;
            foreach ($resperfunchall as $res) {
                if ($fh->code == $res->venuecode) {
                    $arrFunctionHalls[$fh->name] = $res->reservationctr;
                    break;
                }
            }
        }
        // dd([
        //     'resperfunchall' => $resperfunchall,
        //     'respermr' => $respermr,
        //     'eventnatures' => $eventnatures,
        //     'functionhalls' => $functionhalls,
        //     'meetingrooms' => $meetingrooms,
        //     'totalreservations' => $totalreservations,
        // ]);

        $pdf = PDF::loadView('forms.reservation-report', compact('resperfunchall', 'respermr', 'eventnatures', 'functionhalls', 'meetingrooms', 'totalreservations', 'dates', 'resperstatus', 'arrFunctionHalls', 'arrMeetingRooms'));
        return $pdf->stream('reservation-report_' . time() . '.pdf'); 
        
        // return view('admin.reports-reservation')->with([
        //     'resperfunchall' => $resperfunchall,
        //     'respermr' => $respermr,
        //     'eventnatures' => $eventnatures,
        //     'functionhalls' => $functionhalls,
        //     'meetingrooms' => $meetingrooms,
        //     'totalreservations' => $reservations,
        // ]);
        
        // $pdf = PDF::loadView('forms.billing-statement', compact('customer', 'reservation', 'reservationinfo', 'eventvenues', 'eventequipments', 'equipgrandtotal', 'eventgrandtotal', 'title', 'prefix', 'discountedPrice'));
        // return $pdf->stream($reservation->code . '_' . time() . '.pdf');
    }
    
    public function sales() {
        $reservationfee = 0;
        $downpayment = 0;
        $fullpayment = 0;
        $depositcharge = 0;
        $corkagefee = 0;
        
        $payments = Reservation::join('tblpayments', 'tblreservations.code', '=', 'tblpayments.reservationcode')
                    ->join('tblreservationinfo', 'tblreservations.reservationinfoid', '=', 'tblreservationinfo.id')
                    ->where('paymenttype', '!=', 'Security Deposit')
                    ->where('isAccredited', '=', '1')
                    ->get();
        foreach ($payments as $payment) {
            if ($payment->paymenttype == 'Reservation Fee') {
                $reservationfee += $payment->amount;
            } else if ($payment->paymenttype == '50% Downpayment') {
                $downpayment += $payment->amount;
            } else if ($payment->paymenttype == '50% Full Payment') {
                $fullpayment += $payment->amount;
            } else {
                $depositcharge += $payment->amount;
            }
        }
        // $payments = Payment::join('tblreservations', 'tblreservations.code', '=', 'tblpayments.reservationcode')->get();

        // $cancelledreservations = Reservation::onlyTrashed()
        //                         ->join('tblpayments', 'tblpayments.reservationcode', '=', 'tblreservations.code')
        //                         ->whereIn('paymenttype', ['50% Downpayment', '50% Full Payment'])
        //                         ->select('reservationcode', DB::raw('tblreservations.deleted_at as deleted_at'), 'tblreservations.eventdate', DB::raw('SUM(amount) as paid'))
        //                         ->groupBy('reservationcode', 'deleted_at', 'eventdate')
        //                         ->get();
        // foreach ($cancelledreservations as $res) {
        //     if (date_diff(date_create($res->eventdate . "00:00:00"), date_create($res->deleted_at))->m > 1 ) {
        //         $cancelledressales += ($res->paid - 5000) / 2;
        //     } else {
        //         $cancelledressales += ($res->paid - 5000);
        //     }
        // }
        // dd($cancelledreservations);
        //###############################################################################################################
        // Sales Percentage Chart
        //###############################################################################################################
        // $eventnatures = array();
        // $reseventnatureondb = ReservationInfo::join('tblreservations', 'tblreservations.reservationinfoid', '=', 'tblreservationinfo.id')->whereRaw('tblreservations.eventdate >= ? AND tblreservations.eventdate <= ?', $dates)->take(5)->get();

        // foreach ($reseventnatureondb as $eventnature) {
        //     foreach (explode(",", $eventnature->eventnature) as $events) {
        //         $eventnatures[$events] = 0;
        //     }
        // }

        // foreach ($eventnatures as $key => $value) {
        //     foreach ($reseventnatureondb as $eventnature) {
        //         foreach (explode(",", $eventnature->eventnature) as $events) {
        //             if ($key == $events) {
        //                 $eventnatures[$key] += 1;
        //             }
        //         }
        //     }
        // }

        // $respereventnaturechart = new SampleChart;
        // foreach ($eventnatures as $key => $value) {
        //     $respereventnaturechart->dataset($key, 'bar', array($value))->color($this->random_color());
        // }

        return view('admin.reports-sales');
    }

    public function updateSalesReport(Request $request) {
        dd($request->all());
    }

    public function generateSalesReport(Request $request) {

        // $pdf = PDF::loadView('forms.billing-statement', compact('customer', 'reservation', 'reservationinfo', 'eventvenues', 'eventequipments', 'equipgrandtotal', 'eventgrandtotal', 'title', 'prefix', 'discountedPrice'));
        // return $pdf->stream($reservation->code . '_' . time() . '.pdf');
    }

    public function miscIndex() {
        return view('admin.reports-misc');
    }

    public function generateReservationHistory(Request $request) {
        $date = explode('|', $request->daterange);
        $date[0] = $date[0] . ' 00:00:00';
        $date[1] = $date[1] . ' 23:59:59';

        $reservations = Reservation::withTrashed()
                            ->join('tblreservationinfo', 'tblreservations.reservationinfoid', '=', 'tblreservationinfo.id')
                            ->join('tblcustomers', 'tblreservations.customercode', '=', 'tblcustomers.code')
                            ->select(DB::raw('tblreservations.code, tblreservations.eventtitle, tblcustomers.name, tblreservations.datefiled, tblreservations.status, tblreservations.eventdate, tblreservationinfo.timestart, tblreservationinfo.timeend'))
                            ->whereRaw('tblreservations.created_at >= ? AND tblreservations.created_at <= ?', $date)
                            ->orderBy('tblreservations.created_at', 'desc')
                            ->get();
        
        $pdf = PDF::loadView('forms.reservation-history', compact('reservations', 'date'));
        return $pdf->stream('Reservation History - ' . $date[0] . ' - ' . $date[1] . '.pdf');

        // $pdf = PDF::loadView('forms.billing-statement', compact('customer', 'reservation', 'reservationinfo', 'eventvenues', 'eventequipments', 'equipgrandtotal', 'eventgrandtotal', 'title', 'prefix', 'discountedPrice'));
        // return $pdf->stream($reservation->code . '_' . time() . '.pdf');
    }

    public function generatePaymentHistory(Request $request) {
        $date = explode('|', $request->daterange);
        $date[0] = $date[0] . ' 00:00:00';
        $date[1] = $date[1] . ' 23:59:59';

        $payments = Payment::withTrashed()
                        ->join('tblreservations', 'tblreservations.code', '=', 'tblpayments.reservationcode')
                        ->join('tblcustomers', 'tblreservations.customercode', '=', 'tblcustomers.code')
                        ->select(DB::raw('tblpayments.*, tblreservations.eventtitle, tblcustomers.name, tblreservations.total as totalbal'))
                        ->whereRaw('tblreservations.created_at >= ? AND tblreservations.created_at <= ?', $date)
                        ->orderBy('tblreservations.code', 'asc')
                        ->orderBy('tblpayments.created_at', 'desc')
                        ->get();
        $totpayments = count($payments);

        $pdf = PDF::loadView('forms.payment-history', compact('payments', 'date', 'totpayments'));
        return $pdf->stream('Payment History - ' . $date[0] . ' - ' . $date[1] . '.pdf');
    }

    public function generateCustWithBal(Request $request) {
        $date = explode('|', $request->daterange);
        $date[0] = $date[0] . ' 00:00:00';
        $date[1] = $date[1] . ' 23:59:59';

        $customers = Customer::withTrashed()
                        ->join('tblreservations', 'tblreservations.customercode', '=', 'tblcustomers.code')
                        ->join('tblpayments', 'tblreservations.code', '=', 'tblpayments.reservationcode')
                        ->select(DB::raw('tblcustomers.code, tblcustomers.name, tblreservations.eventtitle, tblreservations.balance'))
                        ->whereRaw('tblreservations.created_at >= ? AND tblreservations.created_at <= ?', $date)
                        ->where('balance', '>', '0')
                        ->orderBy('tblcustomers.code', 'asc')
                        ->orderBy('tblreservations.code', 'asc')
                        ->get();
        dd($customers);

        // $pdf = PDF::loadView('forms.billing-statement', compact('customer', 'reservation', 'reservationinfo', 'eventvenues', 'eventequipments', 'equipgrandtotal', 'eventgrandtotal', 'title', 'prefix', 'discountedPrice'));
        // return $pdf->stream($reservation->code . '_' . time() . '.pdf');
    }

    public function generateActivityLog(Request $request) {
        $date = explode('|', $request->daterange);
        $date[0] = $date[0] . ' 00:00:00';
        $date[1] = $date[1] . ' 23:59:59';
        
        $activitylog = UserLog::join('users', 'users.id', '=', 'userlog.userid')
                        ->whereRaw('userlog.date >= ? AND userlog.date <= ?', $date)
                        ->orderBy('date', 'desc')
                        ->get();

        $pdf = PDF::loadView('forms.activitylog', compact('activitylog', 'date'));
        return $pdf->stream('ActivityLog' . $date[0] . ' - ' . $date[1] . '.pdf');

        // $pdf = PDF::loadView('forms.billing-statement', compact('customer', 'reservation', 'reservationinfo', 'eventvenues', 'eventequipments', 'equipgrandtotal', 'eventgrandtotal', 'title', 'prefix', 'discountedPrice'));
        // return $pdf->stream($reservation->code . '_' . time() . '.pdf');
    }

    function random_color_part() {
        return str_pad( dechex( mt_rand( 0, 255 ) ), 2, '0', STR_PAD_LEFT);
    }
    
    function random_color() {
        return '#' . $this->random_color_part() . $this->random_color_part() . $this->random_color_part();
    }
}
