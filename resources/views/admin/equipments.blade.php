@extends('layouts.adminlayout')

@section('styles')
@endsection

@section('title')
Bayanihan Center | Equipments
@endsection

@section('content-header')
    <h1>
        Equipments
        <small>List of equipments for rent</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Equipments</li>
    </ol>
@endsection

@section('content')
{{--  Event Types Table  --}}
@include('inc.messages')
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {{--  box header  --}}
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Equipment List</h3>
                <div class="pull-right" style="padding:0px">
                    <button class="btn btn-block btn-success" data-toggle="modal" data-target="#modalCreate"> <i class="glyphicon glyphicon-plus-sign"></i> Add Equipment </button>
                </div>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                @if(count($equipments) > 0)
                <table id="tblSetup" class="table table-bordered table-hover">
                    <thead>
                        <th class="col-sm-1">Equipment Code</th>
                        <th class="col-sm-2">Equipment</th>
                        <th class="col-sm-3">Description</th>
                        <th class="col-sm-1">Whole Day Rate</th>
                        <th class="col-sm-1">Half Day Rate</th>
                        <th class="col-sm-1">Hourly Excess Rate</th>
                        <th class="col-sm-1">Status</th>
                        <th style="width: 8%">Actions</th>
                    </thead>
                    <tbody>
                        @foreach($equipments as $equipment)
                        <tr>
                            <td>{{ $equipment->code }}</td>
                            <td>{{ $equipment->name }}</td>
                            <td>{{ $equipment->description }}</td>
                            <td>{{ number_format($equipment->wholedayrate, 2) }}</td>
                            <td>{{ number_format($equipment->halfdayrate, 2) }}</td>
                            <td>{{ number_format($equipment->hourlyexcessrate, 2) }}</td>
                            <td>
                                @if ($equipment->trashed())
                                    <span class="label label-danger">Deactivated</span>
                                @else
                                    <span class="label label-success">Activated</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                @if ($equipment->trashed())
                                <button class="btn btn-warning" type="button" title="Restore Equipment" id="btnmodalrestore" data-id="{{$equipment->id}}"> <i class="fa fa-undo"></i></button>
                                @else
                                <button class="btn btn-primary" type="button" title="Update Equipment" id="btnmodalupdate" data-id="{{$equipment->id}}"> <i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger" type="button" title="Delete Equipment" id="btnmodaldelete" data-id="{{$equipment->id}}"> <i class="fa fa-close"></i></button>
                                @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>No Equipments founds</p>
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
            
            <form action="{{ action('EquipmentController@store') }}" class="form-horizontal" method="post">
                <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Equipment Name:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="addEquipName" placeholder="Insert equipment name..." value="{{ old('addEquipName') }}" autocomplete="off" required>
                            </div>

                            @if ($errors->has('addEquipName'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addEquipName') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Description:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="addEquipDesc" placeholder="Insert equipment description..." value="{{ old('addEquipDesc') }}" autocomplete="off" >
                            </div>

                            @if ($errors->has('addEquipDesc'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addEquipDesc') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Whole Day Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" step="0.01" class="form-control" name="addEquipWholeDay" value="{{ old('addEquipWholeDay') }}" placeholder="Insert equipment whole day rate..." autocomplete="off" min="0.01" max="99999.99" required>
                            </div>

                            @if ($errors->has('addEquipWholeDay'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addEquipWholeDay') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Half Day Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" step="0.01" class="form-control" name="addEquipHalfDay" value="{{ old('addEquipHalfDay') }}" placeholder="Insert equipment half day rate..." autocomplete="off" min="0.01" max="99999.99" required>
                            </div>

                            @if ($errors->has('addEquipHalfDay'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addEquipHalfDay') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Hourly Excess Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" step="0.01" class="form-control" name="addEquipHourlyExcess" value="{{ old('addEquipHourlyExcess') }}" placeholder="Insert equipment hourly excess rate..." autocomplete="off" min="0.01" max="99999.99" required>
                            </div>

                            @if ($errors->has('addEquipHourlyExcess'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addEquipHourlyExcess') }}</strong>
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

{{-- Update Modal --}}
<div class="modal fade" id="modalUpdate" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit</h4>
            </div>

            <form id="form-update" class="form-horizontal" method="post">
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Equipment Name:</label>
                            <div class="col-sm-7">
                                <input type="text" id="equipment" class="form-control" name="editequipName" placeholder="Insert equipment name..." autocomplete="off" required>
                            </div>

                            @if ($errors->has('editequipName'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editequipName') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Description:</label>
                            <div class="col-sm-7">
                                <input type="text" id="description" class="form-control" name="editequipDesc" placeholder="Insert equipment description..." autocomplete="off" >
                            </div>

                            @if ($errors->has('editequipDesc'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editequipDesc') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Whole Day Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" id="wholedayrate" step="0.01" class="form-control" name="editequipWholeDay" placeholder="Insert equipment whole day rate..." autocomplete="off" min="0.01" max="99999.99" required>
                            </div>

                            @if ($errors->has('editequipWholeDay'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editequipWholeDay') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Half Day Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" id="halfdayrate" step="0.01" class="form-control" name="editequipHalfDay" placeholder="Insert equipment half day rate..." autocomplete="off" min="0.01" max="99999.99" required>
                            </div>

                            @if ($errors->has('editequipHalfDay'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editequipHalfDay') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Hourly Excess Rate:</label>
                            <div class="col-sm-7">
                                <input type="number" id="hourlyexcessrate" step="0.01" class="form-control" name="editequipHourlyExcess" placeholder="Insert equipment hourly excess rate..." autocomplete="off" min="0.01" max="99999.99" required>
                            </div>

                            @if ($errors->has('editequipHourlyExcess'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editequipHourlyExcess') }}</strong>
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
                <h4 class="modal-title"><i class="fa fa-trash"></i> Delete</h4>
            </div>

            <div class="modal-body">
                <h5>Confirm deletion of equipment?</h5>
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

            </div>
            <div class="modal-body">

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
<script>
$(function() {
    $('#tblSetup').DataTable({
        "pageLength": 25,
        "order": [],
    });
    
    $(document).on('click', '#btnmodalupdate', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.equipments.edit', ['id' => 'idhere']) }}";

        $.ajax({
           url: '/admin/maintenance/equipments/get',
           method: 'POST',
           data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id,
           },
           success: function(equipment) {
               $('#equipment').val(equipment.name);
               $('#description').val(equipment.description);
               $('#wholedayrate').val(equipment.wholedayrate);
               $('#halfdayrate').val(equipment.halfdayrate);
               $('#hourlyexcessrate').val(equipment.hourlyexcessrate);

               $('#form-update').attr('action', route.replace('idhere', equipment.id));

               $('#modalUpdate').modal('show');
           }
        });
    });

    $(document).on('click', '#btnmodaldelete', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.equipments.destroy', ['id' => 'idhere']) }}";

        $('#form-delete').attr('action', route.replace('idhere', id));

        $('#modalDelete').modal('show');
    });

    $(document).on('click', '#btnmodalrestore', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.equipments.restore', ['id' => 'idhere']) }}";

        $('#form-restore').attr('action', route.replace('idhere', id));

        $('#modalRestore').modal('show');
    });
    

    $('#modalCreate').on('hidden.bs.modal', function(e){
        $("#modalCreate .error").remove();
        $("#modalCreate .modal-body input").val("");
    });

    $('#modalUpdate').on('hidden.bs.modal', function(e){
        $("#modalUpdate .error").remove();
    });

    @if (session('showAddModal'))
        $('#modalCreate').modal('show');
    @endif

    @if (session('showEditModal'))
        var id = {{session('id')}};
        var route = "{{ route('admin.equipments.edit', ['id' => 'idhere']) }}";

        $.ajax({
           url: '/admin/maintenance/equipments/get',
           method: 'POST',
           data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id,
           },
           success: function(equipment) {
               $('#equipment').val(equipment.name);
               $('#description').val(equipment.description);
               $('#wholedayrate').val(equipment.wholedayrate);
               $('#halfdayrate').val(equipment.halfdayrate);
               $('#hourlyexcessrate').val(equipment.hourlyexcessrate);

               $('#form-update').attr('action', route.replace('idhere', equipment.id));

               $('#modalUpdate').modal('show');
           }
        });
    @endif
});
</script>
@endsection