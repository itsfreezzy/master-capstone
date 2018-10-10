@extends('layouts.adminlayout')

@section('styles')
<!-- fullCalendar -->
<script type="text/javascript" src="{{asset('adminlte/bower_components/moment/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('adminlte/bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>
<!-- fullCalendar -->
<link rel="stylesheet" href="{{asset('adminlte/bower_components/fullcalendar/dist/fullcalendar.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/bower_components/fullcalendar/dist/fullcalendar.print.min.css')}}" media="print">
<!-- DataTables -->
<link rel="stylesheet" href="adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
@endsection

@section('title')
Bayanihan Center | Dashboard
@endsection

@section('content-header')
    <h1>
        Dashboard
        <small>Control panel</small>
    </h1>
@endsection

@section('content')
@include('inc.messages')
{{--  Information Cards  --}}
<div class="row">
    <div class="col-lg-4 col-xs-4">
        <div class="small-box bg-aqua">
            <div class="inner">
            <h3>{{ $monthreservations }}</h3>

            <p>Events this month</p>
            </div>
            <div class="icon">
            <i class="ion ion-calendar"></i>
            </div>
            <a href="/admin/reservations" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-4 col-xs-4">
        <div class="small-box bg-green">
            <div class="inner">
            <h3>{{ $reservationstoday }}</h3>

            <p>Reservations Today</p>
            </div>
            <div class="icon">
            <i class="ion ion-android-more-horizontal"></i>
            </div>
            <a href="/admin/reservations" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-4 col-xs-4">
        <div class="small-box bg-yellow">
            <div class="inner">
            <h3>{{ $pendingreservations }}</h3>

            <p>Pending Reservations</p>
            </div>
            <div class="icon">
            <i class="ion ion-android-more-horizontal"></i>
            </div>
            <a href="/admin/reservations" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-2 col-xs-6 col-xs-offset-1">
        <div class="small-box bg-yellow">
            <div class="inner">
            <h3>{{ $pendingreservations + $confirmedreservations + $cancelledreservations + $donereservations }}</h3>

            <p>Total Reservations</p>
            </div>
            <div class="icon">
            <i class="ion ion-ios-list"></i>
            </div>
            <a href="/admin/reservations" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-2 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
            <h3>{{ $pendingreservations }}</h3>

            <p>Pending Reservations</p>
            </div>
            <div class="icon">
            <i class="ion ion-ios-help-empty"></i>
            </div>
            <a href="/admin/reservations" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-2 col-xs-6">
        <div class="small-box bg-primary">
            <div class="inner">
            <h3>{{ $confirmedreservations }}</h3>

            <p>Confirmed Reservations</p>
            </div>
            <div class="icon">
            <i class="ion ion-ios-checkmark-empty"></i>
            </div>
            <a href="/admin/reservations" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-2 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
            <h3>{{ $donereservations }}</h3>

            <p>Done Reservations</p>
            </div>
            <div class="icon">
            <i class="ion ion-ios-checkmark-empty"></i>
            </div>
            <a href="/admin/reservations" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <div class="col-lg-2 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
            <h3>{{ $cancelledreservations }}</h3>

            <p>Cancelled Reservations</p>
            </div>
            <div class="icon">
            <i class="ion ion-ios-close-empty"></i>
            </div>
            <a href="/admin/reservations" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

{{--  Calendar  --}}
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body no-padding">
                {!! $calendar->calendar() !!}
                {!! $calendar->script() !!}
            </div>
        </div>
    </div>
</div>

{{--  Upcoming Events  --}}
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {{--  box header  --}}
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Upcoming Events</h3>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                <table id="tblUpcomingEvents" class="table table-bordered table-hover">
                    <thead>
                        <th>Event Title</th>
                        <th>Event Organizer</th>
                        <th>Function Room/s</th>
                        <th>Date</th>
                        <th style="width: 3%">Actions</th>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach ($reservations as $reservation)
                            @if (date_diff(date_create(date("Y-m-d")), date_create($reservation->eventdate))->format("%a") < 30)
                            <td>{{ $reservation->eventtitle }}</td>
                            <td>{{ $reservation->eventorganizer }}</td>
                            <td>
                                @foreach ($eventvenues as $eventvenue) 
                                    @if ($reservation->code == $eventvenue->reservationcode)
                                        @foreach ($functionhalls as $functionhall) 
                                            @if ($eventvenue->venuecode == $functionhall->code) 
                                                {{$functionhall->name}} <br>
                                            @endif
                                        @endforeach

                                        @foreach ($meetingrooms as $meetingroom) 
                                            @if ($eventvenue->venuecode == $meetingroom->code) 
                                                {{$meetingroom->name}} <br>
                                            @endif
                                        @endforeach
                                    @endif
                                @endforeach
                            </td>
                            <td>{{ date("F d, Y", strtotime($reservation->eventdate)) }}</td>
                            <td><a class="btn btn-default" title="View Reservation Info" href="{{ route('admin.showreservationinfo', ['id' => $reservation->id]) }}"> <i class="fa fa-eye"></i></a></td>
                            @endif
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Page specific script -->
<script type="text/javascript">
    $(function () {
    });
</script>
@endsection