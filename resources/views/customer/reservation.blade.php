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
var ctrEquipment = 1;
$(function() {
    var preffrtype = @json(old('frtype'));
    var oldinput = @json(old('PrefFuncRooms'));
    var oldtb = @json(old('tblock'));
    var $meetingrooms = @json($meetingrooms);
    var $equip = @json(old('equipments'));
    var $qty = @json(old('quantity'));
    var $tot = @json(old('total'));
    var clickedEquipment = false;
    var limitEquipment = $('#Equipment option').length;

    if (preffrtype == 'FH') {
        $('#preffh option').each(function() {
            var inst = $(this);

            $.each(oldinput, function(key, val){
                if (inst.val() == val) {
                    inst.prop('selected', true);
                }
            });
        });

        $('#preffh').attr('disabled', false);
        $('#functionhalls').show();
    } else if (preffrtype == 'MR') {
        $('#timeblock option').each(function() {
            if ($(this).val() == oldtb) {
                $(this).prop('selected', true);

                return false;
            }
        });

        var combos = @JSON($meetrmdiscount);
        $('#prefmr').empty();
        $.each($meetingrooms, function(key, val) {
            $('#prefmr').append('<option data-timestart="'+val.timestart+'" data-mincap="'+val.mincapacity+'" data-maxcap="'+val.maxcapacity+'" data-timeend="'+val.timeend+'" data-id="'+val.timeblockcode+'" value="'+val.code+'">'+val.name+' || '+val.mincapacity+' - '+val.maxcapacity+' pax</option>');
        });
        $.each(combos, function(key, val) {
            $('#prefmr').append('<option data-timestart="'+val.timestart+'" data-mincap="'+val.mincapacity+'" data-maxcap="'+val.maxcapacity+'" data-timeend="'+val.timeend+'" data-id="'+val.timeblockcode+'" value="'+val.code+'">'+val.name+' || '+val.mincapacity+' - '+val.maxcapacity+' pax</option>');
        });
        switch (oldtb) {
            case 'C':
            case 'F':
            case 'G':
                $('#prefmr').attr('multiple', false);
                // $('#prefmr').select2();
                break;
            default:
                $('#prefmr').attr('multiple', true);
                // $('#prefmr').select2();
        }

        $('#prefmr option').each(function() {
            var mr = $(this);
            var mrtimeblock = $(this).data('id');
            
            if (!mrtimeblock.includes(oldtb)) {
                // mr.attr('disabled', true);
                mr.remove();
            }
        });

        $('#prefmr').val(null);
        $('#prefmr').select2();

        $('#timestart').val($('#timeblock :selected').data('timestart'));
        $('#timestart').prop('readonly', true);
        $('#timeend').val($('#timeblock :selected').data('timeend'));
        $('#timeend').prop('readonly', true);

        if (oldtb == '' || oldtb == null) {
            $('#prefmr').attr('disabled', true);
        } else {
            updateFunctionRooms($('#EventDate').val());
            $('#prefmr').attr('disabled', false);
        }

        $('#prefmr option').each(function() {
            var inst = $(this);

            $.each(oldinput, function(key, val) {
                if (inst.val() == val) {
                    inst.prop('selected', true);
                } 
            });
        });

        $('#prefmr').attr('disabled', false);
        $('#mrtimeblock').show();
        $('#meetingroomblock').show();
    }

    if (!Array.isArray($equip) || !$equip.length) {
    } else {
        var eid = new Array();
        var disp_equip_id = new Array();
        var total = 0;

        $('#listEquipment').append('<div class="row"><label id="lbl1" for="" class="col-sm-3 control-label">Equipment</label>'+
        '<label id="lbl2" for="" class="col-sm-offset-1 col-sm-1 control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Quantity</label>'+
        '<label id="lbl3" for="" class="col-sm-offset-1 col-sm-1 control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price</label>'+
        '<label class="col-sm-offset-2 col-sm-3" id="lbl4" style="visibility:hidden">asdzxcxzczxczcxzczxczxczxxczxczxczczxxczczczxcczxczcz</label><br /><br />'+
        '<div class="row">'+
                '<label class="col-sm-offset-5 col-sm-1 control-label" id="lbltotal">Total: P</label>'+
                '<div class="col-sm-2">'+
                    '<input type="text" id="grandtot" class="form-control" readonly>'+
                '</div>'+
            '</div>');
        clickedEquipment = true;

        $('#Equipment option').each(function() {
            var inst = $(this);

            $.each($equip, function(key, val) {
                if (val == inst.val()) {
                    eid.push(inst.data('id'));
                    disp_equip_id.push(inst.text());
                }
            });
        });
        
        $.each($equip, function(key, val) {
            var add = '<div class="form-group" id="equipRow'+eid[key]+'">'+
                    '<div style="display: none">'+
                        '<input class="form-control" type="text" data-id="'+eid[key]+'" id="unique'+eid[key]+'" name="equipments[]" value="'+ val +'" readonly>'+
                    '</div>'+
                    '<div class="col-sm-offset-1 col-sm-3">'+
                        '<input class="form-control" type="text" value="'+ disp_equip_id[key] +'" readonly>'+
                    '</div>'+
                    '<div class="col-sm-2">'+
                        '<input class="form-control" onkeyup="computePrice('+eid[key]+', this)" onchange="computePrice('+eid[key]+', this)" id="prodlimit'+eid[key]+'" type="number" name="'+$qty[key]+'" min="1" required>'+
                    '</div>'+
                    '<div class="col-sm-2">'+
                        '<input type="text" data-id="total" name="total[]" data-e_id="'+eid[key]+'" class="form-control" id="equipTotal'+eid[key]+'" readonly value="'+$tot[key]+'">' +
                    '</div>'+
                    '<div class="col-sm-2">'+
                        '<button type="button" class="btn btn-danger" id="removeEquipment" onclick="removeEquipmentRow('+eid[key]+')">REMOVE EQUIPMENT</button>' +
                    '</div>'+
                  '</div>';
            $(add).insertBefore($('#lbltotal'));

            ctrEquipment++;

            total += $tot[key];

            $('input[type=number]').not('#NumAttendees').bind('keyup mouseup', validate);
            validate();
        });
        
        $('#lbltotal').val(total);
    }
    
    $('[data-toggle="tooltip"]').tooltip();
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
    $('#prefmr').select2();
    $('#preffh').select2();

    //##################################################################
    // ON change of Function Room Type
    //##################################################################
    $('#funcroomtype').on('change', function() {
        var selroomtype = $(this).val();

        if (selroomtype == 'FH') {
            $('#prefmr option:selected').prop('selected', false);
            $('#prefmr').select2();
            $('#timeblock').val(null);
            $('#meetingroomblock').hide();
            $('#functionhalls').show();
            $('#prefmr').attr('disabled', true);
            $('#preffh').attr('disabled', false);

            $('#timestart').val('');
            $('#timestart').prop('readonly', false);
            $('#timeend').val('');
            $('#timeend').prop('readonly', false);
        
            validate();
        } else if (selroomtype == 'MR') {
            $('#preffh option:selected').prop('selected', false);
            $('#preffh').select2();
            $('#functionhalls').hide();
            $('#meetingroomblock').show();
            $('#mrtimeblock').show();
            
            $('#preffh').attr('disabled', true);
            $('#prefmr').attr('disabled', true);
        
            validate();
        } else {
            $('#preffh option:selected').prop('selected', false);
            $('#prefmr option:selected').prop('selected', false);
            $('#preffh').select2();
            $('#prefmr').select2();
            $('#timeblock').val(null);
            $('#meetingroomblock').hide();
            $('#functionhalls').hide();
            $('#prefmr').attr('disabled', true);
            $('#preffh').attr('disabled', true);
        
            validate();
        }
    });

    //##################################################################
    // ON change of Timeblock
    //##################################################################
    $('#timeblock').on('change', function() {
        var seltb = $(this).val();
        var combos = @JSON($meetrmdiscount);
        
        $('#prefmr').empty();
        $.each($meetingrooms, function(key, val) {
            $('#prefmr').append('<option data-timestart="'+val.timestart+'" data-mincap="'+val.mincapacity+'" data-maxcap="'+val.maxcapacity+'" data-timeend="'+val.timeend+'" data-id="'+val.timeblockcode+'" value="'+val.code+'">'+val.name+' || '+val.mincapacity+' - '+val.maxcapacity+' pax</option>');
        });
        $.each(combos, function(key, val) {
            $('#prefmr').append('<option data-timestart="'+val.timestart+'" data-mincap="'+val.mincapacity+'" data-maxcap="'+val.maxcapacity+'" data-timeend="'+val.timeend+'" data-id="'+val.timeblockcode+'" value="'+val.code+'">'+val.name+' || '+val.mincapacity+' - '+val.maxcapacity+' pax</option>');
        });

        switch (seltb) {
            case 'C':
            case 'F':
            case 'G':
                $('#prefmr').attr('multiple', false);
                // $('#prefmr').select2();
                break;
            default:
                $('#prefmr').attr('multiple', true);
                // $('#prefmr').select2();
        }

        $('#prefmr option').each(function() {
            var mr = $(this);
            var mrtimeblock = $(this).data('id');
            
            if (!mrtimeblock.includes(seltb)) {
                // mr.attr('disabled', true);
                mr.remove();
            }
        });

        $('#prefmr').val(null);
        $('#prefmr').select2();

        $('#timestart').val($('#timeblock :selected').data('timestart'));
        $('#timestart').prop('readonly', true);
        $('#timeend').val($('#timeblock :selected').data('timeend'));
        $('#timeend').prop('readonly', true);

        if (seltb == '' || seltb == null) {
            $('#prefmr').attr('disabled', true);
        } else {
            updateFunctionRooms($('#EventDate').val());
            $('#prefmr').attr('disabled', false);
        }
    });

    //##################################################################
    // ON change of MR
    //##################################################################
    $('#prefmr').on('change', function() {
        var mincap = null;
        var maxcap = null;

        $('#prefmr option:selected').each(function() {
            mincap += $(this).data('mincap');
            maxcap += $(this).data('maxcap');
        });
        
        $('#NumAttendees').attr('min', mincap);
        $('#NumAttendees').attr('max', maxcap + 5);

        if ($('#NumAttendees').val() < maxcap * .50 && $('#NumAttendees').val() != '' && $('#prefmr option:selected').length > 0) {
            swal({
                title: 'Warning!',
                html: 'Number of attendees is less than 50% of the maximum capacity of the venue!<br><strong>Attendees: '+$('#NumAttendees').val()+'</strong><br><strong>Venue Minimum Capacity: '+maxcap+'</strong>',
                type: 'warning'
            });
        }

        if ($('#NumAttendees').val() > maxcap + 5 && $('#prefmr option:selected').length > 0) {
            swal({
                title: 'Warning!',
                html: 'Number of attendees exceeds the maximum capacity of the venue!<br><strong>Attendees: '+$('#NumAttendees').val()+'</strong><br><strong>Venue Maximum Capacity: '+maxcap+'</strong>',
                type: 'warning'
            });
        }

        if ($(this).prop('multiple') === true) {
            var rooms = null;

            $.each($('#prefmr').val(), function(key, val){
                if (val.includes('|')) {
                    rooms = val;
                    return false;
                }
            });
            
            if (rooms) {
                $('#prefmr option').each(function() {
                    var rmoption = $(this);

                    if (rmoption.val() == '' || rmoption.val() == null) {
                        return;
                    } else if (rooms.includes(rmoption.val())) {
                        if (rooms == rmoption.val()) {
                            return;
                        }
                        rmoption.prop('selected', false);
                        rmoption.prop('disabled', true);
                    } else if ( rooms == rmoption.val() ) {
                        console.log('test');
                    } else {
                        rmoption.prop('disabled', false);
                    }
                });

                $('#prefmr').select2();
            } else {
                $('#prefmr option').each(function() {
                    if (typeof $(this).data('reserved') !== 'undefined') {
                        return;
                    }

                    $(this).prop('disabled', false);
                });

                var test = $('#prefmr').val().join('|');
                $('#prefmr option').each(function() {
                    var opt = $(this);

                    if (test == opt.val()) {
                        opt.prop('selected', true);

                        $('#prefmr option:selected').each(function() {
                            var disabling = $(this);

                            if ( test.includes(disabling.val()) ) {
                                if (test == disabling.val()) {
                                    return;
                                }
                                
                                disabling.prop('disabled', true);
                                disabling.prop('selected', false);
                            }
                        });
                        return false;
                    }
                });

                $('#prefmr').select2();
            }
        }
    });

    //##################################################################
    // ON change of FH
    //##################################################################
    $('#preffh').on('change', function() {
        var mincap = null;
        var maxcap = null;

        $('#preffh option:selected').each(function() {
            mincap += $(this).data('mincap');
            maxcap += $(this).data('maxcap');
        });
        
        $('#NumAttendees').attr('min', mincap);
        $('#NumAttendees').attr('max', maxcap + 10);

        if ($('#NumAttendees').val() < mincap * .75  && $('#NumAttendees').val() != '' && $('#preffh option:selected').length > 0) {
            swal({
                title: 'Warning!',
                html: '<p style="font-size: 30">Number of attendees is significantly less than the minimum capacity of the venue!<br><strong>Attendees: '+$('#NumAttendees').val()+'</strong><br><strong>Venue Minimum Capacity: '+mincap+'</strong><p>',
                type: 'warning'
            });
        } else if ($('#NumAttendees').val() < mincap * .9) {
            if ($('#NumAttendees').val() != '' && $('#preffh option:selected').length > 0) {
                swal({
                    title: 'Warning!',
                    html: 'Number of attendees is less than the minimum capacity of the venue!<br><strong>Attendees: '+$('#NumAttendees').val()+'</strong><br><strong>Venue Minimum Capacity: '+mincap+'</strong>',
                    type: 'warning'
                });
            }
        }

        if ($('#NumAttendees').val() > maxcap + 10 && $('#preffh option:selected').length > 0) {
            swal({
                title: 'Warning!',
                html: 'Number of attendees exceeds the maximum capacity of the venue!<br><strong>Attendees: '+$('#NumAttendees').val()+'</strong><br><strong>Venue Maximum Capacity: '+maxcap+'</strong>',
                type: 'warning'
            });
        }
    });

    $('#NumAttendees').on('change', function() {
        if ( !$('#prefmr').prop('disabled') && $('#prefmr option:selected').length > 0) {
            var mincap = null;
            var maxcap = null;

            $('#prefmr option:selected').each(function() {
                mincap += $(this).data('mincap');
                maxcap += $(this).data('maxcap');
            });

            $('#NumAttendees').attr('min', mincap);
            $('#NumAttendees').attr('max', maxcap + 5);

            if ($('#NumAttendees').val() < maxcap * .50 && $('#NumAttendees').val() != '' && $('#prefmr option:selected').length > 0) {
                swal({
                    title: 'Warning!',
                    html: 'Number of attendees is less than 50% of the maximum capacity of the venue!<br><strong>Attendees: '+$('#NumAttendees').val()+'</strong><br><strong>Venue Minimum Capacity: '+maxcap+'</strong>',
                    type: 'warning'
                });
            }

            if ($('#NumAttendees').val() > maxcap + 5 && $('#prefmr option:selected').length > 0) {
                swal({
                    title: 'Warning!',
                    html: 'Number of attendees exceeds the maximum capacity of the venue!<br><strong>Attendees: '+$('#NumAttendees').val()+'</strong><br><strong>Venue Maximum Capacity: '+maxcap+'</strong>',
                    type: 'warning'
                });
            }
        } else if ( !$('#preffh').prop('disabled') && $('#preffh option:selected').length > 0) {
            var mincap = null;
            var maxcap = null;

            $('#preffh option:selected').each(function() {
                mincap += $(this).data('mincap');
                maxcap += $(this).data('maxcap');
            });
            
            $('#NumAttendees').attr('min', mincap);
            $('#NumAttendees').attr('max', maxcap + 10);

            if ($('#NumAttendees').val() < mincap * .75  && $('#NumAttendees').val() != '' && $('#preffh option:selected').length > 0) {
                swal({
                    title: 'Warning!',
                    html: '<p style="font-size: 30">Number of attendees is significantly less than the minimum capacity of the venue!<br><strong>Attendees: '+$('#NumAttendees').val()+'</strong><br><strong>Venue Minimum Capacity: '+mincap+'</strong><p>',
                    type: 'warning'
                });
            } else if ($('#NumAttendees').val() < mincap * .9 && $('#preffh option:selected').length > 0) {
                if ($('#NumAttendees').val() != '') {
                    swal({
                        title: 'Warning!',
                        html: 'Number of attendees is less than the minimum capacity of the venue!<br><strong>Attendees: '+$('#NumAttendees').val()+'</strong><br><strong>Venue Minimum Capacity: '+mincap+'</strong>',
                        type: 'warning'
                    });
                }
            }

            if ($('#NumAttendees').val() > maxcap + 10 && $('#preffh option:selected').length > 0) {
                swal({
                    title: 'Warning!',
                    html: 'Number of attendees exceeds the maximum capacity of the venue!<br><strong>Attendees: '+$('#NumAttendees').val()+'</strong><br><strong>Venue Maximum Capacity: '+maxcap+'</strong>',
                    type: 'warning'
                });
            }
        }
    });

    //##################################################################
    // Disable submit button until every 'required' field has value
    //##################################################################
    validate();
    $('input').filter('[required]:visible').on({
        keyup: function() {
            validate();
        },
        change: function() {
            validate();
        }
    });
    $('#consent').change(validate);

    //##################################################################
    // Dynamic Radio Button that sees if Catererer selected is Accredited
    //##################################################################
    $caterer.on('change', function (event) {
        var selected = $('#CatererName :selected:last').val();
        var ondb = false;

        $('#CatererNameData option').each(function() {
            if (selected == $(this).val()) {
                ondb = true;
                return false;
            }
        });

        if (ondb) {
            $('#dispaccredited').prop('checked', true);
            $('#accredited').prop('checked', true);
            $('#dispnotaccredited').prop('checked', false);
            $('#notaccredited').prop('checked', false);
        } else {
            $('#dispnotaccredited').prop('checked', true);
            $('#notaccredited').prop('checked', true);
            $('#dispaccredited').prop('checked', false);
            $('#accredited').prop('checked', false);
        }
    });
    

    //##################################################################
    // for Adding of Equipment
    //##################################################################
    $('#addEquipment').click(function() {
        
        if (ctrEquipment > limitEquipment) {
            return false;
        }

        if (!clickedEquipment) {
            $('#listEquipment').append('<div class="row"><label id="lbl1" for="" class="col-sm-3 control-label">Equipment</label>'+
            '<label id="lbl2" for="" class="col-sm-offset-1 col-sm-1 control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Quantity</label>'+
            '<label id="lbl3" for="" class="col-sm-offset-1 col-sm-1 control-label">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Price</label>'+
            '<label class="col-sm-offset-2 col-sm-3" id="lbl4" style="visibility:hidden">asdzxcxzczxczcxzczxczxczxxczxczxczczxxczczczxcczxczcz</label><br /><br />'+
            '<div class="row">'+
                    '<label class="col-sm-offset-5 col-sm-1 control-label" id="lbltotal">Total: P</label>'+
                    '<div class="col-sm-2">'+
                        '<input type="text" id="grandtot" class="form-control" readonly>'+
                    '</div>'+
                '</div>');
            clickedEquipment = true;
        }

        var $equipment = $('#Equipment');
        var equip_id = $equipment.val();
        var disp_equip_id = $("option:selected", $equipment).text();
        var eid = $("option:selected", $equipment).data('id');
        var exists = false;

        $('#listEquipment [id^=unique]').each(function() {
            if ($(this).data('id') == eid) {
                exists = true;
                return false;
            }
        });

        var add = '<div class="form-group" id="equipRow'+eid+'">'+
                    '<div style="display: none">'+
                        '<input class="form-control" type="text" data-id="'+eid+'" id="unique'+eid+'" name="equipments[]" value="'+ equip_id +'" readonly>'+
                    '</div>'+
                    '<div class="col-sm-offset-1 col-sm-3">'+
                        '<input class="form-control" type="text" value="'+ disp_equip_id +'" readonly>'+
                    '</div>'+
                    '<div class="col-sm-2">'+
                        '<input class="form-control" onkeyup="computePrice('+eid+', this)" onchange="computePrice('+eid+', this)" id="prodlimit'+eid+'" type="number" name="quantity[]" min="1" required>'+
                    '</div>'+
                    '<div class="col-sm-2">'+
                        '<input type="text" data-id="total" name="total[]" data-e_id="'+eid+'" class="form-control" id="equipTotal'+eid+'" readonly>' +
                    '</div>'+
                    '<div class="col-sm-2">'+
                        '<button type="button" class="btn btn-danger" id="removeEquipment" onclick="removeEquipmentRow('+eid+')">REMOVE EQUIPMENT</button>' +
                    '</div>'+
                  '</div>';
        
        if (!exists) {
            $(add).insertBefore($('#lbltotal'));

            ctrEquipment++;

            $('input[type=number]').not('#NumAttendees').bind('keyup mouseup', validate);
            validate();
        }

        if ($('#timestart').val() == '' || $('#timestart').val() == null || $('#timeend').val() == '' || $('#timeend').val() == null) {
            $('[id^=equipRow] [id^=prodlimit]').each(function() {
                $(this).attr('disabled', true);
            });
        }
    });

    //##################################################################
    // On date change
    //##################################################################
    $('#EventDate').on({
        keyup: function() {
            var date = $(this).val();
            updateFunctionRooms(date);
        },
        change: function() {
            var date = $(this).val();
            updateFunctionRooms(date);
        }
    });


    //##################################################################
    // Changing of time
    //##################################################################
    $('#timestart').on({
        keyup: function() {
            timeChange();
        },
        change: function() {
            timeChange();
            if ( ($(this).val() != null && $(this).val() != '') && ($('#timeend').val() != null && $('#timeend').val() != '') ) {
                $('[id^=equipRow] [id^=prodlimit]').each(function() {
                    var inst = $(this);

                    if (inst.attr('disabled')) {
                        inst.attr('disabled', false);
                    }
                });
            } else {

            }
        }
    });
    $('#timeend').on({
        keyup: function() {
            timeChange();
        },
        change: function() {
            timeChange();
            if ( ($(this).val() != null && $(this).val() != '') && ($('#timestart').val() != null && $('#timestart').val() != '') ) {
                $('[id^=equipRow] [id^=prodlimit]').each(function() {
                    var inst = $(this);

                    if (inst.attr('disabled')) {
                        inst.attr('disabled', false);
                    }
                });
            } else {
                
            }
        }
    });
});

