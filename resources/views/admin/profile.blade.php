@extends('layouts.adminlayout') 
@section('title') PROFILE | CLIENT - UNILAB Bayanihan Center
@endsection
 
@section('content-header')
<h1>
    Profile
    <small>Your account information.</small>
</h1>
<ol class="breadcrumb">
    <li class="active"><i class="fa fa-user"></i> Profile</li>
</ol>
@endsection
 
@section('content')
    @include('inc.messages')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-offset-3 col-md-6">
            <div class="box box-primary">
                <div class="box-header" style="color: white; background-color: #3c8dbc">
                    <h3 class="box-title"> Account Information/Profile </h3>
                    <div class="pull-right" style="padding:0px">

                    </div>
                </div>
                <div class="box-body">
                    <form role="form" id="formconfirm">
                        <div class="form-group col-xs-12">
                            <label>Full Name:</label>
                            <input type="text" value="{{ Auth::user()->fullname }}" class="form-control" placeholder="Enter your full name...">
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Username:</label>
                            <input type="text" value="{{ Auth::user()->username }}" class="form-control" placeholder="Enter your desired username...">
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Email:</label>
                            <input type="text" value="{{ Auth::user()->email }}" class="form-control" placeholder="Enter your email...">
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Access Type:</label>
                            <input type="text" value="{{ Auth::user()->usertype }}" class="form-control" placeholder="Enter your full name..." readonly>
                        </div>
                        <div class="form-group col-xs-6">
                            <label>Account Creation Date:</label>
                            <input type="text" value="{{ date("F d, Y h:i:A", strtotime(Auth::user()->created_at)) }}" class="form-control" placeholder="Enter your full name..." readonly>
                        </div>
                        <div class="form-group col-xs-6">
                            <label>Account Last Updated:</label>
                            <input type="text" value="{{ date("F d, Y h:i:A", strtotime(Auth::user()->updated_at)) }}" class="form-control" placeholder="Enter your full name..." readonly>
                        </div>
                    </form>
                        
                    <div class="form-group col-xs-12">
                        <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalConfirm"> <i class="fa fa-check"></i> Update my Profile!</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">

            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <div class="pull-right">
                    <button type="submit" form="formconfirm" class="btn btn-block btn-success"> <i class="fa fa-check"></i> Update my Profile!</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 
@section('scripts')
    <script>
        $(function() {

    });

    </script>
@endsection