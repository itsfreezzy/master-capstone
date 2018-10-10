@extends('layouts.adminlayout')

@section('styles')
<!-- Select2 -->
<link rel="stylesheet" href="{{ asset('adminlte/bower_components/select2/dist/css/select2.min.css') }}" type="text/css">
@endsection

@section('title')
Bayanihan Center | Meeting Rooms
@endsection

@section('content-header')
    <h1>
        Meeting Room Combos
        <small>List and details of current meeting room combos</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Meeting Rooms</li>
    </ol>
@endsection

@section('content')
@include('inc.messages')
{{--  Meeting Rooms Table  --}}
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {{--  box header  --}}
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Meeting Room Combo List</h3>
                <div class="pull-right" style="padding:0px">
                    <button class="btn btn-block btn-success" data-toggle="modal" data-target="#modalCreate"> <i class="glyphicon glyphicon-plus-sign"></i> Add Meeting Room Combo</button>
                </div>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                @if (count($meetingroomcombos) > 0)
                <table id="tblMeetingRooms" class="table table-bordered table-hover">
                    <thead>
                            <th>MR Code</th>
                            <th>Meeting Room</th>
                            <th>Floor Area</th>
                            <th>Capacity</th>
                            <th>Status</th>
                            <th style="width: 14%">Actions</th>
                    </thead>
                    <tbody>
                        @foreach($meetingroomcombos as $meetingroomcombo)
                        <tr>
                            <td>{{ $meetingroomcombo->code }}</td>
                            <td>{{ $meetingroomcombo->name }}</td>
                            <td>{{ $meetingroomcombo->floorarea }} Sq. M.</td>
                            <td>{{ $meetingroomcombo->mincapacity }} - {{ $meetingroomcombo->maxcapacity }} pax</td>
                            <td>
                            @if ($meetingroomcombo->trashed())
                                <span class="label label-danger">Deactivated</span>
                            @else
                                <span class="label label-success">Activated</span>
                            @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                <button class="btn btn-default" title="View Meeting Room Info" id="btnmodalread" data-id="{{$meetingroomcombo->id}}"> <i class="fa fa-eye"></i></button>
                                @if ($meetingroomcombo->trashed())
                                <button class="btn btn-warning" title="Restore Meeting Room" id="btnmodalrestore" data-id="{{$meetingroomcombo->id}}"> <i class="fa fa-undo"></i></button>
                                @else
                                <button class="btn btn-primary" title="Edit Meeting Room Info" id="btnmodalupdate" data-id="{{$meetingroomcombo->id}}"> <i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger" title="Delete Meeting Room" id="btnmodaldelete" data-id="{{$meetingroomcombo->id}}"> <i class="fa fa-close"></i></button>
                                @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>No Meeting Room found</p>
                @endif
            </div>
        </div>
    </div>
</div>



