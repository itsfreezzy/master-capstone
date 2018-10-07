@extends('layouts.authentication')

@section('title')
Registration | Bayanihan Center
@endsection

@section('content')
<div class="register-box">
    <div class="register-logo">
        <a href="/"><b>Bayanihan</b>Center</a>
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
                            <input type="checkbox" name="consent" id=""> I agree to the <a href="#modalGuidelines" data-toggle="modal">terms/conditions</a>
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

    <div class="modal fade" id="modalGuidelines" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title"><i class="fa fa-warning"></i> Bayanihan Center Guidelines</h4>
                </div>
                <div class="modal-body">
                    {{-- RESERVATION PROCESS --}}
                    <h5><strong>RESERVATION PROCESS</strong></h5>
                    <ul>
                        <li>Check availability of function halls / meeting rooms through email at bcmoreservation@unilab.com.ph or through phone call at 858-1978 / 858-1985 or through the Schedules tab of the website.</li>
                        <li>Submit an accomplished copy of the reservation form.</li>
                        <li>We required at least 3 months lead time for reservations to be made. Pencil bookings are allowed for the period of 2 weeks only from the date of placing the reservation. Bookings unconfirmed within the allowable 2-week period will automatically be cancelled.</li>
                        <li>To confirm the reservation, the client must pay the reservation fee of PhP 5000. The management will then verify the payment, and then confirm the reservation.</li>
                        <li>30 days after confirmation the 50% downpayment needs to be settled. The billing statement and reservation contract will then be signed by both parties.</li>
                        <li>Full payment shall be required 30 days before the event date.</li>
                        <li>Only accredited caterers shall be allowed to handle the food requirements of events booked at the Bayanihan Center. A corresponding corkage fee of PhP 15000 + 12% VAT will be charged to non-accredited caterers.</li>
                    </ul><br>

                    {{-- PAYMENT TERMS --}}
                    <h5><strong>PAYMENT TERMS</strong></h5>
                    <ul>
                        <li>Reservation Fee - PhP 5,000 (paid upon confirmation, Non-refundable)</li>
                        <li>50% downpayment (paid 30 days after confirmation)</li>
                        <li>50% full payment (paid 30 days before the event)</li>
                        <li>Security Deposit - PhP 10,000 (lodged 15 days before the event, returned less charges 3 days after the event)</li>
                    </ul><br>

                    {{-- CANCELLATION CHARGES --}}
                    <h5><strong>CANCELLATION CHARGES</strong></h5>
                    <ul>
                        <li>2 months prior to function date &emsp; - &emsp; 50% of required deposit</li>
                        <li>1 month prior to function date &emsp; - &emsp; Forfeiture of required deposit</li>
                        <li>2 weeks prior to function date &emsp; - &emsp; 100% cancellation charge</li>
                    </ul><br>

                    {{-- HOUSE RULES AND REGULATIONS --}}
                    <h5><strong>HOUSE RULES AND REGULATIONS</strong></h5>
                    <ul>
                        <li>The Center is a <strong>NO SMOKING</strong> facility.</li>
                        <li>Hanging, pinning, pasting, and nailing of any promo/display/ad/announcement materials shall not be allowed on the wall or any part of the facility. STAND ALONE display/ads materials and booths shall be the preferred exhibits.</li>
                        <li>No promo/ad/display/booths shall be placed within 2-meter radius beside the busts of Mr. JY Campos and Mr. MK Tan</li>
                        <li>Disposal of food and waste materials shall be the responsibility of the organizer. Please follow the <strong>"CLEAN AS YOU GO"</strong> policy.</li>
                        <li>Pets are not allowed inside the center.</li>
                        <li>Any damages done to the function rooms shall be the accountability of the organizer. Corresponding charges shall be billed to and paid by the organizers.</li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"> <i class="fa fa-check"></i> I understand.</button>
                </div>
            </div>
        </div>
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