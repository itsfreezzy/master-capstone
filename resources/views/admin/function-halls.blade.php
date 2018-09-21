@extends('layouts.adminlayout')

@section('styles')
@endsection

@section('title')
Bayanihan Center | Function Halls
@endsection

@section('content-header')
    <h1>
        Function Halls
        <small>List and details of current function halls</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Function Halls</li>
    </ol>
@endsection

@section('content')
{{--  Function Halls Table  --}}
@include('inc.messages')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {{--  box header  --}}
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Function Hall List</h3>
                <div class="pull-right" style="padding:0px">
                    <button class="btn btn-block btn-success" data-toggle="modal" data-target="#modalCreate"> <i class="glyphicon glyphicon-plus-sign"></i> Add Function Hall </button>
                </div>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                @if (count($functionhalls) > 0)
                <table id="tblFunctionHalls" class="table table-bordered table-hover">
                    <thead>
                        <th>FH Code</th>
                        <th>Function Hall</th>
                        <th>Floor Area</th>
                        <th>Capacity</th>
                        <th>Status</th>
                        <th style="width: 14%">Actions</th>
                    </thead>
                    <tbody>
                        @foreach($functionhalls as $functionhall)
                        <tr>
                            <td>{{ $functionhall->code }}</td>
                            <td>{{ $functionhall->name }}</td>
                            <td>{{ $functionhall->floorarea }} Sq. M.</td>
                            <td>{{ $functionhall->mincapacity }} - {{ $functionhall->maxcapacity }} pax</td>
                            <td>
                            @if ($functionhall->trashed())
                                <span class="label label-danger">Deactivated</span>
                            @else
                                <span class="label label-success">Activated</span>
                            @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-default" type="button" title="View Function Hall Info" id="btnmodalread" data-id="{{$functionhall->id}}"> <i class="fa fa-eye"></i></button>
                                    @if ($functionhall->trashed())
                                    <button class="btn btn-warning" type="button" title="Activate Function Hall" id="btnmodalrestore" data-id="{{$functionhall->id}}"> <i class="fa fa-undo"></i></button>
                                    @else
                                    <button class="btn btn-primary" type="button" title="Edit Function Hall Info" id="btnmodalupdate" data-id="{{$functionhall->id}}"> <i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger" type="button" title="Deactivate Function Hall" id="btnmodaldelete" data-id="{{$functionhall->id}}"> <i class="fa fa-close"></i></button>
                                @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>No Function Halls found</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if (count($functionhalls) > 0)
{{-- Create Modal --}}
<div class="modal fade" id="modalCreate" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Add</h4>
            </div>

            <form action="{{ action('FunctionHallController@store') }}" class="form-horizontal" method="post">
                <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Function Hall:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="fhname" placeholder="Insert function hall..." value="{{ old('fhname') }}" autocomplete="off" required>
                            </div>

                            @if ($errors->has('fhname'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('fhname') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Floor Area:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="addfloorarea" value="{{ old('addfloorarea') }}" placeholder="Insert floor area..." autocomplete="off" >
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
                                <input type="number" step="1" class="form-control" name="addmincap" value="{{ old('addmincap') }}" placeholder="Insert maximum capacity..." autocomplete="off" min="1" max="999" required>
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
                                <input type="number" step="1" class="form-control" name="addmaxcap" value="{{ old('addmaxcap') }}" placeholder="Insert minimum capacity..." autocomplete="off" min="1" max="999" required>
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
                            <label for="" class="col-sm-4 control-label">Whole Day Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" step="0.01" class="form-control" name="addwholeday" value="{{ old('addwholeday') }}" placeholder="Insert hall whole day rate..." autocomplete="off" min="0.01" max="999999.99" required>
                            </div>

                            @if ($errors->has('addwholeday'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addwholeday') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Half Day Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" step="0.01" class="form-control" name="addhalfday" value="{{ old('addhalfday') }}" placeholder="Insert hall half day rate..." autocomplete="off" min="0.01" max="99999.99" required>
                            </div>

                            @if ($errors->has('addhalfday'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addhalfday') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Ingress/Eggress Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" step="0.01" class="form-control" name="addinegrate" value="{{ old('addinegrate') }}" placeholder="Insert hall ingress/eggress rate..." autocomplete="off" min="0.01" max="99999.99" required>
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
                            <label for="" class="col-sm-4 control-label">Hourly Excess Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" step="0.01" class="form-control" name="addhourlyexcess" value="{{ old('addhourlyexcess') }}" placeholder="Insert hall hourly excess rate..." autocomplete="off" min="0.01" max="99999.99" required>
                            </div>

                            @if ($errors->has('addhourlyexcess'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addhourlyexcess') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
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
                <h4 class="modal-title"><i class="fa fa-eye"></i> Function Hall Information</h4>
            </div>

            <form action="" class="form-horizontal" method="post">
                <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Function Hall:</label>
                            <div class="col-sm-7">
                                <input id="vfhname" type="text" class="form-control" name="funcHallName" value="" placeholder="Insert function hall..." autocomplete="off" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Floor Area:</label>
                            <div class="col-sm-7">
                                <input id="vfloorarea" type="number" step="0.01" class="form-control" name="floorArea" value="" placeholder="Insert floor area..." autocomplete="off" min="0.01" max="99999.99" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Minimum Capacity:</label>
                            <div class="col-sm-7">
                                <input id="vmincap" type="number" step="1" class="form-control" name="minCap" value="" placeholder="Insert minimum capacity..." autocomplete="off" min="1" max="99999" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Maximum Capacity:</label>
                            <div class="col-sm-7">
                                <input id="vmaxcap" type="number" step="1" class="form-control" name="maxCap" value="" placeholder="Insert maximum capacity..." autocomplete="off" min="1" max="99999" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Whole Day Rate:</label>
                            <div class="col-sm-7">
                                <input id="vwholeday" type="number" step="0.01" class="form-control" name="fhWholeDay" value="" placeholder="Insert hall whole day rate..." autocomplete="off" min="0.01" max="99999.99" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Half Day Rate:</label>
                            <div class="col-sm-7">
                                <input id="vhalfday" type="number" step="0.01" class="form-control" name="fhHalfDay" value="" placeholder="Insert hall half day rate..." autocomplete="off" min="0.01" max="99999.99" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Ingress/Eggress Rate:</label>
                            <div class="col-sm-7">
                                <input id="vinegrate" type="number" step="0.01" class="form-control" name="fhInEgRate" value="" placeholder="Insert hall ingress/eggress rate..." autocomplete="off" min="0.01" max="99999.99" readonly>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Hourly Excess Rate:</label>
                            <div class="col-sm-7">
                                <input id="vhourlyexcess" type="number" step="0.01" class="form-control" name="fhHourlyExcess" value="" placeholder="Insert hall hourly excess rate..." autocomplete="off" min="0.01" max="99999.99" readonly>
                            </div>
                        </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-dismiss="modal"> <i class="glyphicon glyphicon-ok-sign"></i> OK</button>
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
                <h4 class="modal-title"><i class="fa fa-edit"></i> Update Function Hall</h4>
            </div>

            <form action="" class="form-horizontal" method="post" id="form-update">
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Function Hall:</label>
                            <div class="col-sm-7">
                                <input type="text" id="efhname" class="form-control" name="editfhname" value="" placeholder="Insert function hall..." autocomplete="off" >
                            </div>

                            @if ($errors->has('editfhname'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editfhname') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Floor Area:</label>
                            <div class="col-sm-7">
                                <input type="text" id="efloorarea" class="form-control" name="editfloorarea" value="" placeholder="Insert floor area..." autocomplete="off" step="0.01" min="0.01" max="99999.99">
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
                                <input type="number" id="emincap" step="1" class="form-control" name="editmincap" value="" placeholder="Insert minimum capacity..." autocomplete="off" min="1" max="99999">
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
                                <input type="number" id="emaxcap" step="1" class="form-control" name="editmaxcap" value="" placeholder="Insert maximum capacity..." autocomplete="off" min="1" max="99999">
                            </div>

                            @if ($errors->has('editmaxcap'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editmaxcap') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Whole Day Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" id="ewholeday" step="0.01" class="form-control" name="editwholeday" value="" placeholder="Insert hall whole day rate..." autocomplete="off" min="0.01" max="999999.99" >
                            </div>

                            @if ($errors->has('editwholeday'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editwholeday') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Half Day Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" id="ehalfday" step="0.01" class="form-control" name="edithalfday" value="" placeholder="Insert hall half day rate..." autocomplete="off" min="0.01" max="999999.99" >
                            </div>

                            @if ($errors->has('edithalfday'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('edithalfday') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Ingress/Eggress Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" id="einegrate" step="0.01" class="form-control" name="editinegrate" value="" placeholder="Insert hall ingress/eggress rate..." autocomplete="off" min="0.01" max="999999.99" >
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
                            <label for="" class="col-sm-4 control-label">Hourly Excess Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" id="ehourlyexcess" step="0.01" class="form-control" name="edithourlyexcess" value="" placeholder="Insert hall hourly excess rate..." autocomplete="off" min="0.01" max="99999.99" >
                            </div>

                            @if ($errors->has('edithourlyexcess'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('edithourlyexcess') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
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
                <h4 class="modal-title"><i class="fa fa-trash"></i> Remove Function Hall</h4>
            </div>

            <div class="modal-body">
                <h5>Removing this function hall will only archive the function hall, not actually delete it.</h5>
                <h5>Confirm removal of function hall?</h5>
            </div>
            
            <div class="modal-footer">
                <form action="" id="form-delete" method="POST" class="pull pull-right">
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
                <h4 class="modal-title"><i class="fa fa-undo"></i> Restore</h4>
            </div>

            <div class="modal-body">
                <h5>Confirm restoration of function hall?</h5>
            </div>
            
            <div class="modal-footer">
                <form id="form-restore" action="" method="POST" class="pull pull-right">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Confirm Restore</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@section('scripts')
<script>
$(function() {
    $('#tblFunctionHalls').DataTable({
        "pageLength": 25,
        "order": []
    });

    $(document).on('click', '#btnmodalread', function() {
        id = $(this).data('id');
        $.ajax({
            url: '/admin/maintenance/function-halls/get',
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id,
            },
            success: function(functionhall) {
                $('#vfhname').val(functionhall.name);
                $('#vfloorarea').val(functionhall.floorarea);
                $('#vmincap').val(functionhall.mincapacity);
                $('#vmaxcap').val(functionhall.maxcapacity);
                $('#vwholeday').val(functionhall.wholedayrate);
                $('#vhalfday').val(functionhall.halfdayrate);
                $('#vinegrate').val(functionhall.ineghourlyrate);
                $('#vhourlyexcess').val(functionhall.hourlyexcessrate);
                $('#modalRead').modal('show');
            }
        });
    });

    $(document).on('click', '#btnmodalupdate', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.function-halls.edit', ['id' => 'idhere']) }}";

        $.ajax({
            url: '/admin/maintenance/function-halls/get',
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id,
            },
            success: function(functionhall) {
                $('#efhname').val(functionhall.name);
                $('#efloorarea').val(functionhall.floorarea);
                $('#emincap').val(functionhall.mincapacity);
                $('#emaxcap').val(functionhall.maxcapacity);
                $('#ewholeday').val(functionhall.wholedayrate);
                $('#ehalfday').val(functionhall.halfdayrate);
                $('#einegrate').val(functionhall.ineghourlyrate);
                $('#ehourlyexcess').val(functionhall.hourlyexcessrate);
                
                $('#form-update').attr('action', route.replace('idhere', functionhall.id));

                $('#modalUpdate').modal('show');
            }
        });
    });

    $(document).on('click', '#btnmodaldelete', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.function-halls.destroy', ['id' => 'idhere']) }}";
        $('#form-delete').attr('action', route.replace('idhere', id));

        $('#modalDelete').modal('show');
    });

    $(document).on('click', '#btnmodalrestore', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.function-halls.restore', ['id' => 'idhere']) }}";
        $('#form-restore').attr('action', route.replace('idhere', id));

        $('#modalRestore').modal('show');
    });

    $('#modalCreate').on('hidden.bs.modal', function(e) {
        $('#modalUpdate .error').remove();
        $("#modalCreate .modal-body input").val("");
    });

    $('#modalUpdate').on('hidden.bs.modal', function(e) {
        $('#modalUpdate .error').remove();
    });

    @if (session('showAddModal'))
        $('#modalCreate').modal('show');
    @endif

    @if (session('showEditModal'))
        var id = {{ session('id') }};
        var route = "{{ route('admin.function-halls.edit', ['id' => 'idhere']) }}";

        $.ajax({
            url: '/admin/maintenance/function-halls/get',
            type: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id,
            },
            success: function(functionhall) {
                $('#efhname').val(functionhall.name);
                $('#efloorarea').val(functionhall.floorarea);
                $('#emincap').val(functionhall.mincapacity);
                $('#emaxcap').val(functionhall.maxcapacity);
                $('#ewholeday').val(functionhall.wholedayrate);
                $('#ehalfday').val(functionhall.halfdayrate);
                $('#einegrate').val(functionhall.ineghourlyrate);
                $('#ehourlyexcess').val(functionhall.hourlyexcessrate);
                
                $('#form-update').attr('action', route.replace('idhere', functionhall.id));

                $('#modalUpdate').modal('show');
            }
        });
    @endif
});
</script>
@endsection