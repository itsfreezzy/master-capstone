@extends('layouts.adminlayout')

@section('styles')
<!-- daterange picker -->
<link rel="stylesheet" href="{{ asset('adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">
@endsection

@section('title')
Bayanihan Center | Reports
@endsection

@section('content-header')
    <h1>
        Reservation Report
        <small>Report about Reservations</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Reservation Report</li>
    </ol>
@endsection

@section('content')
@include('inc.messages')
<div class="row">
	<div class="col-md-3 col-xs-12">
		<div class="box box-primary">
			<div class="box-header with-border" style="color: white; background-color: #3c8dbc">
				<h3 class="box-title">Pending Reservations</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
			</div>
			<div class="box-body">
				{{-- LABELS --}}
				<div class="row">
					<div class="col-xs-offset-3 col-xs-6">
						<label for="">Reservation Date Range:</label>
					</div>
				</div>

				{{-- SELECT BOXES --}}
				<div class="row" >
					<form action="{{ route('admin.reports.generate.pendingres') }}" id="pendingresform" method="POST" novalidate target="_blank">
						@csrf
						<div class="col-xs-8">
							<div class="form-group">
								<button type="button" class="btn btn-default col-xs-12" id="pendingres">
										<span>
											<i class="fa fa-calendar"></i> Date range picker
										</span>
										<i class="fa fa-caret-down"></i>
								</button>
								<input type="text" id="pendingresdate" name="daterange" required style="display: none">
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group">
								<button type="submit" class="btn btn-success col-xs-12" id="submitpendingres">Go!</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-3 col-xs-12">
		<div class="box box-primary">
			<div class="box-header with-border" style="color: white; background-color: #3c8dbc">
				<h3 class="box-title">Confirmed Reservations</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
			</div>
			<div class="box-body">
				{{-- LABELS --}}
				<div class="row">
					<div class="col-xs-offset-3 col-xs-6">
						<label for="">Reservation Date Range:</label>
					</div>
				</div>

				{{-- SELECT BOXES --}}
				<div class="row" >
					<form action="{{ route('admin.reports.generate.confirmedres') }}" id="confirmedresform" method="POST" novalidate target="_blank">
						@csrf
						<div class="col-xs-8">
							<div class="form-group">
								<button type="button" class="btn btn-default col-xs-12" id="confirmedres">
										<span>
											<i class="fa fa-calendar"></i> Date range picker
										</span>
										<i class="fa fa-caret-down"></i>
								</button>
								<input type="text" id="confirmedresdate" name="daterange" required style="display: none">
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group">
								<button type="submit" class="btn btn-success col-xs-12" id="submitconfirmedres">Go!</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-3 col-xs-12">
		<div class="box box-primary">
			<div class="box-header with-border" style="color: white; background-color: #3c8dbc">
				<h3 class="box-title">Done Reservations</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
			</div>
			<div class="box-body">
				{{-- LABELS --}}
				<div class="row">
					<div class="col-xs-offset-3 col-xs-6">
						<label for="">Reservation Date Range:</label>
					</div>
				</div>

				{{-- SELECT BOXES --}}
				<div class="row" >
					<form action="{{ route('admin.reports.generate.doneres') }}" id="doneresform" method="POST" novalidate target="_blank">
						@csrf
						<div class="col-xs-8">
							<div class="form-group">
								<button type="button" class="btn btn-default col-xs-12" id="doneres">
										<span>
											<i class="fa fa-calendar"></i> Date range picker
										</span>
										<i class="fa fa-caret-down"></i>
								</button>
								<input type="text" id="doneresdate" name="daterange" required style="display: none">
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group">
								<button type="submit" class="btn btn-success col-xs-12" id="submitdoneres">Go!</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-3 col-xs-12">
		<div class="box box-primary">
			<div class="box-header with-border" style="color: white; background-color: #3c8dbc">
				<h3 class="box-title">Cancelled Reservations</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
			</div>
			<div class="box-body">
				{{-- LABELS --}}
				<div class="row">
					<div class="col-xs-offset-3 col-xs-6">
						<label for="">Reservation Date Range:</label>
					</div>
				</div>

				{{-- SELECT BOXES --}}
				<div class="row" >
					<form action="{{ route('admin.reports.generate.cancelledres') }}" id="cancelledresform" method="POST" novalidate target="_blank">
						@csrf
						<div class="col-xs-8">
							<div class="form-group">
								<button type="button" class="btn btn-default col-xs-12" id="cancelledres">
										<span>
											<i class="fa fa-calendar"></i> Date range picker
										</span>
										<i class="fa fa-caret-down"></i>
								</button>
								<input type="text" id="cancelledresdate" name="daterange" required style="display: none">
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group">
								<button type="submit" class="btn btn-success col-xs-12" id="submitcancelledres">Go!</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border" style="color: white; background-color: #3c8dbc">
				<h3 class="box-title">Frequency</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
			</div>
			<div class="box-body">
				{{-- LABELS --}}
				<div class="row">
					<div class="col-xs-offset-3 col-xs-6">
						<label for="">Reservation Date Range:</label>
					</div>
				</div>

				{{-- SELECT BOXES --}}
				<div class="row" >
					<form action="" id="selectionform" method="POST">
						@csrf
						<div class="col-xs-offset-3 col-xs-6">
							<div class="form-group">
								<button type="button" class="btn btn-default col-xs-12" id="daterange-btn">
										<span>
											<i class="fa fa-calendar"></i> Date range picker
										</span>
										<i class="fa fa-caret-down"></i>
								</button>
								<input type="text" id="inputrange" name="daterange" required style="display: none">
							</div>
						</div>
					</form>
				</div>

				{{-- BUTTONS --}}
				<div class="row col-xs-offset-3 col-xs-6" style="padding-left: 0.5%; margin-top: 0.5%">
					<button type="button" class="btn btn-success submitbtn" id="btnsearch" data-id="search">Search</button>
					<button type="button" class="btn btn-primary submitbtn" id="btngenerate" data-id="generate">Generate PDF</button>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Number of Reservations per Function Room</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="box-body">
                {!! $resperfuncroomchart->container() !!}
            </div>
        </div>
    </div>


    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Number of Reservations per Event Nature</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
            </div>
			<div class="box-body">
				{!! $respereventnaturechart->container() !!}
			</div>
        </div>
    </div>

    
    <div class="col-md-4">
        <div class="box box-primary">
            <div class="box-header with-border" style="color: white; background-color: #3c8dbc">
                <h3 class="box-title">Number of Reservations per Status</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
				</div>
			</div>
			<div class="box-body">
				{!! $resperstatchart->container() !!}
			</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- ChartJS -->
