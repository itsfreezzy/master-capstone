@extends('layouts.clientlayout')

@section('title')
Settings | USER - UNILAB Bayanihan Center
@endsection

@section('styles')
@endsection

@section('content-header')
    <h1>
        Settings
        <small>Edit it according to your personal preference.</small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><i class="fa fa-dashboard"></i> Home</li>
    </ol>
@endsection

@section('content')
<ul class="nav nav-pills">
    <li role="presentation" class="active"><a href="#">My Profile</a></li>
    <li role="presentation"><a href="#">EWAN1</a></li>
    <li role="presentation"><a href="#">EWAN1</a></li>
</ul>
@endsection

@section('scripts')
<script>
    $(function (){
        // $('#tblCustomerReservations').DataTable();
    });
</script>
@endsection