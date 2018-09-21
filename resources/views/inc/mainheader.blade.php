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
                <li class="dropdown-user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{asset('adminlte/dist/img/user2-160x160.jpg')}}" class="user-image" alt="User Image">
                        <span class="hidden-xs">{{ Auth::user()->fullname }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- user image -->
                        <li class="user-header">
                            <img src="{{asset('adminlte/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">

                            <p>
                                {{ Auth::user()->fullname }}
                                <small>{{ Auth::user()->usertype }}</small>
                            </p>
                        </li>
                        <!-- menu footer -->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ route('admin.show.profile') }}" class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ route('admin.logout') }}" class="btn btn-default btn-flat" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Sign out</a>
                                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
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
