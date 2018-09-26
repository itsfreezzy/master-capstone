@extends('layouts.clientlayout') 
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
        <div class="col-xs-12 col-md-6">
            <div class="box box-primary">
                <div class="box-header" style="color: white; background-color: #3c8dbc">
                    <h3 class="box-title"> Account Information/Profile </h3>
                    <div class="pull-right" style="padding:0px">

                    </div>
                </div>
                <div class="box-body">
                    <form action="{{ route('client.update.profile') }}" method="POST" role="form" id="formconfirm">
                        @csrf
                        <div class="form-group col-xs-6">
                            <label>Customer Name:</label>
                            <input type="text" name="clientname" value="{{ Auth::guard('customer')->user()->name }}" class="form-control" placeholder="Enter your full name...">
                            @if ($errors->has('clientname'))
                                <div class="error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('clientname') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-xs-6">
                            <label>TIN Number:</label>
                            <input type="text" name="tinnumber" value="{{ Auth::guard('customer')->user()->tinnumber }}" class="form-control" placeholder="Enter your full name...">
                            @if ($errors->has('tinnumber'))
                                <div class="error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('tinnumber') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-xs-6">
                            <label>Username:</label>
                            <input type="text" name="username" value="{{ Auth::guard('customer')->user()->username }}" class="form-control" placeholder="Enter your full name...">
                            @if ($errors->has('username'))
                                <div class="error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-xs-6">
                            <label>Email:</label>
                            <input type="text" name="email" value="{{ Auth::guard('customer')->user()->email }}" class="form-control" placeholder="Enter your full name...">
                            @if ($errors->has('email'))
                                <div class="error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Contact Number:</label>
                            <input type="text" id="contactnumber" name="contactnumber" value="{{ Auth::guard('customer')->user()->contactnumber }}" class="form-control" placeholder="Enter your full name...">
                            @if ($errors->has('contactnumber'))
                                <div class="error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('contactnumber') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Customer Type:</label>
                            <input type="text" id="disptype" value="{{ Auth::guard('customer')->user()->type }}" class="form-control" placeholder="Enter your full name..." readonly>
                        </div>
                        <div class="form-group col-xs-12">
                            <label><input type="radio" value="Personal" name="customertype" id="customertype" {{ Auth::guard('customer')->user()->type == 'Personal' ? 'checked' : '' }}>Personal</label>
                            &emsp;<label><input type="radio" value="Company" name="customertype" id="customertype" {{ Auth::guard('customer')->user()->type == 'Company' ? 'checked' : '' }}>Company</label>
                        </div>
                        <div class="form-group col-xs-6">
                            <label>Account Creation Date:</label>
                            <input type="text" value="{{ date("F d, Y h:i:A", strtotime(Auth::guard('customer')->user()->created_at)) }}" class="form-control" placeholder="Enter your full name..." readonly>
                        </div>
                        <div class="form-group col-xs-6">
                            <label>Account Last Updated:</label>
                            <input type="text" value="{{ date("F d, Y h:i:A", strtotime(Auth::guard('customer')->user()->updated_at)) }}" class="form-control" placeholder="Enter your full name..." readonly>
                        </div>
                        <div class="form-group pull-right col-xs-12">
                            <button type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#modalConfirm"> <i class="fa fa-check"></i> Update my Profile!</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-md-6">
            <div class="box box-primary">
                <div class="box-header" style="color: white; background-color: #3c8dbc">
                    <h3 class="box-title"> Change Password </h3>
                    <div class="pull-right" style="padding:0px">

                    </div>
                </div>
                <div class="box-body">
                    <form action="{{ route('client.update.password') }}" method="POST" role="form" id="formconfirm">
                        @csrf
                        <div class="form-group col-xs-12">
                            <label>New Password:</label>
                            <input type="password" class="form-control" name="newpassword" placeholder="Enter new password..." required>
                            @if ($errors->has('newpassword'))
                                <div class="error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('newpassword') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Confirm New Password:</label>
                            <input type="password" class="form-control" name="newpassword_confirmation" placeholder="Confirm new password..." required>
                            @if ($errors->has('password_confirmation'))
                                <div class="newerror">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('newpassword_confirmation') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Old Password:</label>
                            <input type="password" class="form-control" name="oldpassword" placeholder="Enter old password..." required>
                            @if ($errors->has('oldpassword'))
                                <div class="error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('oldpassword') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group pull-right col-xs-12">
                            <button type="submit" class="btn btn-block btn-success"> <i class="fa fa-check"></i> Update my Password!</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalConfirm" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-check"></i> Confirm changes to your profile?</h4>
            </div>
            <div class="modal-body">
                <div class="form-group col-xs-12">
                    <label>Confirm Password</label>
                    <input type="password" form="formconfirm" name="password" id="password" class="form-control" placeholder="Enter your password...">
                </div>
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
    $('input[name="customertype"]').on('change', function() {
        $('#disptype').val($(this).val());
    });
});
</script>
@endsection