function updateFunctionRooms(date) {
    $.ajax({
        url: '/getreservedrooms',
        type: 'POST',
        data: {
            _token: $('meta[name=csrf-token]').attr('content'),
            date: date,
        },
        success: function(data) {
            $('#prefmr option').each(function(){ 
                $(this).prop('disabled', false);
            });
            $('#preffh option').each(function(){ 
                $(this).prop('disabled', false);
            });

            $('#preffh option').each(function(){ 
                room = $(this);

                $.each(data, function(key, val) {
                    if (room.val() == val.venuecode) {
                        room.prop('disabled', true);
                        room.prop('selected', false);
                    }
                });
            });
            $('#prefmr option').each(function(){ 
                room = $(this);

                $.each(data, function(key, val) {
                    if (room.val().includes(val.venuecode) && room.data('timestart') == val.timestart && room.data('timeend') == val.timeend) {
                        room.prop('disabled', true);
                        room.prop('selected', false);
                        room.attr('data-reserved', '1');
                    }
                });
            });

            $('#preffh').select2('destroy');
            $('#preffh').select2();
            $('#prefmr').select2('destroy');
            $('#prefmr').select2();
        }
    });
}

function computePrice (equipment_id, src) {
    equipmentlist = @json($equipments);

    var timeStart = new Date(getTodayDate() + ' ' + $('#timestart').val());
    var timeEnd = new Date(getTodayDate() + ' ' + $('#timeend').val());
    var diff = (timeEnd - timeStart)/1000/60/60;
    var qtyequipment = src.value;
    var equipmentrate = 0;
    var total = 0;
    var grandTotal = 0;

    if (diff <= 0) {
        diff = 24 + diff;
    }

    $.each(equipmentlist, function(key, val) {
        if (val.id == equipment_id) {
            if (diff >= 1.0 && diff <= 5.0) {
                equipmentrate = val.halfdayrate;
                total = qtyequipment * equipmentrate;
            } else if (diff > 5.0) {
                equipmentrate = val.wholedayrate;
                total = qtyequipment * equipmentrate;

                if (diff > 10.0) {
                    if (val.wholedayrate == val.halfdayrate && val.halfdayrate == val.hourlyexcessrate) {
                        return false;
                    } else {
                        total += (diff - 10) * val.hourlyexcessrate * qtyequipment;
                    }
                }
            }

            return false;
        }
    });

    $('#equipTotal' + equipment_id).val(total);

    $('#listEquipment input').each( function() {
        if ($(this).data('id') == 'total' && parseFloat($(this).val())) {
            grandTotal += parseFloat($(this).val());
        }
    });
    
    $('#grandtot').val(grandTotal);
}

