@extends('layouts.adminlayout')

@section('styles')
@endsection

@section('title')
Bayanihan Center | Users
@endsection

@section('content-header')
    <h1>
        Users
        <small>List of Users</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Users</li>
    </ol>
@endsection

@section('content')
@include('inc.messages')
{{--  User Table  --}}
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {{--  box header  --}}
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">User List</h3>
                <div class="pull-right" style="padding:0px">
                    @if (Auth::user()->usertype == 'Administrator')<button class="btn btn-block btn-success" data-toggle="modal" data-target="#modalCreate"> <i class="glyphicon glyphicon-plus-sign"></i> Add User </button> @endif
                </div>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                <table id="tblusers" class="table table-bordered table-hover">
                    <thead>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Status</th>
                        <th style="width: 14%">Actions</th>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->fullname }}</td>
                            <td>{{ $user->username}}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->usertype}}</td>
                            <td>
                                @if ($user->trashed())
                                Inactive
                                @else
                                Active
                                @endif
                            </td>
                            <td>
                                @if (Auth::user()->usertype == 'Administrator')
                                @if ($user->id != Auth::user()->id)
                                @if (!$user->trashed())
                                <button type="button" class="btn btn-primary" title="Edit User Type" id="btnmodalupdate" data-toggle="modal" data-target="#modalUpdate" data-id="{{ $user->id }}"><i class="fa fa-edit"></i></button>
                                <button type="button" class="btn btn-danger" title="Deactivate User" id="btnmodaldelete" data-toggle="modal" data-target="#modalDelete" data-id="{{ $user->id }}"><i class="fa fa-close"></i></button>
                                @else
                                <button type="button" class="btn btn-warning" title="Reactivate User" id="btnmodalrestore" data-toggle="modal" data-target="#modalRestore" data-id="{{ $user->id }}"><i class="fa fa-undo"></i></button>
                                @endif
                                @endif
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalCreate" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-plus"></i> Add</h4>
            </div>

            <form action="{{ route('admin.users.create') }}" class="form-horizontal" method="POST">
                @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="fullname" class="col-xs-3 control-label">{{ __('Name') }}</label>
                    <div class="col-xs-8">
                        <input id="fullname" type="text" class="form-control" name="fullname" value="{{ old('name') }}"  autofocus>
                    </div>

                    @if ($errors->has('fullname'))
                       <div class="col-sm-8 col-sm-offset-3 error">
                           <span style="color: red" role="alert">
                               <strong>{{ $errors->first('fullname') }}</strong>
                           </span>
                       </div>
                   @endif
                </div>

                <div class="form-group">
                    <label for="username" class="col-xs-3 control-label">Username</label>
                    <div class="col-xs-8">
                        <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}"  autofocus>
                    </div>

                    @if ($errors->has('username'))
                       <div class="col-sm-8 col-sm-offset-3 error">
                           <span style="color: red" role="alert">
                               <strong>{{ $errors->first('username') }}</strong>
                           </span>
                       </div>
                   @endif
                </div>

                <div class="form-group">
                    <label for="email" class="col-xs-3 control-label">{{ __('E-Mail Address') }}</label>
                    <div class="col-xs-8">
                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" >
                    </div>

                    @if ($errors->has('email'))
                       <div class="col-sm-8 col-sm-offset-3 error">
                           <span style="color: red" role="alert">
                               <strong>{{ $errors->first('email') }}</strong>
                           </span>
                       </div>
                   @endif
                </div>

                <div class="form-group">
                    <label for="password" class="col-xs-3 control-label">{{ __('Password') }}</label>
                    <div class="col-xs-8">
                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" >
                    </div>

                    @if ($errors->has('password'))
                       <div class="col-sm-8 col-sm-offset-3 error">
                           <span style="color: red" role="alert">
                               <strong>{{ $errors->first('password') }}</strong>
                           </span>
                       </div>
                   @endif
                </div>

                <div class="form-group">
                    <label for="password-confirm" class="col-xs-3 control-label">{{ __('Confirm Password') }}</label>
                    <div class="col-xs-8">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" >
                    </div>

                    @if ($errors->has('password_confirmation'))
                       <div class="col-sm-8 col-sm-offset-3 error">
                           <span style="color: red" role="alert">
                               <strong>{{ $errors->first('password_confirmation') }}</strong>
                           </span>
                       </div>
                   @endif
                </div>

                <div class="form-group row">
                    <label for="usertype" class="col-xs-3 control-label">User Type</label>
                    <div class="col-xs-8">
                        <select name="usertype" id="usertype" class="form-control">
                            <option value="Admin Asst.">Admin Asst.</option>
                            <option value="Administrator">Administrator</option>
                        </select>
                    </div>

                     @if ($errors->has('usertype'))
                        <div class="col-sm-8 col-sm-offset-3 error">
                            <span style="color: red" role="alert">
                                <strong>{{ $errors->first('usertype') }}</strong>
                            </span>
                        </div>
                    @endif
                </div>

                <input type="hidden" name="status" value="Active">
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUpdate" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit</h4>
            </div>

            <form id="form-update" class="form-horizontal" method="POST">
                @csrf
                @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <label for="editfullname" class="col-xs-3 control-label">{{ __('Name') }}</label>
                    <div class="col-xs-8">
                        <input id="editfullname" type="text" class="form-control" value="{{ old('name') }}"  readonly>
                    </div>

                    @if ($errors->has('editfullname'))
                       <div class="col-sm-8 col-sm-offset-3 error">
                           <span style="color: red" role="alert">
                               <strong>{{ $errors->first('editfullname') }}</strong>
                           </span>
                       </div>
                   @endif
                </div>

                <div class="form-group">
                    <label for="editusername" class="col-xs-3 control-label">{{ __('Username') }}</label>
                    <div class="col-xs-8">
                        <input id="editusername" type="text" class="form-control" value="{{ old('name') }}"  readonly>
                    </div>

                    @if ($errors->has('editusername'))
                       <div class="col-sm-8 col-sm-offset-3 error">
                           <span style="color: red" role="alert">
                               <strong>{{ $errors->first('editusername') }}</strong>
                           </span>
                       </div>
                   @endif
                </div>

                <div class="form-group">
                    <label for="editemail" class="col-xs-3 control-label">{{ __('Email') }}</label>
                    <div class="col-xs-8">
                        <input id="editemail" type="text" class="form-control" value="{{ old('name') }}"  readonly>
                    </div>

                    @if ($errors->has('editemail'))
                       <div class="col-sm-8 col-sm-offset-3 error">
                           <span style="color: red" role="alert">
                               <strong>{{ $errors->first('editemail') }}</strong>
                           </span>
                       </div>
                   @endif
                </div>

                <div class="form-group row">
                    <label for="usertype" class="col-xs-3 control-label">User Type</label>
                    <div class="col-xs-8" >
                        <select name="editusertype" id="editusertype" class="form-control">
                            <option value="Admin Asst.">Admin Asst.</option>
                            <option value="Administrator">Administrator</option>
                        </select>
                    </div>

                     @if ($errors->has('editusertype'))
                        <div class="col-sm-8 col-sm-offset-3 error">
                            <span style="color: red" role="alert">
                                <strong>{{ $errors->first('editusertype') }}</strong>
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalDelete" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-close"></i> Deactivate User?</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <form id="form-delete" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalRestore" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title"><i class="fa fa-undo"></i> Reactivate User?</h4>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <form id="form-restore" method="POST">
                    @method('PATCH')
                    @csrf
                    <button type="button" class="btn btn-danger" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Cancel</button>
                    <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $('#tblusers').DataTable();

    $(document).on('click', '#btnmodalupdate', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.users.edit', ['id' => 'idhere']) }}"

        $.ajax({
            url: '/admin/utilities/users/get',
            method: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id,
            },
            success: function(user) {
                $('#editfullname').val(user.fullname);
                $('#editusername').val(user.username);
                $('#editemail').val(user.email);
                $('#editusertype').val(user.usertype);

                $('#form-update').attr('action', route.replace('idhere', user.id));

                $('#modalUpdate').modal('show');
            }
        });
    });

    $(document).on('click', '#btnmodaldelete', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.users.destroy', ['id' => 'idhere']) }}"
        
        console.log(route.replace('idhere', id));
        $('#form-delete').attr('action', route.replace('idhere', id));

        $('#modalDelete').modal('show');
    });

    $(document).on('click', '#btnmodalrestore', function() {
        var id = $(this).data('id');
        var route = "{{ route('admin.users.restore', ['id' => 'idhere']) }}"

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
});
</script>
@endsection