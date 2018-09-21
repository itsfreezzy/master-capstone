@extends('layouts.adminlayout')

@section('styles')
@endsection

@section('title')
Bayanihan Center | Customers
@endsection

@section('content-header')
    <h1>
        Customer List
        <small>Existing Nature of Events</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Customers</li>
    </ol>
@endsection

@section('content')
{{--  Upcoming Events  --}}
<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            {{--  box header  --}}
            <div class="box-header" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Customer List</h3>
            </div>

            {{--  box body  --}}
            <div class="box-body">
                <table id="tblCustomers" class="table table-bordered table-hover">
                    <thead>
                        <th>Customer Code</th>
                        <th>Customer Name</th>
                        <th>Customer Type</th>
                        <th>Contact Number</th>
                        <th>Email Address</th>
                        <th>Status</th>
                        <th style="width: 10%">Actions</th>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>{{ $customer->code }}</td>
                            <td>{{ $customer->name }}</td>
                            <td>{{ $customer->type }}</td>
                            <td>{{ $customer->contactnumber }}</td>
                            <td>{{ $customer->email }}</td>
                            <td>
                            @if ($customer->trashed())
                                <span class="label label-danger">Deactivated</span>
                            @else
                                <span class="label label-success">Activated</span>
                            @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-default" title="View Customer Data" id="btnmodalread" data-id="{{$customer->id}}"> <i class="fa fa-eye"></i></button>
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

{{-- Read Modal --}}
<div class="modal fade" id="modalRead" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="close-btn"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="customercode"><i class="fa fa-eye"></i> </h4>
            </div>

            <div class="modal-body">
                <form action="" class="form-horizontal">
                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Customer Name:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="customername" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Customer Type:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="type" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">TIN Number:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="tinnumber" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Contact Number:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="contactno" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Username:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="username" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-4 control-label">Email:</label>
                        <div class="col-sm-7">
                            <input type="text" class="form-control" id="email" readonly>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-dismiss="modal"> <i class="fa fa-check"></i> OK</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(function() {
    $('#tblCustomers').DataTable({
        "pageLength": 25,
        "order": [],
    });

    $(document).on('click', '#btnmodalread', function() {
        var id = $(this).data('id');
        
        $.ajax({
            url: '/admin/maintenance/customers/get',
            method: 'POST',
            data: {
                _token: $('meta[name=csrf-token]').attr('content'),
                id: id,
            },
            success: function(customer) {
                $("#code").val(customer.code);
                $("#customername").val(customer.name);
                $('#type').val(customer.type);
                $('#tinnumber').val(customer.tinnumber);
                $('#contactno').val(customer.contactnumber);
                $('#username').val(customer.username);
                $('#email').val(customer.email);

                $('#modalRead').modal('show');
            }
        });
    });
})
</script>
@endsection