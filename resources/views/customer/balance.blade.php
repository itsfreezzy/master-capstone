@extends('layouts.clientlayout')

@section('styles')
@endsection

@section('title')
Bayanihan Center | Balance
@endsection

@section('content-header')
    <h1>
        Balance
        <small>List of your balance from reservations (if there's any)</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa-balance-scale"></i>Balance</li>
    </ol>
@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {{--  box header  --}}
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Balance List</h3>
                <div class="pull-right" style="padding:0px">
                    {{-- <button class="btn btn-block btn-success" data-toggle="modal" data-target="#modalCreate"> <i class="glyphicon glyphicon-plus-sign"></i> Add Amenity </button> --}}
                </div>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                <table id="tblBalance" class="table table-bordered table-hover">
                    <thead>
                        <th class="col-sm-1">Event Code</th>
                        <th class="col-sm-1">Event Title</th>
                        <th class="col-sm-2">Balance</th>
                    </thead>
                    <tbody>
                        @foreach ($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->code }}</td>
                            <td>{{ $reservation->eventtitle }}</td>
                            <td>P{{ number_format($reservation->balance, 2) }}</td>
                        </tr>
                        @endforeach 
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div> 
@endsection

@section('scripts')
<script>
    $(function() {
        $('#tblBalance').DataTable({
            pageLength: 25,
            order: [],
        });
    });
</script>
@endsection