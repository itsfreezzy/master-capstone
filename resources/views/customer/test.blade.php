@extends('layouts.clientlayout')

@section('title')
NEW RESERVATION | USER - UNILAB Bayanihan Center
@endsection

@section('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('adminlte/bower_components/select2/dist/css/select2.min.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('SmartWizard-master/dist/css/smart_wizard.css') }}" type="text/css">
<link rel="stylesheet" href="{{ asset('SmartWizard-master/dist/css/smart_wizard_theme_arrows.css') }}" type="text/css">
@endsection

@section('content-header')
    <h1>
        New Reservation
        <small>A new reservation with us, yay!</small>
    </h1>
    <ol class="breadcrumb">
        <li class=""><a href="/customer/reservation"><i class="fa fa-calendar"></i> Reservations</a></li>
        <li class="active">Add New Reservation</li>
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
                    @include('customer.reservationform')
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDisplay" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-warning"></i> Confirm Submission</h4>
            </div>
            <div class="modal-body">
                By clicking submit, you are confirming that the information you have entered are correct. <br>
                Proceed?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                <label for="submit-form" class="btn btn-primary">Submit</label>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalGuidelines" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-warning"></i> Bayanihan Center Guidelines</h4>
            </div>
            <div class="modal-body">
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

<div class="modal fade" id="modalViewRates" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-money"></i> Bayanihan Center Equipment Rental Rates</h4>
            </div>
            <div class="modal-body">
                <h3>Material/Equipments<button id="infotooltip" type="button" class="btn btn-flat btn-info" data-toggle="tooltip" data-placement="top" title="Equipments with the same Whole Day Rate, Half Day Rate, and Excess Hour Rate are charged per equipment. Thank you."><i class="fa fa-question"></i></button></h3>
                <table id="tblEquipments" class="table table-bordered table-hovered">
                    <thead>
                        <tr>
                            <th class="col-sm-2 text-center">Material/Equipment</th>
                            <th class="col-sm-1 text-center">Whole Day Rate <br> (6 - 10 Hrs)</th>
                            <th class="col-sm-1 text-center">Half Day Rate <br> (1 - 5 Hrs)</th>
                            <th class="col-sm-1 text-center">Excess Hour Rate</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($equipments as $equipment)
                        <tr>
                            <td >{{ $equipment->name }}</td>
                            <td class="text-right">{{ number_format($equipment->wholedayrate, 2) }}</td>
                            <td class="text-right">{{ number_format($equipment->halfdayrate, 2) }}</td>
                            <td class="text-right">{{ number_format($equipment->hourlyexcessrate, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <a href="#" data-dismiss="modal" class="btn btn-success"><i class="fa fa-check"></i> OK</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('adminlte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('SmartWizard-master/dist/js/jquery.smartWizard.min.js') }}"></script>

<script>
$(function() {
    // Select2 Application
    $('#EventNature, #EventSetup, #CatererName').select2({ tags: true });
    $('#PrefFuncRoom').select2();

    // Tooltip Application
    $('[data-toggle="tooltip"]').tooltip();
});

</script>
@endsection