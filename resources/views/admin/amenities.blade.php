@extends('layouts.adminlayout')

@section('styles')
@endsection

@section('title')
Bayanihan Center | Amenities
@endsection

@section('content-header')
    <h1>
        Amenities
        <small>List of amenities for each venue</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Amenities</li>
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
                <h3 class="box-title">Amenity List</h3>
                <div class="pull-right" style="padding:0px">
                    <button class="btn btn-block btn-success" data-toggle="modal" data-target="#modalCreate"> <i class="glyphicon glyphicon-plus-sign"></i> Add Amenity </button>
                </div>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                @if (count($amenities) > 0)
                <table id="tblSetup" class="table table-bordered table-hover">
                    <thead>
                        <th class="col-sm-1">Amenity ID</th>
                        <th class="col-sm-1">Amenity</th>
                        <th class="col-sm-2">Description</th>
                        <th class="col-sm-1">Status</th>
                        <th style="width: 5%">Actions</th>
                    </thead>
                    <tbody>
                        @foreach($amenities as $amenity)
                        <tr>
                            <td>{{ $amenity->id }}</td>
                            <td>{{ $amenity->amenity }}</td>
                            <td>{{ $amenity->description }}</td>
                            <td>
                                @if($amenity->trashed())
                                <span class="label label-danger">Deactivated</span>
                                @else
                                <span class="label label-success">Activated</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                @if($amenity->trashed())
                                <button class="btn btn-warning" data-toggle="modal" data-target="#modalRestore{{$amenity->id}}" title="Restore Amenity"> <i class="fa fa-undo"></i></button>
                                @else
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalUpdate{{$amenity->id}}" title="Update Amenity"> <i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalDelete{{$amenity->id}}" title="Remove Amenity"> <i class="fa fa-close"></i></button>
                                @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>No Amenities found</p>
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

            <form action="{{ action('AmenityController@store') }}" class="form-horizontal" method="post">
                <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Amenity:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="addamenityName" placeholder="Insert amenity..." autocomplete="off" required>
                            </div>

                            @if ($errors->has('addamenityName'))
                                <div class="col-sm-7 col-sm-offset-4">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addamenityName') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Description:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" name="addamenityDesc" placeholder="Insert amenity description..." autocomplete="off" >
                            </div>

                            @if ($errors->has('addamenityDesc'))
                                <div class="col-sm-7 col-sm-offset-4">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addamenityDesc') }}</strong>
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

            <div class="modal-footer">

            </div>
        </div>
    </div>
</div>

{{-- Read Modal
<div class="modal fade" id="" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
            </div>

            <div class="modal-body">

            </div>

            <div class="modal-footer">

            </div>
        </div>
    </div>
</div> --}}

@if(count($amenities) > 0)
@foreach($amenities as $amenity)
{{-- Update Modal --}}
<div class="modal fade" id="modalUpdate{{$amenity->id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit</h4>
            </div>

            <form action="{{ action('AmenityController@update', ['id' => $amenity->id]) }}" class="form-horizontal" method="post">
                <input type="hidden" name="_method" value="PUT">
                <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Amenity:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" value="{{$amenity->amenity}}" name="editamenityName" placeholder="Insert amenity..." autocomplete="off" required>
                            </div>

                            @if ($errors->has('editamenityName'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editamenityName') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-4 control-label">Description:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" value="{{$amenity->description}}" name="editamenityDesc" placeholder="Insert amenity description..." autocomplete="off" >
                            </div>
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

@if (!$amenity->trashed())
{{-- Delete Modal --}}
<div class="modal fade" id="modalDelete{{$amenity->id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-trash"></i> Delete</h4>
            </div>

            <div class="modal-body">
                <h5>Confirm deletion of amenity?</h5>
            </div>
            
            <div class="modal-footer">
                <form action="{{ action('AmenityController@destroy', ['id' => $amenity->id]) }}" method="POST" class="pull pull-right">
                    @csrf
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Confirm Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>

@else
{{-- Restore Modal --}}
<div class="modal fade" id="modalRestore{{$amenity->id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-undo"></i> Restore</h4>
            </div>
            
            <div class="modal-body">
                Confirm restoration of Amenity?
            </div>

            <div class="modal-footer">
                <form action="{{ action('AmenityController@restore', ['id' => $amenity->id]) }}" method="POST" class="pull pull-right">
                    @csrf
                    <input type="hidden" name="_method" value="PATCH">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Confirm Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach
@endif
@endsection

@section('scripts')
<script>
    $(function() {
        $('#tblSetup').DataTable({
            "pageLength": 25,
            "order": [],
        });
    })

    @if(session('showAddModal'))
        $('#modalCreate').modal('show');
    @endif

    @if(session('showEditModal'))
        $('#modalUpdate' + {{session('id')}}).modal('show');
    @endif
</script>
@endsection