<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <div id="sidebar">
    <section class="sidebar">

        <!-- Sidebar Menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header"><b>HOME</b></li>
            <!-- Optionally, you can add icons to the links -->
            <li {{ (Request::is('admin/dashboard') ? 'class=active' : '') }}>
                <a href="/admin/dashboard"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>


            <li class="header"><b>TRANSACTIONS</b></li>
            <li {{ (Request::is('admin/reservations', 'admin/reservations/*') ? 'class=active' : '') }}>
                <a href="/admin/reservations"><i class="fa fa-calendar"></i> <span>Reservations</span></a>
            </li>
            <li {{ (Request::is('admin/payments') ? 'class=active' : '') }}>
                <a href="/admin/payments"><i class="fa fa-money"></i> <span>Payments Tracking</span></a>
            </li>
            <li {{ (Request::is('admin/balances') ? 'class=active' : '') }}>
                <a href="/admin/balances"><i class="fa fa-balance-scale"></i> <span>Balance</span></a>
            </li>


            <li class="header"><b>MAINTENANCE</b></li>
            <li class="{{ (Request::is('admin/maintenance/meeting-rooms') ? 'active' : '') }}{{ (Request::is('admin/maintenance/function-halls') ? 'active' : '') }} treeview">
                <a href="#"><i class="fa fa-building"></i> <span>Function Rooms</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li {{ (Request::is('admin/maintenance/function-halls') ? 'class=active' : '') }}>
                        <a href="/admin/maintenance/function-halls"><i class="fa fa-circle-o"></i> Function Halls</a>
                    </li>
                    <li {{ (Request::is('admin/maintenance/meeting-rooms') ? 'class=active' : '') }}>
                        <a href="/admin/maintenance/meeting-rooms"><i class="fa fa-circle-o"></i> Meeting Rooms</a>
                    </li>
                </ul>
            </li>
            {{-- <li {{ (Request::is('admin/maintenance/venues') ? 'class=active' : '') }}>
                <a href="/admin/maintenance/venues"><i class="fa fa-building"></i> <span>Venues</span></a>
            </li> --}}
            <li {{ (Request::is('admin/maintenance/events') ? 'class=active' : '') }}>
                <a href="/admin/maintenance/events"><i class="fa fa-birthday-cake"></i> <span>Event Type</span></a>
            </li>
            <li {{ (Request::is('admin/maintenance/setup') ? 'class=active' : '') }}>
                <a href="/admin/maintenance/setup"><i class="fa fa-sitemap"></i> <span>Setup Type</span></a>
            </li>
            <li {{ (Request::is('admin/maintenance/equipments') ? 'class=active' : '') }}>
                <a href="/admin/maintenance/equipments"><i class="fa fa-paperclip"></i> <span>Equipments</span></a>
            </li>
            <li {{ (Request::is('admin/maintenance/amenities') ? 'class=active' : '') }}>
                <a href="/admin/maintenance/amenities"><i class="fa fa-plus-square-o"></i> <span>Amenities</span></a>
            </li>
            <li {{ (Request::is('admin/maintenance/customers') ? 'class=active' : '') }}>
                <a href="/admin/maintenance/customers"><i class="fa fa-user"></i> <span>Customers</span></a>
            </li>
            <li {{ (Request::is('admin/maintenance/caterers') ? 'class=active' : '') }}>
                <a href="/admin/maintenance/caterers"><i class="fa fa-cutlery"></i> <span>Caterers</span></a>
            </li>


            <li class="header"><b>REPORTS</b></li>
            <li {{ (Request::is('admin/reports/reservation') ? 'class=active' : '') }}>
                <a href="/admin/reports/reservation"><i class="fa fa-bar-chart"></i> <span>Reservation Report</span></a>
            </li>
            <li {{ (Request::is('admin/reports/sales') ? 'class=active' : '') }}>
                <a href="/admin/reports/sales"><i class="fa fa-line-chart"></i> <span>Sales Report</span></a>
            </li>


            <li class="header"><b>UTILITIES</b></li>
            <li {{ (Request::is('admin/utilities/users') ? 'class=active' : '') }}>
                <a href="/admin/utilities/users"><i class="fa fa-users"></i> <span>Users</span></a>
            </li>
            <li {{ (Request::is('admin/utilities/user-log') ? 'class=active' : '') }}>
                <a href="/admin/utilities/user-log"><i class="fa fa-history"></i> <span>User Log</span></a>
            </li>
            <li {{ (Request::is('admin/utilities/backupandrestore') ? 'class=active' : '') }}>
                <a href="/admin/utilities/backupandrestore"><i class="fa fa-database"></i> <span>Backup and Restore</span></a>
            </li>

            {{--  <li {{ (Request::is('admin/utilities/contact') ? 'class=active' : '') }}>
                <a href="/admin/utilities/contact"><i class="fa fa-book"></i> <span>Contact</span></a>
            </li>
            <li {{ (Request::is('admin/utilities/website-info') ? 'class=active' : '') }}>
                <a href="/admin/utilities/website-info"><i class="fa fa-info-circle"></i> <span>Website Info</span></a>  --}}
            </li>
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    </div>
    <!-- /.sidebar -->
</aside>

<script type="text/javascript">
</script>