{{-- Create Modal --}}
<div class="modal fade" id="modalCreate" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Add</h4>
            </div>

            <form action="{{ action('MeetingRoomController@comboStore') }}" class="form-horizontal" method="post">
                <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Meet. Room Combo Name:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="comboname" placeholder="Insert meeting room name..." value="{{ old('comboname') }}" autocomplete="off" required>
                            </div>

                            @if ($errors->has('comboname'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('comboname') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Meeting Rooms:</label>
                            <div class="col-sm-7">
                                {{-- <input type="text" class="form-control" name="mrname" placeholder="Insert meeting room name..." value="{{ old('mrname') }}" autocomplete="off" required> --}}
                                <select name="mrname[]" id="mrname" class="form-control form-horizontal" style="width: 100%" multiple>
                                    @foreach($meetingrooms as $mr)
                                    <option value="{{ $mr->code }}">{{ $mr->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($errors->has('mrname'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('mrname') }}</strong>
                                    </span>
                                </div>
                            @endif

                            @if ($errors->has('mrvalue'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('mrvalue') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Floor Area:</label>
                            <div class="col-sm-7">
                                <input type="number" step="0.01" class="form-control" id="addfloorarea" name="addfloorarea" value="{{ old('addfloorarea') }}" placeholder="Insert meeting room floor area..." autocomplete="off" min="0.01" max="99999.99" required>
                            </div>

                            @if ($errors->has('addfloorarea'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addfloorarea') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Minimum Capacity:</label>
                            <div class="col-sm-7">
                                <input type="number" step="1" class="form-control" id="addmincap" name="addmincap" value="{{ old('addmincap') }}" placeholder="Insert meeting room minimum capacity..." autocomplete="off" min="1" max="99999" required>
                            </div>

                            @if ($errors->has('addmincap'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addmincap') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Maximum Capacity:</label>
                            <div class="col-sm-7">
                                <input type="number" step="1" class="form-control" id="addmaxcap" name="addmaxcap" value="{{ old('addmaxcap') }}" placeholder="Insert meeting room maximum capacity..." autocomplete="off" min="1" max="99999" required>
                            </div>

                            @if ($errors->has('addmaxcap'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addmaxcap') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Rate Per Block:</label>
                            <div class="col-sm-7">
                                <input type="number" step="0.01" class="form-control" id="addrateperblock" name="addrateperblock" value="{{ old('addrateperblock') }}" placeholder="Insert meeting room rate per block..." autocomplete="off" min="0.01" max="99999.99" required>
                            </div>

                            @if ($errors->has('addrateperblock'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addrateperblock') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Ingress/Eggress Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" step="0.01" class="form-control" id="addinegrate" name="addinegrate" value="{{ old('addinegrate') }}" placeholder="Insert meeting room ingress/eggress rate..." autocomplete="off" min="0.01" max="99999.99" required>
                            </div>

                            @if ($errors->has('addinegrate'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addinegrate') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Time Block:</label>
                            <div class="col-sm-7">
                                <select class="form-control" name="" id="addtimeblock" multiple style="width: 100%">
                                    @foreach($timeblocks as $timeblock)
                                    <option value="{{ $timeblock->code }}">{{ $timeblock->code }} | {{ date('h:i:s A', strtotime($timeblock->timestart)) }} - {{ date('h:i:s A', strtotime($timeblock->timeend)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($errors->has('addtimeblock'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addtimeblock') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <input type="hidden" name="mrvalue" id="mrvalue" value="{{ old('mrvalue') }}">
                        <input type="hidden" val="{{ old('addtimeblock') }}" name="addtimeblock" id="mrtimeblocks">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success" id="" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Read Modal --}}
<div class="modal fade" id="modalRead" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-eye"></i> View</h4>
            </div>

            <form action="" class="form-horizontal" method="post">
                <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Meeting Room:</label>
                            <div class="col-sm-7">
                                <input type="text" id="vmrname" class="form-control" name="meetingRmName" placeholder="Insert meeting room name..." autocomplete="off" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Floor Area:</label>
                            <div class="col-sm-7">
                                <input type="number" id="vfloorarea" step="0.01" class="form-control" name="floorArea" placeholder="Insert meeting room floor area..." autocomplete="off" min="0.01" max="99999.99" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Minimum Capacity:</label>
                            <div class="col-sm-7">
                                <input type="number" id="vmincap" step="1" class="form-control" name="minCap" placeholder="Insert meeting room minimum capacity..." autocomplete="off" min="1" max="99999" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Maximum Capacity:</label>
                            <div class="col-sm-7">
                                <input type="number" id="vmaxcap" step="1" class="form-control" name="maxCap" placeholder="Insert meeting room maximum capacity..." autocomplete="off" min="1" max="99999" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Rate per Block:</label>
                            <div class="col-sm-7">
                                <input type="number" id="vrateperblock" step="0.01" class="form-control" name="RatePerBlock" placeholder="Insert hall whole day rate..." autocomplete="off" min="0.01" max="99999.99" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Ingress/Eggress Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" id="vinegrate" step="0.01" class="form-control" name="mrInEgRate" placeholder="Insert hall ingress/eggress rate..." autocomplete="off" min="0.01" max="99999.99" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Time Block:</label>
                            <div class="col-sm-7">
                                <select class="form-control" id="vtimeblock" disabled  multiple style="width: 100%">
                                    @foreach($timeblocks as $timeblock)
                                    <option value="{{ $timeblock->code }}">{{ $timeblock->code }} | {{ date('h:i:s A', strtotime($timeblock->timestart)) }} - {{ date('h:i:s A', strtotime($timeblock->timeend)) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"> <i class="fa fa-check"></i> OK</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Update Modal --}}
<div class="modal fade" id="modalUpdate" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit</h4>
            </div>

            <form action="" id="form-update" class="form-horizontal" method="post">
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Meet. Room Combo Name:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="editcomboname" name="editcomboname" placeholder="Insert meeting room name..." value="{{ old('editcomboname') }}" autocomplete="off" required>
                            </div>

                            @if ($errors->has('editcomboname'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editcomboname') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Meeting Rooms:</label>
                            <div class="col-sm-7">
                                {{-- <input type="text" class="form-control" name="mrname" placeholder="Insert meeting room name..." value="{{ old('mrname') }}" autocomplete="off" required> --}}
                                <select name="editmrname[]" id="editmrname" class="form-control form-horizontal" style="width: 100%" multiple>
                                    @foreach($meetingrooms as $mr)
                                    <option value="{{ $mr->code }}">{{ $mr->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($errors->has('editmrname'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editmrname') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Floor Area:</label>
                            <div class="col-sm-7">
                                <input type="number" step="0.01" id="efloorarea" class="form-control" name="editfloorarea" placeholder="Insert meeting room floor area..." autocomplete="off" min="0.01" max="9999.99" >
                            </div>

                            @if ($errors->has('editfloorarea'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editfloorarea') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Minimum Capacity:</label>
                            <div class="col-sm-7">
                                <input type="number" step="1" id="emincap" class="form-control" name="editmincap" placeholder="Insert meeting room minimum capacity..." autocomplete="off" min="1" max="99999" >
                            </div>

                            @if ($errors->has('editmincap'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editmincap') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Maximum Capacity:</label>
                            <div class="col-sm-7">
                                <input type="number" step="1" id="emaxcap" class="form-control"  name="editmaxcap"  placeholder="Insert meeting room maximum capacity..." autocomplete="off" min="1" max="99999" >
                            </div>

                            @if ($errors->has('editmaxCap'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editmaxCap') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Rate Per Block:</label>
                            <div class="col-sm-7">
                                <input type="number" id="erateperblock" step="0.01" class="form-control" name="editrateperblock" placeholder="Insert rate per block..." autocomplete="off" min="0.01" max="999999.99" >
                            </div>

                            @if ($errors->has('editrateperblock'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editrateperblock') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Ingress/Eggress Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" id="einegrate" step="0.01" class="form-control"name="editinegrate" placeholder="Insert hall ingress/eggress rate..." autocomplete="off" min="0.01" max="999999.99" >
                            </div>

                            @if ($errors->has('editinegrate'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editinegrate') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Time Block:</label>
                            <div class="col-sm-7">
                                <select class="form-control" id="etimeblock"  multiple style="width: 100%">
                                    @foreach($timeblocks as $timeblock)
                                    <option {{$meetingroomcombo->timeblockcode == $timeblock->code ? 'selected' : ''}} value="{{ $timeblock->code }}">{{ $timeblock->code }} | {{ date('h:i:s A', strtotime($timeblock->timestart)) }} - {{ date('h:i:s A', strtotime($timeblock->timeend)) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            @if ($errors->has('edittimeblock'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('edittimeblock') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <input type="hidden" id="editmrvalue" name="editmrvalue">
                        <input type="hidden" name="edittimeblock" id="emrtimeblock" value="{{ old('edittimeblock') }}">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success" id="" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="modalDelete" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-trash"></i> Delete</h4>
            </div>

            <div class="modal-body">
                <h5>Confirm deletion of function hall?</h5>
            </div>
            
            <div class="modal-footer">
                <form id="form-delete" method="POST" class="pull pull-right">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Confirm Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Restore Modal --}}
<div class="modal fade" id="modalRestore" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-trash"></i> Delete</h4>
            </div>

            <div class="modal-body">
                <h5>Confirm restoration of meeting room?</h5>
            </div>
            
            <div class="modal-footer">
                <form id="form-restore" method="POST" class="pull pull-right">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Confirm Restore</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- Select2 -->
<script src="{{ asset('adminlte/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

<script>
$(function() {
    var meetingrooms = @json($meetingrooms);
    $('#mrname').select2();
    $('#editmrname').select2();
    $('#addtimeblock').select2();
    $('#etimeblock').select2();
    $('#vtimeblock').select2();

    $('#tblMeetingRooms').DataTable({
        "pageLength": 25,
        "order": [],
    });

    $('#addtimeblock').on('change', function() {
        var timeblocks = $(this).val().join('|');
        $('#mrtimeblocks').val(timeblocks);
    });
    
    $('#etimeblock').on('change', function() {
        var timeblocks = $(this).val().join('|');
        $('#emrtimeblock').val(timeblocks);
    });

    $('#mrname').on('change', function() {
        var floorarea = 0;
        var mincap = 0;
        var maxcap = 0;
        var rateperblock = 0;
        var inegrate = 0;

        $('#mrname option:selected').each(function() {
            var sel = $(this).val();
            
            $.each(meetingrooms, function(key, val) {
                if (sel == val.code) {
                    floorarea += parseFloat(val.floorarea);
                    mincap += parseFloat(val.mincapacity);
                    maxcap += parseFloat(val.maxcapacity);
                    rateperblock += parseFloat(val.rateperblock);
                    inegrate += parseFloat(val.ineghourlyrate);
                }
            });
        });

        $('#addfloorarea').val(floorarea.toFixed(2));
        $('#addmincap').val(mincap.toFixed(2));
        $('#addmaxcap').val(maxcap.toFixed(2));
        $('#addrateperblock').val(rateperblock.toFixed(2));
        $('#addinegrate').val(inegrate.toFixed(2));

        $('#mrvalue').val( ($('#mrname').val()).join('|') );
    });

    $(document).on('click', '#btnmodalread', function() {
        var id = $(this).data('id');

        $.ajax({
            url: '/admin/maintenance/meeting-rooms/combo/get',
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id,
            },
            success: function(meetingroom) {
                $('#vmrname').val(meetingroom.name);
                $('#vfloorarea').val(meetingroom.floorarea);
                $('#vmincap').val(meetingroom.mincapacity);
                $('#vmaxcap').val(meetingroom.maxcapacity);
                $('#vrateperblock').val(meetingroom.rateperblock);
                $('#vinegrate').val(meetingroom.ineghourlyrate);
                $('#vtimeblock option[value="'+meetingroom.timeblockcode+'"]').prop('selected', 'true');

                $('#modalRead').modal('show');
            }
        });
    });

    $('#editmrname').on('change', function() {
        var floorarea = 0;
        var mincap = 0;
        var maxcap = 0;
        var rateperblock = 0;
        var inegrate = 0;

        $('#editmrname option:selected').each(function() {
            var sel = $(this).val();
            
            $.each(meetingrooms, function(key, val) {
                if (sel == val.code) {
                    floorarea += parseFloat(val.floorarea);
                    mincap += parseFloat(val.mincapacity);
                    maxcap += parseFloat(val.maxcapacity);
                    rateperblock += parseFloat(val.rateperblock);
                    inegrate += parseFloat(val.ineghourlyrate);
                }
            });
        });

        $('#efloorarea').val(floorarea.toFixed(2));
        $('#emincap').val(mincap.toFixed(2));
        $('#emaxcap').val(maxcap.toFixed(2));
        $('#erateperblock').val(rateperblock.toFixed(2));
        $('#einegrate').val(inegrate.toFixed(2));

        $('#editmrvalue').val( ($('#editmrname').val()).join('|') );
    });

    $(document).on('click', '#btnmodalupdate', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.meeting-rooms.edit', ['id' => 'idhere']) }}";

        $.ajax({
            url: '/admin/maintenance/meeting-rooms/combo/get',
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id,
            },
            success: function(meetingroom) {
                $('#editcomboname').val(meetingroom.name);
                $('#editmrname').val( meetingroom.code.split('|') );
                $('#editmrname').select2();
                $('#efloorarea').val(meetingroom.floorarea);
                $('#emincap').val(meetingroom.mincapacity);
                $('#emaxcap').val(meetingroom.maxcapacity);
                $('#erateperblock').val(meetingroom.rateperblock);
                $('#einegrate').val(meetingroom.ineghourlyrate);
                $('#etimeblock option[value="'+meetingroom.timeblockcode+'"]').prop('selected', 'true');
                $('#editmrvalue').val(meetingroom.code);
                
                $('#form-update').attr('action', route.replace('idhere', meetingroom.id));

                $('#modalUpdate').modal('show');
            }
        });
    });

    $(document).on('click', '#btnmodaldelete', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.mrooms-combo.destroy', ['id' => 'idhere']) }}";
        $('#form-delete').attr('action', route.replace('idhere', id));

        $('#modalDelete').modal('show');
    });

    $(document).on('click', '#btnmodalrestore', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.mrooms-combo.restore', ['id' => 'idhere']) }}";
        $('#form-restore').attr('action', route.replace('idhere', id));

        $('#modalRestore').modal('show');
    });

    $('#modalCreate').on('hidden.bs.modal', function(e) {
        $('#modalCreate .error').remove();
        $("#modalCreate .modal-body input").val("");
        $('#mrname option:selected').each(function() {
            $(this).prop('selected', false);
        });
        $('#mrname').select2();
    });
    
    $('#modalUpdate').on('hidden.bs.modal', function(e) {
        $('#modalUpdate .error').remove();
    });

    @if(session('showAddModal'))
        $('#modalCreate').modal('show');
    @endif

    @if (session('showEditModal'))
        var id = {{ session('id') }};
        var route = "{{ route('admin.meeting-rooms.edit', ['id' => 'idhere']) }}";

        $.ajax({
            url: '/admin/maintenance/meeting-rooms/get',
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id,
            },
            success: function(meetingroom) {
                $('#emrname').val(meetingroom.name);
                $('#efloorarea').val(meetingroom.floorarea);
                $('#emincap').val(meetingroom.mincapacity);
                $('#emaxcap').val(meetingroom.maxcapacity);
                $('#erateperblock').val(meetingroom.rateperblock);
                $('#einegrate').val(meetingroom.ineghourlyrate);
                $('#etimeblock option[value="'+meetingroom.timeblockcode+'"]').prop('selected', 'true');
                
                $('#form-update').attr('action', route.replace('idhere', meetingroom.id));

                $('#modalUpdate').modal('show');
            }
        });
    @endif
});
</script>
@endsection