@extends('layouts.adminlayout') 
@section('title') Admin Profile - UNILAB Bayanihan Center
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
                    <form role="form" id="formconfirm" action="{{ route('admin.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group col-xs-12">
                            <label>Full Name:</label>
                            <input type="text" value="{{ Auth::user()->fullname }}" class="form-control" name="fullname" placeholder="Enter your full name...">

                            @if ($errors->has('fullname'))
                                <div class="error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('fullname') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Username:</label>
                            <input type="text" value="{{ Auth::user()->username }}" class="form-control" name="username" placeholder="Enter your desired username...">

                            @if ($errors->has('username'))
                                <div class="error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Email:</label>
                            <input type="text" value="{{ Auth::user()->email }}" class="form-control" name="email" placeholder="Enter your email...">

                            @if ($errors->has('email'))
                                <div class="error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                </div>
                            @endif
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

        
        <div class="col-xs-12 col-md-6">
            <div class="box box-primary">
                <div class="box-header" style="color: white; background-color: #3c8dbc">
                    <h3 class="box-title"> Change Password </h3>
                    <div class="pull-right" style="padding:0px">

                    </div>
                </div>
                <div class="box-body">
                    <form role="form" id="formconfirmz" action="{{ route('admin.password.update') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group col-xs-12">
                            <label>New Password:</label>
                            <input type="password" name="newpassword" id="newpassword" class="form-control" placeholder="Enter new password...">
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Confirm New Password:</label>
                            <input type="password" name="newpassword_confirmation"  class="form-control" placeholder="Confirm new password...">
                        </div>
                        <div class="form-group col-xs-12">
                            <label>Old Password:</label>
                            <input type="password" name="oldpassword" class="form-control" placeholder="Enter old password...">
                        </div>
                    </form>
                        
                    <div class="form-group col-xs-12">
                        <button form="formconfirmz" type="submit" class="btn btn-block btn-success"> <i class="fa fa-check"></i> Update my Password!</button>
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

    });

    </script>
@endsection