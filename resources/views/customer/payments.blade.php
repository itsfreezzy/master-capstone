@extends('layouts.clientlayout')

@section('title')
PAYMENTS | USER - UNILAB Bayanihan Center
@endsection

@section('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('adminlte/bower_components/select2/dist/css/select2.min.css')}}">
@endsection

@section('content-header')
    <h1>
        Payments
        <small>Where you can keep track of your payments.</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa-money"></i> Payments</li>
    </ol>
@endsection

@section('content')
<div class="container-fluid">
    @include('inc.messages')
    <div class="row">
        <div class="col md-12">
            <div class="box box-primary">
                {{--  box header  --}}
                <div class="box-header" style="color: white; background-color: #3c8dbc">
                    <h3 class="box-title">Payments List</h3>

                    <div class="pull-right" style="padding:0px">
                        <button class="btn btn-block btn-success" data-toggle="modal" id="btnAddPayment" data-target="#modalCreate"> <i class="glyphicon glyphicon-plus-sign"></i> Add Payment </button>
                    </div>
                </div>

                {{--  box body  --}}
                <div class="box-body">
                    <table id="tblPayments" class="table table-bordered table-hover">
                        <thead>
                            <th class="col-sm-1">Payment Code</th>
                            <th>Event Title</th>
                            <th>Reservation Status</th>
                            <th>Payment Type</th>
                            <th>Payment Date</th>
                            <th>Payment Status</th>
                            <th style="width: 10%">Actions</th>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                @foreach ($reservations as $reservation)
                                    @if ($payment->reservationcode == $reservation->code && $reservation->customercode == Auth::guard('customer')->user()->code)
                                    <tr>
                                        <td>{{$payment->paymentcode}}</td>
                                        <td>{{$reservation->eventtitle}}</td>
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
                                        <td>{{$payment->paymenttype}}</td>
                                        <td>{{date('F d, Y', strtotime($payment->paymentdate))}}</td>
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
                                            <button type="button" class="btn btn-default" title="View Payment Details" data-toggle="modal" data-target="#modalRead{{$payment->id}}"> <i class="fa fa-eye"></i></button>
                                            @if($payment->status != "Confirmed")
                                            <button type="button" class="btn btn-primary" title="Edit Payment Details" data-toggle="modal" data-target="#modalUpdate{{$payment->id}}"> <i class="fa fa-edit"></i></button>
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
</div>

{{-- Create Modal --}}
<div class="modal fade" id="modalCreate" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Add Reservation Payment</h4>
            </div>

            <div class="modal-body">
                <form action="{{ route('client.payments.submit') }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    @csrf
                    {{-- Reservation Title --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Reservation Title:</label>
                        <div class="col-sm-7">
                            <select name="reservationcode" id="selreservationcode" class="form-control">
                                <option value="">--SELECT RESERVATION--</option>
                                @foreach($reservations as $reservation)
                                    @if ($reservation->status != 'Done' && $reservation->customercode == Auth::guard('customer')->user()->code)
                                        <option value="{{$reservation->code}}">{{$reservation->eventtitle}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Payment Type --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Payment Type:</label>
                        <div class="col-sm-7">
                            <select name="paymenttype" id="paymenttype" class="form-control">
                                <option value="">--SELECT PAYMENT TYPE--</option>
                                <option value="Reservation Fee">Reservation Fee</option>
                                <option value="50% Downpayment">50% Downpayment</option>
                                <option value="50% Full Payment">50% Full Payment</option>
                                <option value="Security Deposit">Security Deposit</option>
                            </select>
                        </div>
                    </div>

                    {{-- Payment Amount --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Payment Amount:</label>
                        <div class="col-sm-7">
                            <input name="paymentamount" id="pmtamt" type="number" class="form-control" value="" autocomplete="off" min="1" step="0.01">
                        </div>

                        @if ($errors->has('paymentamount'))
                            <div class="col-sm-7 col-sm-offset-4 error">
                                <span style="color: red" role="alert">
                                    <strong>{{ $errors->first('paymentamount') }}</strong>
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Payment Date --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Payment Date:</label>
                        <div class="col-sm-7">
                            <input name="paymentdate" type="date" class="form-control" id="paymentdate" value="" autocomplete="off">
                        </div>

                        @if ($errors->has('paymentdate'))
                            <div class="col-sm-7 col-sm-offset-4 error">
                                <span style="color: red" role="alert">
                                    <strong>{{ $errors->first('paymentdate') }}</strong>
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Payment Image --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Proof of Payment:</label>
                        <div class="col-sm-7">
                            <input class="form-control" type="file" name="paymentproof[]" id="paymentproof" multiple>
                        </div>

                        @if ($errors->has('paymentproof.*') || $errors->has('paymentproof'))
                            <div class="col-sm-7 col-sm-offset-4 error">
                                <span style="color: red" role="alert">
                                    <strong>{{ $errors->first('paymentproof') }}</strong>
                                </span>
                            </div>
                            <div class="col-sm-7 col-sm-offset-4 error">
                                <span style="color: red" role="alert">
                                    <strong>{{ $errors->first('paymentproof.*') }}</strong>
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <input type="submit" id="btnsubmit" style="display: none">
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                <label for="btnsubmit" class="btn btn-success"><i class="fa fa-check"></i> Submit</label>
            </div>
        </div>
    </div>
</div>

@foreach ($payments as $payment)
@foreach ($reservations as $reservation)
@if ($payment->reservationcode == $reservation->code && $reservation->customercode == Auth::guard('customer')->user()->code)
{{-- Read Modal --}}
<div class="modal fade" id="modalRead{{$payment->id}}" role="dialog">
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
                                    <a href="{{$proof}}" target="_blank"><img src="{{$proof}}" width="100%"></a> <br><br>
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

{{-- Update Modal --}}
<div class="modal fade" id="modalUpdate{{$payment->id}}">
    <div class="modal-dialog">
        <div class="div modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> Update Payment Information</h4>
            </div>
            <div class="modal-body">
                <form action="{{ action('ClientController@updatePayment', ['id' => $payment->id]) }}" class="form-horizontal" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    {{-- Reservation Title --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Reservation Title:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="paymenttype" value="{{$reservation->eventtitle}}" autocomplete="off" readonly>
                        </div>
                    </div>

                    {{-- Payment Type --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Payment Type:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="paymenttype" value="{{$payment->paymenttype}}" autocomplete="off" readonly>
                        </div>
                    </div>

                    {{-- Payment Amount --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Payment Amount:</label>
                        <div class="col-sm-7">
                            <input name="editpaymentamount" type="number" class="form-control" id="editpmtamt" value="{{$payment->amount}}" autocomplete="off" min="1" max="" step="0.01">
                        </div>
                    </div>

                    {{-- Payment Date --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Payment Date:</label>
                        <div class="col-sm-7">
                            <input name="editpaymentdate" type="date" class="form-control" value="{{date('Y-m-d', strtotime($payment->paymentdate))}}" autocomplete="off">
                        </div>
                    </div>

                    {{-- Payment Image --}}
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Proof of Payment:</label>
                        <div class="col-sm-7">
                            <input class="form-control" type="file" name="editpaymentproof[]" id="" multiple>
                        </div>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    
                    <button type="submit" class="btn btn-success" id="submitbtn"><i class="fa fa-check"></i> Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
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

    
    selpaymenttype.val('');
    selpaymenttype.attr('disabled', true);
    $('#pmtamt').attr('disabled', true);
    $('#paymentdate').attr('disabled', true);
    $('#paymentproof').attr('disabled', true);

    //##################################################################
    // On change of reservation list selectbox
    //##################################################################
    reservationselection.on('change', function() {
        var selectedreservation = $(this).val();
        var paymentexists = false;

        $($payments).each(function(index, payment) {
            if (payment.reservationcode == selectedreservation) {
                paymentexists = true;
                $.each($reservations, function(index, value) {
                    if (selectedreservation == value.code) {
                        if (selpaymenttype.val() == 'Security Deposit') {
                            $('#pmtamt').attr('max', 10000);
                            $('#pmtamt').attr('min', 10000);
                        } else if (selpaymenttype.val() == 'Reservation Fee') {
                            $('#pmtamt').attr('max', 5000);
                            $('#pmtamt').attr('min', 5000);
                        }
                        $('#pmtamt').attr('min', 1);
                        $('#pmtamt').attr('max', value.balance);
                        return false;
                    }
                });

                return false;
            }
        });

        if (paymentexists) {
            $($payments).each(function(index, payment) {
                if (payment.reservationcode == selectedreservation) {
                    paymentoptions.each(function() {
                        if (payment.paymenttype == $(this).val() || $(this).val() == '' || $(this).val() == null) {
                            $(this).prop('disabled', true);
                        } else {
                            $(this).prop('disabled', false);
                        }
                    });
                    
                    paymentoptions.each(function() {
                        if (!$(this).attr('disabled')) {
                            selpaymenttype.val($(this).val());
                            return false;
                        }
                    });
                }
            });
            
            selpaymenttype.attr('disabled', false);
            $('#pmtamt').attr('disabled', false);
            $('#paymentdate').attr('disabled', false);
            $('#paymentproof').attr('disabled', false);
        } else {
            if (selectedreservation == null || selectedreservation == '') {
                paymentoptions.each(function () {
                    if ($(this).val() != selectedreservation) {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                    }
                });

                selpaymenttype.val('');
                selpaymenttype.attr('disabled', true);
                $('#pmtamt').attr('disabled', true);
                $('#paymentdate').attr('disabled', true);
                $('#paymentproof').attr('disabled', true);
            } else {
                paymentoptions.each(function() {
                    if($(this).val() != 'Reservation Fee') {
                        $(this).prop('disabled', true);
                    } else {
                        $(this).prop('disabled', false);
                        selpaymenttype.val($(this).val());
                    }
                });
                
                selpaymenttype.attr('disabled', false);
                $('#pmtamt').attr('disabled', false);
                $('#paymentdate').attr('disabled', false);
                $('#paymentproof').attr('disabled', false);
            }
        }
    });

    $('#tblPayments').DataTable();
    $('[data-toggle="tooltip"]').tooltip();
    $('#selreservationcode').select2({
        width: '100%'
    });

    @if (session('showAddModal'))
        $('#modalCreate').modal('show');
    @endif

    $('#modalCreate').on('hidden.bs.modal', function(e){
        selpaymenttype.val('');
        selpaymenttype.attr('disabled', true);
        $('#pmtamt').attr('disabled', true);
        $('#paymentdate').attr('disabled', true);
        $('#paymentproof').attr('disabled', true);
        $("#modalCreate .error").remove();
        $("#modalCreate .modal-body input").val("");
    });
});
</script>
@endsection