function timeChange() {
    equipmentlist = @json($equipments);
    var now = new Date(getTodayDate() + ' ' + '00:00');
    var timeStart = new Date(getTodayDate() + ' ' + $('#timestart').val());
    var timeEnd = new Date(getTodayDate() + ' ' + $('#timeend').val());
    var diff = (timeEnd - timeStart)/1000/60/60;
    var grandTotal = 0;

    console.log(diff);
    
    if (diff <= 0) {
        if (diff >= 1 || diff <= 5) {

        } else if (diff > 5 || diff <= 10) {

        } else {

        }
        diff = 24 + diff;
    }

    // if ((now - timeEnd)/1000/60/60 < 0 && (now - timeEnd)/1000/60/60 >= -7.5) {
    //     $('#timeend').val('00:00')
    // }

    $.each(equipmentlist, function(key, val) {
        $('#listEquipment input').each(function() {
            if ($(this).data('e_id') == val.id && parseFloat($(this).val())) {
                if (diff >= 1.0 && diff <= 5.0) {
                    $(this).val($('#prodlimit' + $(this).data('e_id')).val() * val.halfdayrate);
                } else if (diff > 5.0) {
                    $(this).val($('#prodlimit' + $(this).data('e_id')).val() * val.wholedayrate);
                }
            }
        });
    });

    $('#listEquipment input').each( function() {
        if ($(this).data('id') == 'total' && parseFloat($(this).val())) {
            grandTotal += parseFloat($(this).val());
        }
    });

    $('#grandtot').val(grandTotal);
    validate();
}

