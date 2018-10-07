@extends('layouts.clientlayout')

@section('title')
RESERVATIONS | CLIENT - UNILAB Bayanihan Center
@endsection

@section('styles')
@endsection

@section('content-header')
    <h1>
        Reservations
        <small>Your history of reservation with us.</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa-calendar"></i> Reservations</li>
    </ol>
@endsection

@section('content')
<div class="container-fluid">
    @include('inc.messages')
    <div class="row">
        <div class="col md-12">
            <div class="box box-primary">
                <div class="box-header" style="color: white; background-color: #3c8dbc">
                    <h3 class="box-title">Reservation History</h3>
                    <div class="pull-right" style="padding:0px">
                        <a href="{{ route('client.reservationform') }}" class="btn btn-block btn-success"> <i class="glyphicon glyphicon-plus-sign"></i> Add New Reservation </a>
                    </div>
                </div>
                <div class="box-body">
                    <table id="tblCustomerReservations" class="table table-bordered table-hover">
                        <thead>
                            <th class="col-sm-1">Reservation Code</th>
                            <th>Event Title</th>
                            <th>Date Filed</th>
                            <th>Event Date</th>
                            <th>Event Organizer</th>
                            <th>Status</th>
                            <th style="width: 12%">Actions</th>
                        </thead>
                        <tbody>
                            @foreach ($reservations as $reservation)
                                @if($reservation->customercode == Auth::guard('customer')->user()->code)
                                    <tr>
                                        <td>{{$reservation->code}}</td>
                                        <td>{{$reservation->eventtitle}}</td>
                                        <td>{{date('F d, Y', strtotime($reservation->datefiled))}}</td>
                                        <td>{{date('F d, Y', strtotime($reservation->eventdate))}}</td>
                                        <td>{{$reservation->eventorganizer}}</td>
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
                                            <a class="btn btn-default" href="{{ route('client.show.reservationinfo', ['id' => $reservation->id]) }}" type="button" title="View Reservation Information"><i class="fa fa-eye"></i></a>
                                            @if (!$reservation->trashed())
                                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                                <span class="caret"></span>
                                                <span class="sr-only">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                {{-- <li><a href="{{ route('client.print.billing-statement', ['id' => $reservation->id]) }}" target="_blank">View Reservation Voucher</a></li>
                                                <li><a href="{{ route('client.print.billing-statement', ['id' => $reservation->id]) }}" target="_blank">View Reservation Confirmation</a></li>
                                                @if ($reservation->hasContract)
                                                <li><a href="{{ route('client.print.billing-statement', ['id' => $reservation->id]) }}" target="_blank">View Reservation Contract</a></li>
                                                @endif
                                                @if ($reservation->hasBilling) --}}
                                                <li><a href="{{ route('client.print.billing-statement', ['id' => $reservation->id]) }}" target="_blank" >View Initial Billing Statement</a></li>
                                            </ul>
                                            @endif
                                            @if ($reservation->isDone)
                                            @elseif ($reservation->trashed())
                                            @elseif (date_diff(date_create($reservation->eventdate), date_create(date('Y-m-d')))->invert == 0)
                                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalMarkAsDone{{$reservation->id}}" title="Mark Event/Reservation as Done"> <i class="fa fa-check"></i></button>
                                            @elseif ($reservation->status == "Pending" || $reservation->status == "Confirmed")
                                            <a class="btn btn-primary" href="{{ route('client.edit.reservationinfo', ['id' => $reservation->id]) }}" type="button" title="Update Reservation Information"><i class="fa fa-edit"></i></a>
                                            <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#modalCancel{{$reservation->id}}" title="Cancel Reservation"><i class="fa fa-close"></i></button>
                                            @elseif ($reservation->status == "Cancelled")
                                            <button class="btn btn-warning" type="button" data-toggle="modal" data-target="#modalUndoCancel{{$reservation->id}}" title="Undo Reservation Cancellation"> <i class="fa fa-undo"></i></button>
                                            @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach ($reservations as $reservation)
    @if ($reservation->customercode == Auth::guard('customer')->user()->code)
        @if ($reservation->isDone)
        @elseif (date_diff(date_create($reservation->eventdate), date_create(date('Y-m-d')))->invert == 0)
        <div class="modal fade" id="modalMarkAsDone{{$reservation->id}}" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-check"></i> Mark Reservation/Event as Done</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Security Deposit Charge:</label>
                            <input class="form-control" type="number" name="posteventcharge" min="0" max="99999.99" step="0.01" form="submit-form" required>
                        </div>

                        <div class="form-group">
                            <label>Date Received:</label>
                            <input class="form-control" type="date" name="datereceived" form="submit-form" min="{{date('Y-m-d')}}" required>
                        </div>

                        Marking this event/reservation as done means all transactions for this certain reservation/event is done. <br>
                        Proceed on marking this event/reservation as done? <br> <br>
                        <h4><strong>NOTE: This action cannot be undone.</strong></h4>
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('client.reservation.done', ['id' => $reservation->id]) }}" method="POST" class="pull pull-right" id="submit-form">
                            @csrf
                            @method('PUT')
                            <a href="#" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-close"></i> No, not yet.</a>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Yes, mark as done.</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @elseif ($reservation->status == "Pending" || $reservation->status == 'Confirmed')
        <div class="modal fade" id="modalCancel{{$reservation->id}}" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-eye"></i> Reservation Cancellation</h4>
                    </div>
                    <div class="modal-body">
                        Cancellation of reservation may be subjected to the following <strong>CANCELLATION CHARGES</strong>:
                        <ul>
                            <li>2 months prior to function date &emsp; - &emsp; 50% of required deposit</li>
                            <li>1 month prior to function date &emsp; - &emsp; Forfeiture of required deposit</li>
                            <li>2 weeks prior to function date &emsp; - &emsp; 100% cancellation charge</li>
                        </ul><br>
                        Do you still want to proceed on cancelling your reservation?
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('client.reservation.cancel', ['id' => $reservation->id]) }}" method="POST" class="pull pull-right">
                            @csrf
                            @method('DELETE')
                            <a href="#" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-close"></i> NO</a>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> YES</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @elseif ($reservation->status == "Cancelled")
        <div class="modal fade" id="modalUndoCancel{{$reservation->id}}" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title"><i class="fa fa-undo"></i> Undo Cancellation of Reservation</h4>
                    </div>
                    <div class="modal-body">
                        This undoes the cancellation of reservation. Do you want to proceed?
                    </div>
                    <div class="modal-footer">
                        <form action="{{ route('client.undo.cancel', ['id' => $reservation->id]) }}" method="POST" class="pull pull-right">
                            @csrf
                            @method('DELETE')
                            <a href="#" data-dismiss="modal" class="btn btn-danger"><i class="fa fa-close"></i> NO</a>
                            <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> YES</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endif
@endforeach
@endsection

@section('scripts')
<script>
    $(function (){
        $('#tblCustomerReservations').DataTable({
            pageLength: 25,
            order: [],
        });
    });
</script>
@endsection