@extends('layouts.adminlayout')

@section('title')
View Reservation - UNILAB Bayanihan Center
@endsection

@section('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{asset('adminlte/bower_components/select2/dist/css/select2.min.css')}}">

<style>
    .select2-results { background-color: #00f; }
</style>
@endsection

@section('content-header')
    <h1>
        Reservation Information
        <small>Information about customer's reservation.</small>
    </h1>
    <ol class="breadcrumb">
        <li class=""><a href="/admin/dashboard"><i class="fa fa-tachometer"></i> Dashboard</a></li>
        <li><a href="/admin/reservations">Reservation</a></li>
        <li class="active">View Reservation Information</li>
    </ol>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col md-12">
            <div class="box box-primary">
                <div class="box-header" style="color: white; background-color: #3c8dbc">
                    <h3 class="box-title">Reservation Form</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="box-body">
                    @include('customer.dispreservationform')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{asset('adminlte/bower_components/select2/dist/js/select2.full.min.js')}}"></script>

<script>
$(function (){
    //##################################################################
    // For Applying Select2 on Select Boxes
    //##################################################################
    $('#EventNature').select2({
        tags: true
    });

    $('#EventSetup').select2({
        tags: true
    });
    $caterer = $('#CatererName').select2({
        tags: true
    });
    $('#PrefFuncRoom').select2();
});
</script>
@endsection