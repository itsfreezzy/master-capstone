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
    $('#meetingrooms').hide();
    $('#functionhalls').hide();
    $('#smartwizard').smartWizard({
        selected: 0,
        theme: 'arrows',
    });
    $("#prev-btn").on("click", function() {
        // Navigate previous
        $('#smartwizard').smartWizard("prev");
        return true;
    });

    $("#next-btn").on("click", function() {
        // Navigate next
        $('#smartwizard').smartWizard("next");
        return true;
    });
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
    $('#PrefFuncRoom').select2();


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
    // Select only from one option group for Function Rooms
    //##################################################################
    $meetingrooms = @json($meetingrooms);
    $preffuncroom = $('#PrefFuncRoom').select2();
    $preffuncroom.on('change', function () {
        onPrefRoomChange();
    });

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
    var clickedEquipment = false;
    var limitEquipment = $('#Equipment option').length;
    var ctrEquipment = 1;

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
    });

    //##################################################################
    // On date change
    //##################################################################
    $('#EventDate').on({
        keyup: function() {
            var date = $(this).val();
            updateFunctionRooms(date);
            // onDateChange();
        },
        change: function() {
            var date = $(this).val();
            updateFunctionRooms(date);
            // onDateChange();
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
        }
    });
    $('#timeend').on({
        keyup: function() {
            timeChange();
        },
        change: function() {
            timeChange();
        }
    });
});

function onPrefRoomChange() {
    var seloptgroup = $('#PrefFuncRoom :selected:last').parent().attr('label');
    $('#timestart').val('');
    $('#timestart').prop('readonly', false);
    $('#timeend').val('');
    $('#timeend').prop('readonly', false);

    $('#PrefFuncRoom optgroup').each(function() {
        if (typeof seloptgroup === 'undefined') {
            $('#PrefFuncRoom optgroup[label="'+$(this).attr('label')+'"]').each(function() {
                $(this).find('option').prop('disabled', false);
            })

            $('#PrefFuncRoom').select2('destroy');
            $('#PrefFuncRoom').select2();
        }
        else if ($(this).attr('label') != seloptgroup) {
            $('#PrefFuncRoom optgroup[label="'+$(this).attr('label')+'"]').each(function() {
                $(this).find('option').prop('disabled', true);
            })

            $('#PrefFuncRoom').select2('destroy');
            $('#PrefFuncRoom').select2();
        }
    });

    if (seloptgroup == "Meeting Rooms") {
        var lastselected = $('#PrefFuncRoom :selected:last');

        $.each($meetingrooms, function(key, val) {
            if (lastselected.val() == val.code) {
                console.log($(this))
                $('#timestart').val(val.timestart);
                $('#timestart').prop('readonly', true);
                $('#timeend').val(val.timeend);
                $('#timeend').prop('readonly', true);
                return false;
            }
        });
    }

    if (seloptgroup == "Meeting Rooms") { 
        var lastselected = $('#PrefFuncRoom :selected:last');
        var ctr = $('#PrefFuncRoom :selected').length;
        
        if (ctr > 1) {
            $('#PrefFuncRoom :selected').each(function() {
                var cont = $(this).data('id');
                if (cont != lastselected.data('id')) {
                    lastselected.prop('selected', false);
                    $('#PrefFuncRoom').select2('destroy');
                    $('#PrefFuncRoom').select2();                               
                }
            });
        }
    }
}

function updateFunctionRooms(date) {
    $.ajax({
        url: '/getreservedrooms',
        type: 'POST',
        data: {
            _token: $('meta[name=csrf-token]').attr('content'),
            date: date,
        },
        success: function(data) {
            $('#PrefFuncRoom option').each(function(){ 
                $(this).prop('disabled', false);
            });

            $('#PrefFuncRoom option').each(function(){
                room = $(this);

                $.each(data, function(key, val) {
                    if (room.val() == val.venuecode) {
                        room.prop('disabled', true);
                        room.prop('selected', false);
                    }
                });
            });

            var seloptgroup = $('#PrefFuncRoom :selected:last').parent().attr('label');
            $('#PrefFuncRoom option').each(function() {
                if ($('#PrefFuncRoom :selected:last').val() == undefined) {
                    return false;
                }
                else if ($(this).parent().attr('label') != seloptgroup) {
                    $(this).prop('disabled', true);
                }
            });
            $('#PrefFuncRoom').select2('destroy');
            $('#PrefFuncRoom').select2();
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
                        total += (diff - 10) * val.hourlyexcessrate;
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
    
    if (diff <= 0) {
        if (diff >= 1 || diff <= 5) {

        } else if (diff > 5 || diff <= 10) {

        } else {

        }
        diff = 24 + diff;
    }

    if ((now - timeEnd)/1000/60/60 < 0 && (now - timeEnd)/1000/60/60 >= -7.5) {
        $('#timeend').val('00:00')
    }

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
    validate();
    ctrEquipment--;
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
        $("#btnsubmit").prop("disabled", false);
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