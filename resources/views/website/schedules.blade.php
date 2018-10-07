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
                <h4 class="modal-title"><i class="fa fa-eye"></i> Check Available Rooms</h4>
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
                        <li data-id="{{ $fhall->code }}">{{ $fhall->name }} || {{ $fhall->mincapacity }} - {{ $fhall->maxcapacity }} pax</li>
                        @endforeach
                        @foreach ($fhdiscount as $fh) 
                        <li data-id="{{ $fh->code }}" >{{ $fh->name }} || {{ $fh->mincapacity }} - {{ $fh->maxcapacity }} pax</li>
                        @endforeach
                    </ul>
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
                            <li data-id="{{ $mroom->code }}" data-tbcode="{{ $mroom->timeblockcode }}">{{ $mroom->name }} || {{ $mroom->mincapacity }} - {{ $mroom->maxcapacity }} pax</li>
                            @endforeach
                            @foreach ($meetrmdiscount as $mr) 
                            <li data-id="{{ $mr->code }}" data-tbcode="{{ $mr->timeblockcode }}">{{ $mr->name }} || {{ $mr->mincapacity }} - {{ $mr->maxcapacity }} pax</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> OK</button>
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
                            if (id.includes(fhall.venuecode) && fhall.status == 'Confirmed') {
                                elem.wrap('<strike>');
                                return false;
                            } else if (id.includes(fhall.venuecode) && fhall.status == 'Pending') {
                                elem.wrap('<i>');
                                return false;
                            }
                        });
                    });
                }
            });
        } else if ($(this).val() == 'MR') {
            $('#fhgroup').hide();
            $('#mrgroup').show();

            $('#timeblock').on('change', function() {
                $.ajax({
                    url: '/availability/get',
                    method: 'POST',
                    data: {
                        _token: $('meta[name=csrf-token]').attr('content'),
                        date: $('#seldate').val(),
                        type: $('#funcroomtype').val(),
                        timeblock: $('#timeblock').val(),
                    },
                    success: function(meetrooms) {
                        if ($('#timeblock').val() != '' || $('#timeblock').val() != null) {
                            var seltb = $('#timeblock').val();

                            $('#meetroomlist li').each(function() {
                                var meetroom = $(this);
                                var meetroomtb = $(this).data('tbcode');

                                if (meetroomtb != seltb) {
                                    meetroom.hide();
                                } else {
                                    if (meetroom.parent().get(0).tagName == 'STRIKE') {
                                        meetroom.unwrap();
                                    }

                                    $.each(meetrooms, function(index, mroom){
                                        if (meetroom.data('id').includes(mroom.venuecode)) {
                                            meetroom.wrap('<strike>');
                                            return false;
                                        }
                                    });

                                    meetroom.show();
                                }
                            });

                            $('#meetingroom').show();
                        } else {
                            $('#meetingroom').hide();
                        }
                    }
                });
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
