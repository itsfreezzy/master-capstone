<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Site Icon -->
    <link rel="icon" href="{{ asset('img/ULBC Logo no BG v1.png') }}" type="image/jpg">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/font-awesome/css/font-awesome.min.css')}}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/Ionicons/css/ionicons.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('adminlte/dist/css/AdminLTE.min.css')}}">
    <link rel="stylesheet" href="{{asset('adminlte/dist/css/skins/skin-purple.min.css')}}">
    <!-- jQuery 3 -->
    <script type="text/javascript" src="{{asset('adminlte/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    @yield('styles')

    <script>
        var months = new Array(12);
        months[0] = "January";
        months[1] = "February";
        months[2] = "March";
        months[3] = "April";
        months[4] = "May";
        months[5] = "June";
        months[6] = "July";
        months[7] = "August";
        months[8] = "September";
        months[9] = "October";
        months[10] = "November";
        months[11] = "December";
        var weekday = new Array(7);
        weekday[0] = "Sunday";
        weekday[1] = "Monday";
        weekday[2] = "Tuesday";
        weekday[3] = "Wednesday";
        weekday[4] = "Thursday";
        weekday[5] = "Friday";
        weekday[6] = "Saturday";

        function startTime() {
            var today = new Date();
            var year = today.getFullYear();
            var month = today.getMonth();
            var day = today.getDay();
            var time = today.toLocaleTimeString();
            var h = today.getHours();
            var m = today.getMinutes();
            var s = today.getSeconds();
            m = checkTime(m);
            s = checkTime(s);
            document.getElementById('runningtime').innerHTML =
            weekday[day] + ", " + months[month] + " " + today.getDate() + " " + year + " " + time;//h + ":" + m + ":" + s;
            var t = setTimeout(startTime, 500);
        }
        function checkTime(i) {
            if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
            return i;
        }
    </script>
</head>
<body class="hold-transition skin-purple sidebar-mini" onload="startTime()">
    <div class="wrapper">

        @include('customer.header')

        @include('customer.sidebar')

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">

            <section class="content-header">
                @yield('content-header')
            </section>

            <section class="content container-fluid">
                @yield('content')
            </section>
        </div>

        @include('inc.footer')
    </div> <!-- /wrapper -->

    <!-- Bootstrap 3.3.7 -->
    <script type="text/javascript" src="{{asset('adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- DataTables -->
    <script type="text/javascript" src="{{asset('adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <!-- SlimScroll -->
    <script type="text/javascript" src="{{asset('adminlte/bower_components/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script type="text/javascript" src="{{asset('adminlte/dist/js/adminlte.min.js')}}"></script>
    @yield('scripts')
</body>
</html>