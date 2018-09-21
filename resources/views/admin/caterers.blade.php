@extends('layouts.adminlayout')

@section('title')
    Bayanihan Center | Caterers
@endsection

@section('styles')
@endsection

@section('content-header')
    <h1>
        Caterers
        <small>List of accredited addcaterers</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Caterers</li>
    </ol>
@endsection

@section('content')
{{--  Caterer Table  --}}
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {{--  box header  --}}
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Caterer List</h3>
                <div class="pull-right" style="padding:0px">
                    <button class="btn btn-block btn-success" data-toggle="modal" data-target="#modalCreate"> <i class="glyphicon glyphicon-plus-sign"></i> Add Caterer </button>
                </div>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                <table id="tblCaterers" class="table table-bordered table-hover">
                    <thead>
                        <th class="col-sm-1">Caterer ID</th>
                        <th class="col-sm-2">Caterer</th>
                        <th class="col-sm-1">Cateerer Email</th>
                        <th>Contact Number</th>
                        <th class="col-sm-2">Contact Person</th>
                        <th>Status</th>
                        <th style="width: 10%">Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($caterers as $caterer)
                        <tr>
                            <td>{{ $caterer->id }}</td>
                            <td>{{ $caterer->name }}</td>

                            <td>
                            @foreach ($catemails as $catemail)
                            @if ($catemail->catererid == $caterer->id)
                                {{ $catemail->email }} <br>
                            @endif
                            @endforeach
                            </td>

                            <td>
                            @foreach ($catcontacts as $catcontact)
                            @if ($catcontact->catererid == $caterer->id)
                                {{ $catcontact->contactno }} ||
                            @endif
                            @endforeach
                            </td>

                            <td>
                            @foreach($catcontactpersons as $catcontactperson)
                            @if($catcontactperson->catererid == $caterer->id)
                                {{ $catcontactperson->person }} <br>
                            @endif
                            @endforeach
                            </td>

                            <td>
                                @if ($caterer->trashed())
                                    <span class="label label-danger">Deactivated</span>
                                @else
                                    <span class="label label-success">Activated</span>
                                @endif
                            </td>

                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-default" data-toggle="modal" data-target="#modalRead{{$caterer->id}}" title="View Caterer Info"> <i class="fa fa-eye"></i></button>
                                    @if(!$caterer->trashed())
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#modalUpdate{{$caterer->id}}" title="Edit Caterer Info"> <i class="fa fa-edit"></i></button>
                                    <button class="btn btn-danger" data-toggle="modal" data-target="#modalDelete{{$caterer->id}}" title="Delete Caterer"> <i class="fa fa-close"></i></button>
                                    @else
                                    <button class="btn btn-warning" data-toggle="modal" data-target="#modalRestore{{$caterer->id}}" title="Restore Caterer"> <i class="fa fa-undo"></i></button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
                <h4 class="modal-title"><i class="fa fa-plus"></i> New Accredited Caterer</h4>
            </div>

            <form action="{{ route('admin.caterers.store') }}" class="form-horizontal" method="post">
                <div class="modal-body">
                        @csrf
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Caterer Name:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="addcaterer" value="{{ old('addcaterer') }}" placeholder="Insert caterer name..." autocomplete="off" >
                            </div>

                            @if ($errors->has('addcaterer'))
                                <div class="col-sm-8 col-sm-offset-3 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addcaterer') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Address:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="addaddress" value="{{ old('addaddress') }}" placeholder="Insert caterer address..." autocomplete="off" >
                            </div>

                            @if ($errors->has('addaddress'))
                                <div class="col-sm-8 col-sm-offset-3 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('addaddress') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Contact No(s):</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="addcatcontact" placeholder="Insert caterer contact number..." autocomplete="off">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="addContact" class="btn btn-default"><i class="glyphicon glyphicon-plus-sign"></i> Add</button>
                            </div>

                            @if ($errors->has('caterercontact'))
                                <div class="col-sm-8 col-sm-offset-3 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('caterercontact') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group" id="AddCatererContact">

                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">E-mail(s):</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="addcatemail" placeholder="Insert caterer email address..." autocomplete="off" >
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="addEmail" class="btn btn-default"><i class="glyphicon glyphicon-plus-sign"></i> Add</button>
                            </div>

                            @if ($errors->has('catereremail'))
                                <div class="col-sm-8 col-sm-offset-3 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('catereremail') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group" id="AddCatererEmail">
                            
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Contact Person(s):</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="addcatcontactperson" placeholder="Insert caterer contact person..." autocomplete="off">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="addContactPerson" class="btn btn-default"><i class="glyphicon glyphicon-plus-sign"></i> Add</button>
                            </div>

                            @if ($errors->has('caterercontactperson'))
                                <div class="col-sm-8 col-sm-offset-3 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('caterercontactperson') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-group" id="AddCatererContactPerson">

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

