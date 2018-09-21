@extends('layouts.adminlayout')

@section('styles')
@endsection

@section('title')
Bayanihan Center | Reservations
@endsection

@section('content-header')
    <h1>
        Reservations
        <small>List of Reservations (Done, Confirmed, Pending, Cancelled)</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reservation</li>
    </ol>
@endsection

@section('content')
@include('inc.messages')
{{--  Reservation Table  --}}
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {{--  box header  --}}
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Reservation List</h3>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                <table id="tblReservations" class="table table-bordered table-hover">
                    <thead>
                        <th class="col-sm-1">Reservation Code</th>
                        <th>Event Title</th>
                        <th>Customer Name</th>
                        <th>Date Filed</th>
                        <th>Date of Event</th>
                        <th>Status</th>
                        <th style="width: 12%">Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->code }}</td>
                            <td>{{ $reservation->eventtitle }}</td>
                            <td>{{ $reservation->name }}</td>
                            <td>{{ date('F d, Y h:i:sA', strtotime($reservation->datefiled)) }}</td>
                            <td>{{ date('F d, Y', strtotime($reservation->eventdate)) }} | {{ date('h:i:A', strtotime($reservation->timestart)) }} - {{ date('h:i:A', strtotime($reservation->timeend)) }}</td>
                            <td>
                                @if ($reservation->status == "Pending")
                                <span class="label label-default">{{ $reservation->status }}</span>
                                @elseif ($reservation->status == "Confirmed")
                                <span class="label label-primary">{{ $reservation->status }}</span>
                                @elseif ($reservation->status == "Done")
                                <span class="label label-success">{{ $reservation->status }}</span>
                                @elseif ($reservation->status == "Cancelled")
                                <span class="label label-danger">{{ $reservation->status }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                @if ($reservation->status == "Confirmed")
                                {{-- <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown">Actions
                                    <span class="caret"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="{{ route('admin.release.contract', ['id' => $reservation->id]) }}">Release Reservation Contract</a></li>
                                    @if($reservation->hasContract)
                                    <li><a href="#" data-target="modalReleaseBilling{{$reservation->id}}">Release Billing Statement</a></li>
                                    @endif
                                </ul> --}}
                                @endif

                                <a class="btn btn-default" href="{{ route('admin.showreservationinfo', ['id' => $reservation->id]) }}" type="button" title="View Reservation Information"><i class="fa fa-eye"></i></a>    
                                @if($reservation->status == 'Pending' || $reservation->status == "Confirmed")
                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalDelete{{$reservation->id}}" title="Cancel Reservation"> <i class="fa fa-close"></i></button>
                                @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach ($reservations as $reservation)
{{-- Delete Modal --}}
<div class="modal fade" id="modalDelete{{$reservation->id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-trash"></i> Cancel Reservation</h4>
            </div>

            <div class="modal-body">
                <form action="{{ route('admin.reservation.cancel', ['id' => $reservation->id]) }}" method="POST" class="form-horizontal">
                    @csrf
                    <h5>Grounds for Cancellation:</h5>
                    <textarea name="cancelGrounds" id="" style="width: 100%" rows="5" maxlength="191"></textarea>
            </div>
            
            <div class="modal-footer">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Confirm Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection

@section('scripts')
<script>
    $(function (){
        $('#tblReservations').DataTable({
            "pageLength": 25,
            "order": [],
        });
    })
</script>
@endsection