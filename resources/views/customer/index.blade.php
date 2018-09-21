@extends('layouts.clientlayout')

@section('title')
HOME | UNILAB Bayanihan Center
@endsection

@section('styles')
@endsection

@section('content-header')
    <h1>
        Home
        <small>TL:DR; of your information on us.</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa-home"></i> Home</li>
    </ol>
@endsection

@section('content')
{{--  Information Cards  --}}
<div class="row">
<div class="col-lg-6 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-aqua">
        <div class="inner">
        <h3>{{ $daystilnextevent }} Day(s)</h3>

        <p>Days until next event</p>
        </div>
        <div class="icon">
        <i class="ion ion-calendar"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
    </div>

    <div class="col-lg-6 col-xs-6">
    <!-- small box -->
    <div class="small-box bg-yellow">
        <div class="inner">
        <h3>P {{ $totbal }}</h3>

        <p>Pending Balance for {{ $reservation->count() }} reservation(s)</p>
        </div>
        <div class="icon">
        <i class="ion ion-android-more-horizontal"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
    </div>
</div>
</div>
@endsection

@section('scripts')
@endsection