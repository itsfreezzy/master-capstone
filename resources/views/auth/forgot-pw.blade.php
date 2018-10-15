@extends('layouts.authentication')

@section('title')
Forgot Password | Bayanihan Center
@endsection

@section('content')
<div class="login-box">
    <div class="login-logo">
        <a href="/"><b>Bayanihan</b>Center</a>
    </div>

    <!-- /.login-logo -->
    <div class="login-box-body">
        @include('inc.messages')
        <p class="login-box-msg">Forgot Password</p>

        <form action="{{ route('client.forgot-password.submit') }}" method="post">
            @csrf
            <div class="form-group has-feedback">
                <input id="email" type="text" class="form-control" placeholder="Please enter your email..." name="email" value="{{ old('email') }}" required>
                <span class="glyphicon glyphicon-user form-control-feedback"></span>
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-12">
                <button type="submit" class="btn btn-primary btn-block btn-flat">I forgot my password!</button>
                </div>
                <!-- /.col -->
            </div>
        </form>
        <br>
        <a href="{{ route('client.login') }}">I remembered my password</a><br>
        <a href="{{ route('client.register') }}" class="text-center">Register a new membership</a>
    </div>
  <!-- /.login-box-body -->

    <div class="messages">
        <?php
            if($errors) {
            foreach ($errors as $key => $value) {
                echo '<div class="alert alert-warning" role="alert">
                <i class="glyphicon glyphicon-exclamation-sign"></i>
                '.$value.'</div>';
                }
            }
        ?>
    </div>
@endsection