@foreach($caterers as $caterer)
{{-- Read Modal --}}
<div class="modal fade" id="modalRead{{$caterer->id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-eye"></i> {{sprintf('CAT-%05d', $caterer->id)}} - {{$caterer->name}}</h4>
            </div>

            <form action="" class="form-horizontal" method="post">
                <div class="modal-body">
                    @csrf
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">Caterer Name:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="editcaterer" value="{{ $caterer->name }}" placeholder="Insert caterer name..." autocomplete="off" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">Address:</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="editaddress" value="{{ $caterer->address }}" placeholder="Insert caterer address..." autocomplete="off" readonly>
                        </div>

                        @if ($errors->has('editaddress'))
                            <div class="col-sm-8 col-sm-offset-4 error">
                                <span style="color: red" role="alert">
                                    <strong>{{ $errors->first('editaddress') }}</strong>
                                </span>
                            </div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">Contact No(s):</label>
                        <div class="col-sm-8">
                            @foreach ($catcontacts as $catcontact)
                            @if ($caterer->id == $catcontact->catererid)
                                <input type="text" class="form-control" value="{{$catcontact->contactno}}" readonly>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">E-mail(s):</label>
                        <div class="col-sm-8">
                            @foreach ($catemails as $catereremail)
                            @if($caterer->id == $catereremail->catererid)
                            <input type="text" class="form-control" value="{{$catereremail->email}}" readonly>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">Contact Person(s):</label>
                        <div class="col-sm-8" >
                            @foreach ($catcontactpersons as $catcontactperson)
                            @if($caterer->id == $catcontactperson->catererid)
                            <input type="text" class="form-control" value="{{$catcontactperson->person}}" readonly>
                            @endif
                            @endforeach
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
<div class="modal fade" id="modalUpdate{{$caterer->id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> Update {{sprintf('CAT-%05d', $caterer->id)}} - {{$caterer->name}}</h4>
            </div>

            <form action="{{ route('admin.caterers.edit', ['id' => $caterer->id]) }}" class="form-horizontal" method="post">
                <div class="modal-body">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Caterer Name:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="editcaterer" value="{{ $caterer->name }}" placeholder="Insert caterer name..." autocomplete="off" >
                            </div>

                            @if ($errors->has('editcaterer'))
                                <div class="col-sm-8 col-sm-offset-3 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editcaterer') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Address:</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" name="editaddress" value="{{ $caterer->address }}" placeholder="Insert caterer address..." autocomplete="off" >
                            </div>

                            @if ($errors->has('editaddress'))
                                <div class="col-sm-8 col-sm-offset-3 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('editaddress') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Contact No(s):</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="editcatcontact" placeholder="Insert caterer contact number..." autocomplete="off">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="editAddContact" onclick="funcEditAddContact(this)" class="btn btn-default" data-id="{{$caterer->id}}"><i class="glyphicon glyphicon-plus-sign"></i> Add</button>
                            </div>

                            @if ($errors->has('caterercontact'))
                                <div class="col-sm-8 col-sm-offset-3 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('caterercontact') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="form-group" id="EditCatererContact">
                            @foreach ($catcontacts as $catcontact)
                            @if ($caterer->id == $catcontact->catererid)
                            <div id="editcaterercontact{{$catcontact->id}}">
                                <div class="col-sm-offset-2 col-sm-6">
                                    <input type="text" class="form-control" name="editcaterercontact[]" id="'+email+'" value="{{ $catcontact->contactno }}" readonly>
                                </div>
                                <div class="col-sm-1">
                                    <button id="btnEditDeleteCatContact" onclick="deleteRow(this, {{$catcontact->id}})" type="button" class="btn btn-danger">DELETE</button>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">E-mail(s):</label>
                            <div class="col-sm-6">
                                <input type="email" class="form-control" id="editcatemail" placeholder="Insert caterer email address..." autocomplete="off" >
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="editAddEmail" onclick="funcEditAddEmail(this)" class="btn btn-default" data-id="{{$caterer->id}}"><i class="glyphicon glyphicon-plus-sign" ></i> Add</button>
                            </div>

                            @if ($errors->has('catereremail'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('catereremail') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="form-group" id="EditCatererEmail">
                            @foreach ($catemails as $catereremail)
                            @if($caterer->id == $catereremail->catererid)
                            <div id="editemail{{$catereremail->id}}">
                                <div class="col-sm-offset-2 col-sm-6">
                                    <input type="text" class="form-control" name="editcatereremail[]" id="'+email+'" value="{{ $catereremail->email }}" readonly>
                                </div>
                                <div class="col-sm-1">
                                    <button id="btnEditDeleteCatEmail" onclick="deleteRow(this, {{$catereremail->id}})" type="button" class="btn btn-danger">DELETE</button>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-3 control-label">Contact Person(s):</label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="editcatcontactperson" placeholder="Insert caterer contact person..." autocomplete="off">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="editAddContactPerson" onclick="funcEditAddContactPerson(this)" class="btn btn-default" data-id="{{$caterer->id}}"><i class="glyphicon glyphicon-plus-sign"></i> Add</button>
                            </div>

                            @if ($errors->has('caterercontactperson'))
                                <div class="col-sm-7 col-sm-offset-4 error">
                                    <span style="color: red" role="alert">
                                        <strong>{{ $errors->first('caterercontactperson') }}</strong>
                                    </span>
                                </div>
                            @endif
                        </div>
                        
                        <div class="form-group" id="EditCatererContactPerson">
                            @foreach ($catcontactpersons as $caterercontactperson)
                            @if ($caterer->id == $caterercontactperson->catererid)
                            <input type="hidden" name="catcontactpersonid[]">
                            <div id="editcontactperson{{$caterercontactperson->id}}">
                                <div class="col-sm-offset-2 col-sm-6">
                                    <input type="text" class="form-control" name="editcaterercontactperson[]" id="'+email+'" value="{{$caterercontactperson->person}}" readonly>
                                </div>
                                <div class="col-sm-1">
                                    <button id="btnEditDeleteCatContactPerson" onclick="deleteRow(this, {{$caterercontactperson->id}})" type="button" class="btn btn-danger">DELETE</button>
                                </div>
                            </div>
                            @endif
                            @endforeach
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

@if(!$caterer->trashed())
{{-- Delete Modal --}}
<div class="modal fade" id="modalDelete{{$caterer->id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-trash"></i> Delete {{ sprintf('CAT-%05d', $caterer->id) }} - {{ $caterer->name }}</h4>
            </div>

            <div class="modal-body">
                <h5>Confirm deletion of {{ sprintf('CAT-%05d', $caterer->id) }} - {{ $caterer->name }}?</h5>
            </div>
            
            <div class="modal-footer">
                <form action="{{ route('admin.caterers.destroy', ['id' => $caterer->id]) }}" method="POST" class="pull pull-right">
                    @csrf
                    @method('DELETE')
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
<div class="modal fade" id="modalRestore{{$caterer->id}}" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-undo"></i> Restore {{ sprintf('CAT-%05d', $caterer->id) }} - {{ $caterer->name }}</h4>
            </div>

            <div class="modal-body">
                <h5>Confirm restoration of {{ sprintf('CAT-%05d', $caterer->id) }} - {{ $caterer->name }}?</h5>
            </div>
            
            <div class="modal-footer">
                <form action="{{ route('admin.caterers.restore', ['id' => $caterer->id]) }}" method="POST" class="pull pull-right">
                    @csrf
                    @method('PATCH')
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Confirm Restoration</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endforeach

@endsection
@section('scripts')
<script>
var ctrEmail = 0, ctrContact = 0, ctrContactPerson = 0;

$(function() {
    @if (session('showEditModal'))
        $('#modalUpdate' + {{ session('id') }}).modal('show');
    @endif
    $('#tblCaterers').DataTable({
        "pageLength": 25,
        "order": [],
    });

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // FOR ADDING OF CATERER
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    $('#addEmail').click(function() {
        var exists = false;
        var email = $('#addcatemail').val();

        if (email == null || email.trim() == '') {
            return false;
        }

        var input = $('input[name="catereremail[]"]').map(function() {
            return this.value;
        }).get();

        $.each(input, function(index, value) {
            if (email == value) {
                exists = true;
            }
        });

        if (exists) {
            return false;
        }

        ctrEmail++;
        $('#AddCatererEmail').append('<div id="email'+ctrEmail+'">'+
                                        '<div class="col-sm-offset-2 col-sm-6">' +
                                                '<input type="text" class="form-control" name="catereremail[]" id="'+email+'" value="'+email+'" readonly>' +
                                        '</div>' +
                                        '<div class="col-sm-1">'+
                                            '<button id="btnDeleteCatEmail" onclick="deleteRow(this, '+ctrEmail+')" type="button" class="btn btn-danger">DELETE</button>'+
                                        '</div>'+
                                    '</div>');
    });

    $('#addContact').click(function() {
        var exists = false;
        var contact = $('#addcatcontact').val();
        
        if (contact == null || contact.trim() == '') {
            return false;
        }

        var input = $('input[name="caterercontact[]"]').map(function() {
            return this.value;
        }).get();

        $.each(input, function(index, value) {
            if (contact == value) {
                exists = true;
            }
        });

        if (exists) {
            return false;
        }

        ctrContact++;
        $('#AddCatererContact').append('<div id="contactno'+ctrContact+'">'+
                                            '<div class="col-sm-offset-2 col-sm-6">' +
                                                '<input type="text" class="form-control" name="caterercontact[]" id="'+contact+'" value="'+contact+'" readonly>' +
                                            '</div>' +
                                            '<div class="col-sm-1">'+
                                                '<button id="btnDeleteCatContact" onclick="deleteRow(this, '+ctrContact+')" type="button" class="btn btn-danger"><i class="fa fa-minus"></i> DELETE</button>'+
                                            '</div>'+
                                        '</div>');
    });

    $('#addContactPerson').click(function() {
        var exists = false;
        var contactperson = $('#addcatcontactperson').val();
        
        if (contactperson == null || contactperson.trim() == '') {
            return false;
        }

        var input = $('input[name="caterercontactperson[]"]').map(function() {
            return this.value;
        }).get();

        $.each(input, function(index, value) {
            if (contactperson == value) {
                exists = true;
            }
        });

        if (exists) {
            return false;
        }

        ctrContactPerson++;
        $('#AddCatererContactPerson').append('<div id="contactperson'+ctrContactPerson+'">' +
                                               '<div class="col-sm-offset-2 col-sm-6">' +
                                                    '<input type="text" class="form-control" name="caterercontactperson[]" id="'+contactperson+'" value="'+contactperson+'" readonly>' +
                                                '</div>' +
                                                '<div class="col-sm-1">'+
                                                    '<button id="btnDeleteCatContactPerson" onclick="deleteRow(this, '+ctrContactPerson+')" type="button" class="btn btn-danger">DELETE</button>'+
                                                '</div>' +
                                            '</div>');
    });
});

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//FOR EDITING OF CATERER
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function funcEditAddContact(sourcebtn) {
    var exists = false;
    var src = $(sourcebtn).data('id');
    var contact = $('#modalUpdate' + src + ' #editcatcontact').val();
    $('#modalUpdate' + src + ' #EditCatererContact input').each(function() {
        if (contact == $(this).val()) {
            exists = true;

            return false;
        }
    });

    if (!exists) {
    $('#modalUpdate' + src + ' #EditCatererContact').append('<div id="editcaterercontact'+contact+'">' +
                                        '<div class="col-sm-offset-2 col-sm-6">' +
                                            '<input type="text" class="form-control" name="editcaterercontact[]" value="'+contact+'" readonly>' +
                                        '</div>' +
                                        '<div class="col-sm-1">'+
                                            '<button id="btnEditDeleteCatContact" onclick="deleteRow(this, \''+ contact +'\')" type="button" class="btn btn-danger">DELETE</button>'+
                                        '</div>' +
                                    '</div>');
    }
}


function funcEditAddEmail(sourcebtn) {
    var exists = false;
    var src = $(sourcebtn).data('id');
    var contact = $('#modalUpdate' + src + ' #editcatemail').val();

    $('#modalUpdate' + src + ' #EditCatererEmail input').each(function() {
        if (contact == $(this).val()) {
            exists = true;

            return false;
        }
    });

    if (!exists) {
    $('#modalUpdate' + src + ' #EditCatererEmail').append('<div id="editemail'+contact+'">' +
                                        '<div class="col-sm-offset-2 col-sm-6">' +
                                            '<input type="text" class="form-control" name="editcatereremail[]" value="'+contact+'" readonly>' +
                                        '</div>' +
                                        '<div class="col-sm-1">'+
                                            '<button id="btnEditDeleteCatEmail" onclick="deleteRow(this, \''+ contact +'\')" type="button" class="btn btn-danger">DELETE</button>'+
                                        '</div>' +
                                    '</div>');
    }
}

function funcEditAddContactPerson(sourcebtn) {
    var exists = false;
    var src = $(sourcebtn).data('id');
    var contact = $('#modalUpdate' + src + ' #editcatcontactperson').val();

    $('#modalUpdate' + src + ' #EditCatererContactPerson input').each(function() {
        if (contact == $(this).val()) {
            exists = true;

            return false;
        }
    });

    if (!exists) {
    $('#modalUpdate' + src + ' #EditCatererContactPerson').append('<div id="editcontactperson'+contact+'">' +
                                        '<div class="col-sm-offset-2 col-sm-6">' +
                                            '<input type="text" class="form-control" name="editcaterercontact[]" value="'+contact+'" readonly>' +
                                        '</div>' +
                                        '<div class="col-sm-1">'+
                                            '<button id="btnEditDeleteCatContactPerson" onclick="deleteRow(this, \''+ contact +'\')" type="button" class="btn btn-danger">DELETE</button>'+
                                        '</div>' +
                                    '</div>');
    }
}

function deleteRow(sourceButton, sourceId) {
    var src = sourceButton.id;
    var id = sourceId;
    switch (src) {
        case 'btnDeleteCatContactPerson':
            $('#contactperson' + id).remove();
            ctrContactPerson--;
            break;
        
        case 'btnDeleteCatContact':
            $('#contactno' + id).remove();
            ctrContact--;
            break;

        case 'btnDeleteCatEmail':
            $('#email' + id).remove();
            ctrEmail--;
            break;  

        case 'btnEditDeleteCatContactPerson':
            $('#editcontactperson' + id).remove();
            // ctrContactPerson--;
            break;
        
        case 'btnEditDeleteCatContact':
            $('#editcaterercontact' + id + '').remove();
            // ctrContact--;
            break;

        case 'btnEditDeleteCatEmail':
            $('#editemail' + id).remove();
            // ctrEmail--;
            break;

        default:
            break;
    }
}

@if (session('showAddModal'))
    $('#modalCreate').modal('show');
@endif
</script>
@endsection