<script src="{{ asset('adminlte/plugins/Chart.min.js') }}" charset=utf-8></script>
<!-- date-range-picker -->
<script src="{{ asset('adminlte/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('adminlte/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
{!! $resperfuncroomchart->script() !!}
{!! $respereventnaturechart->script() !!}
{!! $resperstatchart->script() !!}

<!-- Page script -->
<script>
$(function() {
	$('#submitpendingres, #submitconfirmedres, #submitdoneres, #submitcancelledres, #btnsearch, #btngenerate').attr('disabled', true);
	$('.submitbtn').on('click', function() {
		if ($(this).data('id') == 'search') {
			var route = "{{ route('admin.reports-reservation.update') }}";
			$('#selectionform').attr('action', route);
			$('#selectionform').submit();
		} else if ($(this).data('id') == 'generate') {
			var route = "{{ route('admin.reports-reservation.generatepdf') }}";
			$('#selectionform').attr('action', route);
			$('#selectionform').attr('target', '_blank');
			$('#selectionform').submit();
		}
	});

	$('#pendingres').daterangepicker({
		ranges   : {
		'Today'       : [moment(), moment()],
		'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
		'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		'This Month'  : [moment().startOf('month'), moment().endOf('month')],
		'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		'Annual'      : [moment().startOf('year'), moment().endOf('year')]
		},
		startDate: moment().subtract(29, 'days'),
		endDate  : moment()
	}, function (start, end) {
		$('#pendingres span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
		$('#pendingresdate').val((new Date(start)).getFullYear() +'-'+ ('0'+(new Date(start).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(start)).getDate()).slice(-2) + '|' + (new Date(end)).getFullYear() +'-'+ ('0'+(new Date(end).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(end)).getDate()).slice(-2));
		$('#submitpendingres').attr('disabled', false);
	});

	$('#confirmedres').daterangepicker({
		ranges   : {
		'Today'       : [moment(), moment()],
		'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
		'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		'This Month'  : [moment().startOf('month'), moment().endOf('month')],
		'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		'Annual'      : [moment().startOf('year'), moment().endOf('year')]
		},
		startDate: moment().subtract(29, 'days'),
		endDate  : moment()
	}, function (start, end) {
		$('#confirmedres span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
		$('#confirmedresdate').val((new Date(start)).getFullYear() +'-'+ ('0'+(new Date(start).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(start)).getDate()).slice(-2) + '|' + (new Date(end)).getFullYear() +'-'+ ('0'+(new Date(end).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(end)).getDate()).slice(-2));
		$('#submitconfirmedres').attr('disabled', false);
	});

	$('#doneres').daterangepicker({
		ranges   : {
		'Today'       : [moment(), moment()],
		'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
		'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		'This Month'  : [moment().startOf('month'), moment().endOf('month')],
		'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		'Annual'      : [moment().startOf('year'), moment().endOf('year')]
		},
		startDate: moment().subtract(29, 'days'),
		endDate  : moment()
	}, function (start, end) {
		$('#doneres span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
		$('#doneresdate').val((new Date(start)).getFullYear() +'-'+ ('0'+(new Date(start).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(start)).getDate()).slice(-2) + '|' + (new Date(end)).getFullYear() +'-'+ ('0'+(new Date(end).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(end)).getDate()).slice(-2));
		$('#submitdoneres').attr('disabled', false);
	});

	$('#cancelledres').daterangepicker({
		ranges   : {
		'Today'       : [moment(), moment()],
		'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
		'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		'This Month'  : [moment().startOf('month'), moment().endOf('month')],
		'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		'Annual'      : [moment().startOf('year'), moment().endOf('year')]
		},
		startDate: moment().subtract(29, 'days'),
		endDate  : moment()
	}, function (start, end) {
		$('#cancelledres span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
		$('#cancelledresdate').val((new Date(start)).getFullYear() +'-'+ ('0'+(new Date(start).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(start)).getDate()).slice(-2) + '|' + (new Date(end)).getFullYear() +'-'+ ('0'+(new Date(end).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(end)).getDate()).slice(-2));
		$('#submitcancelledres').attr('disabled', false);
	});

	$('#daterange-btn').daterangepicker({
		ranges   : {
		'Today'       : [moment(), moment()],
		'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
		'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
		'Last 30 Days': [moment().subtract(29, 'days'), moment()],
		'This Month'  : [moment().startOf('month'), moment().endOf('month')],
		'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
		'Annual'      : [moment().startOf('year'), moment().endOf('year')]
		},
		startDate: moment().subtract(29, 'days'),
		endDate  : moment()
	}, function (start, end) {
		$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
		$('#inputrange').val((new Date(start)).getFullYear() +'-'+ ('0'+(new Date(start).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(start)).getDate()).slice(-2) + '|' + (new Date(end)).getFullYear() +'-'+ ('0'+(new Date(end).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(end)).getDate()).slice(-2));
		$('#btnsearch, #btngenerate').attr('disabled', false);
	});
});
</script>
@endsection