@extends('layouts.websitelayout')

@section('title')
Schedules - UNILAB Bayanihan Center
@endsection

@section('styles')
<!-- fullCalendar -->
<script type="text/javascript" src="{{asset('adminlte/bower_components/moment/moment.js')}}"></script>
<script type="text/javascript" src="{{asset('adminlte/bower_components/fullcalendar/dist/fullcalendar.min.js')}}"></script>
<!-- fullCalendar -->
<link rel="stylesheet" href="{{asset('adminlte/bower_components/fullcalendar/dist/fullcalendar.min.css')}}">
<link rel="stylesheet" href="{{asset('adminlte/bower_components/fullcalendar/dist/fullcalendar.print.min.css')}}" media="print">
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('adminlte/bower_components/select2/dist/css/select2.min.css') }}" type="text/css">
@endsection

@section('content')
{{--  Calendar  --}}
<h2 class="text-center">Schedule</h2>
<div class="row" style="padding-top: 60px;">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-body no-padding">
                {!! $calendar->calendar() !!}
                {!! $calendar->script() !!}
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalShow">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit</h4>
            </div>

            <form action="">
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Selected Date:</label>
                    <input type="hidden" name="date" id="seldate" name="seldate" class="form-control">
                    <input type="text" name="date" id="dispdate" class="form-control" readonly>
                </div>

                <div class="form-group">
                    <label for="">Function Room Type:</label>
                    <select name="funcroomtype" id="funcroomtype" class="form-control" aria-placeholder="test">
                        <option value="">SELECT FUNCTION ROOM TYPE</option>
                        <option value="FH">Function Hall</option>
                        <option value="MR">Meeting Room</option>
                    </select>
                </div>

                <div class="form-group" id="fhgroup">
                    <label for="" >Available Function Hall(s):</label>
                    <ul id="funchalllist">
                        @foreach ($funchalls as $fhall)
                        <li data-id="{{ $fhall->code }}">{{ $fhall->name }}</li>
                        @endforeach
                    </ul>
                    {{-- <select name="funchall" id="funchall" class="form-control" aria-placeholder="test"style="width: 100%">
                        <option value="">SELECT FUNCTION HALL</option>
                        <option value="FH">Function Hall</option>
                        <option value="MR">Meeting Room</option>
                    </select> --}}
                </div>
                <div id="mrgroup">
                    <div class="form-group" id="mrtimeblock">
                        <label for="">Timeblock:</label>
                        <select name="timeblock" id="timeblock" class="form-control" aria-placeholder="test" style="width: 100%">
                            <option ></option>
                            @foreach ($timeblocks as $timeblock)
                            <option value="{{ $timeblock->code }}">{{ $timeblock->code }} | {{ date('h:i:s A', strtotime($timeblock->timestart)) }} - {{ date('h:i:s A', strtotime($timeblock->timeend)) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" id="meetingroom">
                        <label for="">Available Meeting Room(s):</label>
                        <ul id="meetroomlist">
                            @foreach ($meetrooms as $mroom)
                            <li data-id="{{ $mroom->code }}" data-tbcode="{{ $mroom->timeblockcode }}">{{ $mroom->name }}</li>
                            @endforeach
                        </ul>
                        {{-- <select name="meetroom" id="meetroom" class="form-control" aria-placeholder="test" multiple style="width: 100%">
                            <option value="">SELECT MEETING ROOM</option>
                            <option value="FH">Function Hall</option>
                            <option value="MR">Meeting Room</option>
                        </select> --}}
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                <button type="button" class="btn btn-info" id="btncheckavailability"> <i class="fa fa-question"></i> Check Availability</button>
                <button type="submit" class="btn btn-success" id="" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>
@endsection



@section('scripts')
<!-- Page specific script -->
<!-- Select2 -->
<script src="{{ asset('adminlte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
<script src="{{ asset('SmartWizard-master/dist/js/jquery.smartWizard.min.js') }}"></script>
<script>
$(function () {
    $('#mrgroup').hide();
    $('#meetingroom').hide();
    $('#fhgroup').hide();
    $('#timeblock').select2({
        placeholder: 'Select desired timeblock...',
    });
    $('#funchall').select2({
        placeholder: 'Select desired function halls...',
    });
    $('#meetroom').select2({
        placeholder: 'Select desired meeting rooms...',
    });

    $('#funcroomtype').on('change', function() {
        if ($(this).val() == 'FH') {
            $.ajax({
                url: '/availability/get',
                method: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    date: $('#seldate').val(),
                    type: $(this).val(),
                },
                success: function(funchalls) {
                    $('#mrgroup').hide();
                    $('#fhgroup').show();

                    $('#funchalllist li').each(function() {
                        var id = $(this).data('id');
                        var elem = $(this);

                        if (elem.parent().get(0).tagName == 'STRIKE') {
                            elem.unwrap();
                        }

                        $.each(funchalls, function(index, fhall){
                            if (id == fhall.venuecode) {
                                elem.wrap('<strike>');
                                return false;
                            }
                        });
                    });
                }
            });
        } else if ($(this).val() == 'MR') {
            $.ajax({
                url: '/availability/get',
                method: 'POST',
                data: {
                    _token: $('meta[name=csrf-token]').attr('content'),
                    date: $('#seldate').val(),
                    type: $(this).val(),
                },
                success: function(meetrooms) {
                    $('#fhgroup').hide();
                    $('#mrgroup').show();

                    $('#mrtimeblock').on('change', function() {
                        if ($(this).val() != '' || $(this).val() != null) {
                            $('#meetingroom').show();
                        } else {
                            $('#meetingroom').hide();
                        }
                    });
                }
            });
        } else {
            $('#fhgroup').hide();
            $('#mrgroup').hide();
        }
    });

    $('#modalShow').on('hidden.bs.modal', function(e){
        $('#mrgroup').hide();
        $('#fhgroup').hide();
        $('#funcroomtype').val('');
        $("select").val('').change();
        $('#meetingroom').hide();
    });
});
</script>
@endsection