function removeEquipmentRow(sourceRow) {
    $('#equipRow' + sourceRow).remove();
    ctrEquipment--;
    var total = 0;

    $('[id^=equipRow] [id^=equipTotal]').each(function() {
        total += parseFloat($(this).val());
    });
    $('#grandtot').val(total);
    validate();
}

function validate() {
    var inputswithval = 0;
    var isChecked = $('#consent').prop('checked');
    var inputs = $('input').filter('[required]:visible').not('#consent');
    
    inputs.each(function (e) {
        if($(this).val().trim()) {
            inputswithval++;
        }
    });

    if (isChecked) {
        if (inputswithval == inputs.length) {
            $("#btnsubmit").prop("disabled", false);
        }
    } else {
        $("#btnsubmit").prop("disabled", true);
    }

    if (inputswithval == inputs.length && isChecked) {
        if (($('#preffh').prop('disabled') && $('#prefmr').prop('disabled'))) {
            $("#btnsubmit").prop("disabled", true);
        } else {

            if ( !($('#preffh').prop('disabled')) && ($('#preffh').val()).length ) {
                // $("#btnsubmit").prop("disabled", false);
            } else if ( !($('#prefmr').prop('disabled')) ) {
                // $("#btnsubmit").prop("disabled", false);
            } else {
                $("#btnsubmit").prop("disabled", true);
            }
        }
    } else {
        $("#btnsubmit").prop("disabled", true);
    }
}

function getTodayDate() {
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    var yyyy = today.getFullYear();

    if(dd<10) {
        dd = '0'+dd
    } 

    if(mm<10) {
        mm = '0'+mm
    } 

    today = mm + '/' + dd + '/' + yyyy;
    return today;
}

</script>
@endsection