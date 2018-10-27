<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>@yield('title')</title>

	<!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    <!-- jQuery 3 -->
    <script type="text/javascript" src="{{asset('adminlte/bower_components/jquery/dist/jquery.min.js')}}"></script>
    <!-- DataTables -->
    <link rel="stylesheet" href="{{asset('adminlte/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
    <!-- Animation -->
    <link rel="stylesheet" href="{{asset('animate/animate.css')}}">

    <style>
        #headerhr {
            margin-top: 0px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <div class="container">
        @include('inc.website.header')

        @yield('content')

        @include('inc.website.footer')
    </div>
    
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v3.0";
    fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" type="text/javascript"></script>
    <!-- Bootstrap 3.3.7 -->
    <script type="text/javascript" src="{{asset('adminlte/bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- DataTables -->
    <script type="text/javascript" src="{{asset('adminlte/bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <!-- DataTables -->
    <script type="text/javascript" src="{{asset('adminlte/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <!-- Animation -->
    <script type="text/javascript" src="{{asset('animate/wow.min.js')}}"></script>
    <script type="text/javascript">
      new WOW().init();
    </script>
    @yield('scripts')
</body>
</html>
