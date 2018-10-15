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
        Misc. Reports
        <small>Reports other than Reservation and Sales</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/admin/dashboard"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Misc. Report</li>
    </ol>
@endsection

@section('content')
@include('inc.messages')
<div class="row">
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border" style="color: white; background-color: #3c8dbc">
				<h3 class="box-title">Payment History</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
			</div>

			<div class="box-body">
			<h1 class="text-center">Payment History</h1>

				{{-- SELECT BOXES --}}
				<div class="row">
					<form action="{{ route('admin.reports.generate.payhist') }}" id="paymenthistoryform" method="POST">
						@csrf
						<input type="text" id="paymenthistrange" name="daterange" required style="display: none">
					</form>
					
					<div class="col-xs-6">
						
					</div>
					
					<div class="col-xs-6">
						
					</div>
				</div>

				<div class="row">
					<div class="col-xs-6">
						
					</div>

					<div class="col-xs-6">
						
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<button type="button" class="btn btn-default col-xs-12" id="paymenthist-dr">
									<span>
										<i class="fa fa-calendar"></i> Date range picker
									</span>
									<i class="fa fa-caret-down"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border" style="color: white; background-color: #3c8dbc">
				<h3 class="box-title">Reservation History</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
			</div>

			<div class="box-body">
			<h1 class="text-center">Reservation History</h1>

				{{-- SELECT BOXES --}}
				<div class="row">
					<form action="{{ route('admin.reports.generate.reshist') }}" id="reservationhistoryform" method="POST">
						@csrf
						<input type="text" id="reshistrange" name="daterange" required style="display: none">
					</form>

					
					<div class="col-xs-6">
						
					</div>

					
					<div class="col-xs-6">
						
					</div>
				</div>

				<div class="row">
					<div class="col-xs-6">

					</div>

					
					<div class="col-xs-6">
						
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<button type="button" class="btn btn-default col-xs-12" id="reshist-dr">
									<span>
										<i class="fa fa-calendar"></i> Date range picker
									</span>
									<i class="fa fa-caret-down"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border" style="color: white; background-color: #3c8dbc">
				<h3 class="box-title">Customers with Balance</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
			</div>

			<div class="box-body">
			<h1 class="text-center">Customers with Balance</h1>

				{{-- SELECT BOXES --}}
				<div class="row">
					<form action="{{ route('admin.reports.generate.custwithbal') }}" id="custwithbalanceform" method="POST">
						@csrf
						<input type="text" id="custwithbalrange" name="daterange" required style="display: none">
					</form>
					
					<div class="col-xs-6">
						
					</div>
					
					<div class="col-xs-6">
						
					</div>
				</div>

				<div class="row">
					<div class="col-xs-6">
						
					</div>

					<div class="col-xs-6">
						
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<button type="button" class="btn btn-default col-xs-12" id="custwithbal-dr">
									<span>
										<i class="fa fa-calendar"></i> Date range picker
									</span>
									<i class="fa fa-caret-down"></i>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border" style="color: white; background-color: #3c8dbc">
				<h3 class="box-title">Activity Log</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                </div>
			</div>

			<div class="box-body">
			<h1 class="text-center">Activity Log</h1>

				{{-- SELECT BOXES --}}
				<div class="row">
					<form action="{{ route('admin.reports.generate.actlog') }}" id="activitylogform" method="POST">
						@csrf
						<input type="text" id="activitylogrange" name="daterange" required style="display: none">
					</form>

					
					<div class="col-xs-6">
						
					</div>

					
					<div class="col-xs-6">
						
					</div>
				</div>

				<div class="row">
					<div class="col-xs-6">

					</div>

					
					<div class="col-xs-6">
						
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<button type="button" class="btn btn-default col-xs-12" id="activitylog-dr">
									<span>
										<i class="fa fa-calendar"></i> Date range picker
									</span>
									<i class="fa fa-caret-down"></i>
							</button>
						</div>
					</div>
				</div>
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

<!-- Page script -->
<script>
$(function() {
	$('#paymenthist-dr').daterangepicker(
	{
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
	},
	function (start, end) {
		$('#paymenthist-dr span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
		$('#paymenthistrange').val((new Date(start)).getFullYear() +'-'+ ('0'+(new Date(start).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(start)).getDate()).slice(-2) + '|' + (new Date(end)).getFullYear() +'-'+ ('0'+(new Date(end).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(end)).getDate()).slice(-2));
		$('#paymenthistoryform').submit();
	});

	$('#reshist-dr').daterangepicker(
	{
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
	},
	function (start, end) {
		$('#reshist-dr span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
		$('#reshistrange').val((new Date(start)).getFullYear() +'-'+ ('0'+(new Date(start).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(start)).getDate()).slice(-2) + '|' + (new Date(end)).getFullYear() +'-'+ ('0'+(new Date(end).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(end)).getDate()).slice(-2));
		$('#reservationhistoryform').submit();
	})

	$('#custwithbal-dr').daterangepicker(
	{
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
	},
	function (start, end) {
		$('#custwithbal-dr span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
		$('#custwithbalrange').val((new Date(start)).getFullYear() +'-'+ ('0'+(new Date(start).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(start)).getDate()).slice(-2) + '|' + (new Date(end)).getFullYear() +'-'+ ('0'+(new Date(end).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(end)).getDate()).slice(-2));
		$('#custwithbalanceform').submit();
	})

	$('#activitylog-dr').daterangepicker(
	{
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
	},
	function (start, end) {
		$('#activitylog-dr span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
		$('#activitylogrange').val((new Date(start)).getFullYear() +'-'+ ('0'+(new Date(start).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(start)).getDate()).slice(-2) + '|' + (new Date(end)).getFullYear() +'-'+ ('0'+(new Date(end).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(end)).getDate()).slice(-2));
		$('#activitylogform').submit();
	})
});
</script>
@endsection