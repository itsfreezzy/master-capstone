@extends('layouts.adminlayout')

@section('styles')
@endsection

@section('title')
    Bayanihan Center | Events
@endsection

@section('content-header')
    <h1>
        Event Types
        <small>Existing Nature of Events</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Event Type</li>
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
                <h3 class="box-title">Event Type List</h3>
                <div class="pull-right" style="padding:0px">
                    <button class="btn btn-block btn-success" data-toggle="modal" data-target="#modalCreate"> <i class="glyphicon glyphicon-plus-sign"></i> Add Event Type </button>
                </div>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                @if (count($eventnatures) > 0)
                <table id="tblEvents" class="table table-bordered table-hover">
                    <thead>
                        <th>Event ID</th>
                        <th>Nature of Event</th>
                        <th>Status</th>
                        <th style="width: 10%">Actions</th>
                    </thead>
                    <tbody>
                        @foreach($eventnatures as $eventnature)
                        <tr>
                            <td>{{ $eventnature->id }}</td>
                            <td>{{ $eventnature->nature }}</td>
                            <td>
                                @if ($eventnature->trashed())
                                <span class="label label-danger">Deactivated</span>
                                @else
                                <span class="label label-success">Activated</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                @if ($eventnature->trashed())
                                <button class="btn btn-warning" type="button" title="Restore Event Type" id="btnmodalrestore" data-id="{{$eventnature->id}}"> <i class="fa fa-undo"></i></button>
                                @else
                                <button class="btn btn-primary" type="button" title="Edit Event Type" id="btnmodalupdate" data-id="{{$eventnature->id}}"> <i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger" type="button" title="Delete Event Type" id="btnmodaldelete" data-id="{{$eventnature->id}}"> <i class="fa fa-close"></i></button>
                                @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>No Event Natures found</p>
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
                <h4 class="modal-title"><i class="fa fa-plus"></i> Add Event Nature</h4>
            </div>
            
            <form action="{{ action('EventNatureController@store') }}" class="form-horizontal" method="post">
                <div class="modal-body">
                    @csrf

                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Nature of Event:</label>

                        <div class="col-sm-7">
                            <input id="addEventNature" type="text" class="form-control" name="addEventNature" value="{{ old('addEventNature') }}" placeholder="Insert nature of event..." autocomplete="off">
                        </div>

                        @if ($errors->has('addEventNature'))
                            <div class="col-sm-7 col-sm-offset-4 error">
                                <span style="color: red" role="alert">
                                    <strong>{{ $errors->first('addEventNature') }}</strong>
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
                <h4 class="modal-title"><i class="fa fa-edit"></i> Update Event Nature</h4>
            </div>

            <form id="form-update" class="form-horizontal" method="post">
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Nature of Event:</label>
                            <div class="col-sm-7">
                                <input type="text" id="eeventnature" class="form-control" name="editEventNature" placeholder="Insert nature of event..." autocomplete="off" required>
                            </div>

                            @if ($errors->has('editEventNature'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editEventNature') }}</strong>
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
                <h4 class="modal-title"><i class="fa fa-trash"></i> Delete Event Nature</h4>
            </div>

            <div class="modal-body">
                <h5>Confirm deletion of event type?</h5>
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
                <h4 class="modal-title"><i class="fa fa-trash"></i> Restore Event Nature</h4>
            </div>

            <div class="modal-body">
                <h5>Confirm restoration of Event Nature?</h5>
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
<script type="text/javascript">
$(function() {
    $('#tblEvents').DataTable({
        "pageLength": 25,
        "order": [],
    });

    $(document).on('click', '#btnmodalupdate', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.events.edit', ['id' => 'idhere']) }}";

        $.ajax({
            url: '/admin/maintenance/events/get',
            method: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id,
            },
            success: function(eventnature) {
                $('#eeventnature').val(eventnature.nature);
                
                $('#form-update').attr('action', route.replace('idhere', eventnature.id));

                $('#modalUpdate').modal('show');
            }
        })
    });

    $(document).on('click', '#btnmodalrestore', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.events.restore', ['id' => 'idhere']) }}";

        $('#form-restore').attr('action', route.replace('idhere', id));

        $('#modalRestore').modal('show');
    });

    $(document).on('click', '#btnmodaldelete', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.events.destroy', ['id' => 'idhere']) }}";

        $('#form-delete').attr('action', route.replace('idhere', id));

        $('#modalDelete').modal('show');
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
        var id = {{ session('id') }};
        var route = "{{ route('admin.events.edit', ['id' => 'idhere']) }}";

        $.ajax({
            url: '/admin/maintenance/events/get',
            method: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id,
            },
            success: function(eventnature) {
                $('#eeventnature').val(eventnature.nature);
                
                $('#form-update').attr('action', route.replace('idhere', eventnature.id));

                $('#modalUpdate').modal('show');
            }
        })
    @endif
});

    
</script>
@endsection