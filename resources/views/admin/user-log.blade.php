@extends('layouts.adminlayout')

@section('styles')
@endsection

@section('title')
Bayanihan Center | User Log
@endsection

@section('content-header')
    <h1>
        User Log
        <small>Audit Trail of Users</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">User Log</li>
    </ol>
@endsection

@section('content')
{{--  User Log Table  --}}
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {{--  box header  --}}
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">User Log List</h3>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                @if (count($userlogs) > 0)
                <table id="tblReservations" class="table table-bordered table-hover">
                    <thead>
                        <th>User</th>
                        <th>User Type</th>
                        <th>User Action</th>
                        <th>Date</th>
                    </thead>
                    <tbody>
                        @foreach ($userlogs as $userlog)
                        <tr>
                            <td>{{ $userlog->fullname }}</td>
                            <td>{{ $userlog->usertype }}</td>
                            <td>{{ $userlog->action}}</td>
                            <td>{{ date_format(date_create($userlog->date), 'D, M d, Y h:i:s A' )}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    <p>No user log found</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
$(function() {
    $('#tblReservations').DataTable();
});
</script>
@endsection