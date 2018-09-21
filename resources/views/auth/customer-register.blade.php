@extends('layouts.authentication')

@section('title')
Registration | Bayanihan Center
@endsection

@section('content')
<div class="register-box">
    <div class="register-logo">
        <a href="#"><b>Bayanihan</b>Center</a>
    </div>

    <!-- /.register-logo -->
    <div class="register-box-body">
        <p class="register-box-msg">Bayanihan Center CLIENT Registration</p>
        @include('inc.messages')
        <form action="{{ route('client.register.submit') }}" method="post">
            @csrf
            <div class="form-group has-feedback">
                <input id="name" type="text" class="form-control" placeholder="Full Name" name="name" value="{{ old('name') }}" required>
                @if ($errors->has('name'))
                    <div class="error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
            <div class="form-group has-feedback">
                <select name="type" id="type" class="form-control" required>
                    <option value="">Select Customer Type</option>
                    <option value="Personal">Personal</option>
                    <option value="Company">Company</option>
                </select>
            </div>
            <div class="form-group has-feedback">
                <input id="tinnumber" type="text" class="form-control" placeholder="TIN Number" name="tinnumber" value="{{ old('tinnumber') }}" data-inputmask="'mask': '999-999-999-999'" required > 
                @if ($errors->has('name'))
                    <div class="error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('tinnumber') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input id="contactnumber" type="text" class="form-control" placeholder="Contact Number" name="contactnumber" value="{{ old('contactnumber') }}" required>
                @if ($errors->has('contactnumber'))
                    <div class="error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('contactnumber') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input id="username" type="text" class="form-control" placeholder="Username" name="username" value="{{ old('username') }}" required>
                @if ($errors->has('username'))
                    <div class="error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('username') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input id="email" type="text" class="form-control" placeholder="E-mail" name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email'))
                    <div class="error">
                        <span style="color: red" role="alert">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    </div>
                @endif
            </div>
            <div class="form-group has-feedback">
                <input id="password" type="password" class="form-control" placeholder="Password" name="password" required>
            </div>
            <div class="form-group has-feedback">
                <input id="password_confirmation" type="password" class="form-control" placeholder="Retype Password" name="password_confirmation" required>
            </div>
            <div class="form-group">
                @captcha()
            </div>
            <div class="row">
                <!-- /.col -->
                <div class="col-xs-8">
                    <div class="checkbox">
                        <label for="">
                            <input type="checkbox" name="consent" id=""> I agree to the <a href="">terms/conditions</a>
                        </label>
                    </div>
                </div>
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block btn-flat">Register</button>
                </div>
                <!-- /.col -->
            </div>
        </form>

        <a href="{{ route('client.login') }}">I already have a membership</a><br>
    </div>
  <!-- /.register-box-body -->

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

@section('scripts')
<script src="{{ asset('adminlte/input-mask/dist/jquery.inputmask.bundle.js') }}"></script>
<script src="{{ asset('adminlte/input-mask/dist/inputmask/bindingsinputmask.binding.js') }}"></script>

<script type="text/javascript">
$(function() {
    $("#tinnumber").inputmask();
});
</script>
@endsection