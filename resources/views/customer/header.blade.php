<!-- Main Header -->
<header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini"><b>UNI</b></span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg"><b>B</b>ayanihan <b>C</b>enter</span>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar Toggle Button -->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle Navigation</span>
        </a>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- user account -->
                <li><h4 style="color: white; padding-top: 6px"><div id="runningtime"></div></h4></li>
                <li class="user-menu"></li>
                <li class="dropdown-user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{asset('adminlte/dist/img/user2-160x160.jpg')}}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- user image -->
                        <li class="user-header">
                            <img src="{{asset('adminlte/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">

                            <p>
                                {{ Auth::user()->name }}
                                <small>Customer Type: {{ Auth::user()->type }}</small>
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <li class="user-body">
                            <div class="row">
                                <div class="col-xs-12 text-center">
                                    <a href="{{ route('landingpage') }}">Go to Website</a>
                                </div>
                            </div>
                            <!-- /.row -->
                        </li>
                        <!-- menu footer -->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('client.show.profile')}}" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('client.logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                                <form id="logout-form" action="{{ route('client.logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
