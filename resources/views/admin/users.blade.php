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
{{--  User Table  --}}
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {{--  box header  --}}
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">User List</h3>
                <div class="pull-right" style="padding:0px">
                    <button class="btn btn-block btn-success" data-toggle="modal" data-target="#modalCreate"> <i class="glyphicon glyphicon-plus-sign"></i> Add User </button>
                </div>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                <table id="tblusers" class="table table-bordered table-hover">
                    <thead>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>User Type</th>
                        <th>Status</th>
                        <th style="width: 14%">Actions</th>
                    </thead>
                    <tbody>
                        {{-- <tr>
                            <td>1</td>
                            <td>Richmond France V. Quizon</td>
                            <td>quizonrichmond@gmail.com</td>
                            <td>Administrator</td>
                            <td>Active</td>
                            <td>
                                <button class="btn btn-default" data-toggle="modal" data-target="#modalRead" title="View User Info"> <i class="fa fa-eye"></i></button>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#modalUpdate" title="Edit User Info"> <i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger" data-toggle="modal" data-target="#modalDelete" title="Delete User"> <i class="fa fa-close"></i></button>
                            </td>
                        </tr> --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $('#tblusers').DataTable();
});
</script>
@endsection