@extends('layouts.adminlayout')

@section('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('adminlte/bower_components/select2/dist/css/select2.min.css')}}">
@endsection

@section('title')
Bayanihan Center | Payments
@endsection

@section('content-header')
    <h1>
        Payment Tracking
        <small>Payment information of clients</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Payment Tracking</li>
    </ol>
@endsection

@section('content')
{{--  Payments Table  --}}
@include('inc.messages')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {{--  box header  --}}
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Payments List</h3>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                <table id="tblPayments" class="table table-bordered table-hover">
                    <thead>
                        <th class="col-sm-1">Payment Code</th>
                        <th>Event Title</th>
                        <th>Customer Name</th>
                        <th>Payment Type</th>
                        <th>Payment Date</th>
                        <th>Payment Status</th>
                        <th style="width: 10%">Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($payments as $payment)
                            @foreach ($reservations as $reservation)
                                @if ($payment->reservationcode == $reservation->code)
                                <tr>
                                    <td>{{$payment->paymentcode}}</td>
                                    <td>{{$reservation->eventtitle}}</td>
                                    <td>{{ $reservation->name }}</td>
                                    <td>{{$payment->paymenttype}}</td>
                                    <td>{{date('F d, Y h:i:sA', strtotime($payment->created_at))}}</td>
                                    <td>
                                        @if($payment->status == 'Pending')
                                        <span class="label label-default">{{ $payment->status }}</span>
                                        @elseif ($payment->status == 'Confirmed')
                                        <span class="label label-success">{{ $payment->status }}</span>
                                        @elseif ($payment->status == 'Rejected')
                                        <span class="label label-danger">{{ $payment->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                        <button type="button" class="btn btn-default" title="View Proof of Payment" data-toggle="modal" data-target="#modalViewProof{{$payment->id}}"> <i class="fa fa-eye"></i></button>
                                        @if ($payment->status == "Rejected")
                                        @elseif ($payment->status != "Confirmed")
                                        <button type="button" class="btn btn-success" title="Confirm Payment" data-toggle="modal" data-target="#modalConfirm{{$payment->id}}"> <i class="fa fa-check"></i></button>
                                        <button type="button" class="btn btn-danger" title="Reject Payment" data-toggle="modal" data-target="#modalReject{{$payment->id}}"> <i class="fa fa-close"></i></button>
                                        @endif
                                        </div>
                                    </td>
                                </tr>
                                    @break
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@foreach ($payments as $payment)
@foreach ($reservations as $reservation)
@foreach ($customers as $customer)
@if ($payment->reservationcode == $reservation->code && $reservation->customercode == $customer->code)
{{-- Modal View Proof of Payment --}}
<div class="modal fade" id="modalViewProof{{$payment->id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-eye"></i> View Payment History</h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal" method="post">
                    @csrf
                    {{-- Reservation Title --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Reservation Title:</label>
                        <div class="col-sm-7">
                            <input name="paymentamount" type="text" class="form-control" name="paymenttype" value="{{$reservation->eventtitle}}" autocomplete="off" min="1" max="" step="0.01" readonly>
                        </div>
                    </div>

                    {{-- Payment Type --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Payment Type:</label>
                        <div class="col-sm-7">
                            <input name="paymentamount" type="text" class="form-control" name="paymenttype" value="{{$payment->paymenttype}}" autocomplete="off" min="1" max="" step="0.01" readonly>
                        </div>
                    </div>

                    {{-- Payment Amount --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Payment Amount:</label>
                        <div class="col-sm-7">
                            <input name="paymentamount" type="text" class="form-control" name="paymenttype" value="{{$payment->amount}}" autocomplete="off" min="1" max="" step="0.01" readonly>
                        </div>
                    </div>

                    {{-- Payment Date --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Payment Date:</label>
                        <div class="col-sm-7">
                            <input name="paymentdate" type="text" class="form-control" name="paymenttype" value="{{date('F d, Y', strtotime($payment->paymentdate))}}" autocomplete="off" readonly>
                        </div>
                    </div>

                    {{-- Payment Image --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Proof of Payment:</label>
                        <div class="col-sm-7">
                            @foreach (explode("|", $payment->proof) as $proof)
                                @if ($proof != '' || $proof != null)
                                    <a href="/storage/{{$customer->code}}/{{$proof}}" target="_blank"><img src="/storage/{{$customer->code}}/{{$proof}}" width="100%"></a> <br><br>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <input type="submit" id="btnsubmit" style="display: none">
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"><i class="fa fa-check"></i> OK</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Confirm --}}
<div class="modal fade" id="modalConfirm{{$payment->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-check"></i> Confirm Payment</h4>
            </div>
            <div class="modal-body">
                Confirming this payment confirms that you have verified the proof of payment sent by the client. Proceed?
            </div>
            <div class="modal-footer">
                <form action="{{ action('PaymentController@confirm', ['id' => $payment->id]) }}" method="POST" class="pull pull-right">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Confirm Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal Reject --}}
<div class="modal fade" id="modalReject{{$payment->id}}">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-close"></i> Reject Payment</h4>
            </div>
            <div class="modal-body">
                Rejecting this payment confirms that you have verified that the proof of payment sent by the client is invalid. Proceed?
            </div>
            <div class="modal-footer">
                <form action="{{ action('PaymentController@reject', ['id' => $payment->id]) }}" method="POST" class="pull pull-right">
                    @csrf
                    <input type="hidden" name="_method" value="PUT">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Reject Payment</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endforeach
@endforeach
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{asset('adminlte/bower_components/select2/dist/js/select2.full.min.js')}}"></script>

<script>
$(function() {
    //##################################################################
    // Getting selections etc.
    //##################################################################
    var reservationselection = $('#selreservationcode');
    var selpaymenttype = $('#paymenttype');
    var paymentoptions = $('#paymenttype option');
    var $reservations = @json($reservations);
    var $payments = @json($payments);

    //##################################################################
    // On change of reservation list selectbox
    //##################################################################
    reservationselection.on('change', function() {
        var selectedreservation = $(this).val();
        var paymentexists = false;

        $($payments).each(function(index, payment) {
            if (payment.reservationcode == selectedreservation) {
                paymentexists = true;
                return false;
            }
        });

        if (paymentexists) {
            $($payments).each(function(index, payment) {
                if (payment.reservationcode == selectedreservation) {
                    paymentoptions.each(function() {
                        if (payment.paymenttype == $(this).val()) {
                            $(this).prop('disabled', true);
                            return false;
                        } else {
                            $(this).prop('disabled', false);
                            return false;
                        }
                    });
                }
                // paymentoptions.each(function () {
                //     if (payment.reservationcode == selectedreservation && payment.paymenttype == $(this).val()) {
                //         $(this).prop('disabled', true);
                //     }
                // });
            });
        } else {
            if (selectedreservation == null || selectedreservation == '') {
                paymentoptions.each(function () {
                    if ($(this).val() != selectedreservation) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            } else {
                paymentoptions.each(function () {
                    if($(this).val() != 'Reservation Fee') {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });
            }
        }
    });

    $('#tblPayments').DataTable({
        "pageLength": 25,
        "order": [],
    });
    $('[data-toggle="tooltip"]').tooltip();
    $('#selreservationcode').select2({
        width: '100%'
    });
});
</script>
@endsection