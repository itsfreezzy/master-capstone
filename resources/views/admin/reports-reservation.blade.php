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
					<button type="button" class="btn btn-success submitbtn" data-id="search">Search</button>
					<button type="button" class="btn btn-primary submitbtn" data-id="generate">Generate PDF</button>
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
		$('.submitbtn').on('click', function() {
			if ($(this).data('id') == 'search') {
				var route = "{{ route('admin.reports-reservation.update') }}";
				$('#selectionform').attr('action', route);
				$('#selectionform').submit();
			} else if ($(this).data('id') == 'generate') {
				var route = "{{ route('admin.reports-reservation.generatepdf') }}";
				$('#selectionform').attr('action', route);
				$('#selectionform').submit();
			}
		});

		$('#daterange-btn').daterangepicker(
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
			$('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'))
			$('#inputrange').val((new Date(start)).getFullYear() +'-'+ ('0'+(new Date(start).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(start)).getDate()).slice(-2) + '|' + (new Date(end)).getFullYear() +'-'+ ('0'+(new Date(end).getMonth() + 1)).slice(-2) +'-'+ ('0'+(new Date(end)).getDate()).slice(-2));
			console.log($('#inputrange').val());
		})
	});
</script>
@endsection