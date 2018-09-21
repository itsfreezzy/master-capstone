@extends('layouts.adminlayout')

@section('styles')
<style>
    .table>thead>tr>th{
        background: #202020;
        color: white;
        text-align: center;
    }
</style>
@endsection

@section('content-header')
    <h1>
        Billing Statement
        <small>Release the billing with or without comment/s.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="/admin/reservations">Reservation</a></li>
        <li class="active">Release Billing Statement</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-md-offset-2 col-md-8">
        <div class="box box-primary">
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Billing Statement</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="printable" style="border: 23px black">
                    <header>
                        <h2 class="text-center"><strong>UNILAB Bayanihan Center</strong></h2>
                        <h2 class="text-center"><strong>Billing Statement</strong></h2>
                    </header>

                    <div class="content">
                        {{-- Brief Discussion of Reservation Info --}}
                        <div class="row" style="">
                            <p><strong>Full name:</strong> {{ $customer->name }}</p>
                            <p><strong>Customer Type:</strong> {{ $customer->type }}</p>
                            <p><strong>Event Title:</strong> {{ $reservation->eventtitle }}</p>
                            <p><strong>Event Date:</strong> {{ date('F d, Y', strtotime($reservation->eventdate)) }}</p>
                            <p style="padding-top: 0%; margin-top:0%"><strong>Event Time:</strong> {{ date('h:iA', strtotime($reservationinfo->timestart)) }} - {{ date('h:iA', strtotime($reservationinfo->timeend)) }}</p>
                        </div>

                        {{-- Event Venue Table --}}
                        <div class="row">
                            <table class="table table-stripped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th style="width: 25%">Function Room</th>
                                        <th style="width: 25%">Floor Area</th>
                                        <th style="width: 25%">Whole Day Rate</th>
                                        <th style="width: 25%">Half Day Rate</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($eventvenues as $eventvenue)
                                    <tr>
                                        {{-- date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 5 ? number_format($eventvenue->wholedayrate, 2) : number_format($eventvenue->halfdayrate, 2) --}}
                                        <td>{{ $eventvenue->name }}</td>
                                        <td>{{ number_format($eventvenue->floorarea, 2) }} Sq. M.</td>
                                        <td>PhP {{ number_format($eventvenue->wholedayrate, 2) }}</td>
                                        <td>PhP {{ number_format($eventvenue->halfdayrate, 2) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3" class="text-right" style="background: lightgrey; vertical-align: middle">
                                            <strong>Event Duration & Cost Type</strong>
                                        </td>
                                        <td colspan="1">{{ date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h }} hours and {{ date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->i }} minutes <br> {{ date_diff(date_create($reservationinfo->timeend), date_create($reservationinfo->timestart))->h > 5 ? 'Whole Day Rate' : 'Half Day Rate' }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-right" style="background: lightgrey;">
                                            <strong>Total</strong>
                                        </td>
                                        <td>PhP {{ number_format($eventgrandtotal, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Event Equipment Table --}}
                        <div class="row">
                            <table class="table table-stripped table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th style="width: 25%">Equipment</th>
                                        <th style="width: 25%">Rate</th>
                                        <th style="width: 25%">Quantity</th>
                                        <th style="width: 25%">Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @foreach($eventequipments as $eventequipment)
                                    <tr>
                                        <td>{{ $eventequipment->name }}</td>
                                        <td style="vertical-align: middle">PhP {{ number_format($eventequipment->totalprice / $eventequipment->qty, 2) }}</td>
                                        <td style="vertical-align: middle">{{ $eventequipment->qty }}</td>
                                        <td style="vertical-align: middle">PhP{{ number_format($eventequipment->totalprice, 2) }}</td>
                                    </tr>
                                    @endforeach

                                    <tr>
                                        <td colspan="3" class="text-right" style="background: lightgrey;">
                                            <strong>Total</strong>
                                        </td>
                                        <td>PhP {{ number_format($equipgrandtotal, 2) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        {{-- Grand Total --}}
                        <div class="row">
                            <table class="table table-stripped table-bordered">
                                <thead>
                                    <th style="width: 75%" class="text-right" >Grand Total</th>
                                    <td style="width: 25%" class="text-center"><strong>PhP {{ number_format($equipgrandtotal + $eventgrandtotal, 2) }}</strong></td>
                                </thead>
                            </table>
                        </div>
                        
                        {{-- Comments Section --}}
                        <div class="row">
                            <p><strong><h3>Comments/Remarks:</h3></strong></p>
                            <div class="well">
                                <textarea form="mainform" name="comment" id="comment" cols="30" rows="10" style="width:100%; resize:none"></textarea>
                            </div>
                        </div>
                    </div>

                    <footer class="footer">
                        <p class="text-center">8008 Pioneer Street, Kapitoly, Pasig City, Metro Manila, Philippines <br> (02) 858-1978 | (02) 858-1985</p>
                    </footer>
                </div>

                <form action="{{ route('admin.submit.billing', ['id' => $reservation->id]) }}" method="POST" id="mainform">
                    @csrf
                    <button type="submit" class="btn btn-lg btn-success btn-block"> <i class="fa fa-check"></i> SUBMIT</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection