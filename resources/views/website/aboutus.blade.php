@extends('layouts.websitelayout')

@section('title')
About Us - UNILAB Bayanihan Center
@endsection

@section('content')
<div class="row">
    <h1 class="text-center wow zoomIn">ABOUT US</h1><br /><br /><br />

    {{-- VISION --}}
    <h3 class="text-center wow bounceInLeft" style="margin-bottom:0%;padding-bottom:0%;">
        <em>"To be the Preferred Venue for Special Meetings and Events"</em>
    </h3>
    <strong><h2 class="text-center wow zoomIn" style="margin-top:0%;padding-top:0%;">Vision</h3></strong><br><br>

    {{-- MISION --}}
    <h3 class="text-center wow bounceInLeft" style="margin-bottom:0%;padding-bottom:0%;">
        <em>"To provide an ideal & appropriate venue with excellent Customer Service to all our Clients for all their events and meeting needs."</em>
    </h3>
    <strong><h2 class="text-center wow zoomIn" style="margin-top:0%;padding-top:0%;">Mision</h3></strong><br><br>

    {{-- History --}}
    <strong><h2 class="text-left wow zoomIn" style="margin-bottom:1%;padding-bottom:0%;">History</h3></strong>
    <p class=" wow bounceInLeft" style="margin-top:0%;padding-top:0%;" style="text-align:justify">
        The Bayanihan Center came into existence as an offshoot of our company's need to have a bigger and more modern meetings venue within our company premises; a facility that will be able to accommodate our growing number of executives, employees, and clients. Its predecessor, the Bayanihan Hall, which was housed in one of our office building, could only accommodate a little over 200 executives during the company's periodic key personnel meeting. The idea of putting up a modern meetings and events facility to be built in a site where an aging warehouse stood became a reality when the Property Servicers Group started its ground breaking activites in the year 2007. By year-end of 2008, the Bayanihan Center was completed and had its first major event, the UNILAB ManCom's Christmas Party. <br><br>

        From that time onwards, the Center became the choice venue of meetings and events conducted by most of its Internal Clients, the UNILAB business units. The idea of opening the facility to External Clients outside of UNILAB business units was hatched in the latter part of the year 2009 to optimize the utilization of the Center. By 2010, the Bayanihan Center started accepting events and meeting schedules from external clients. <br><br>

        With the growing prospects of more events and meetings that can be served by the facility, the Bayanihan Annex was constructed in 2010. In July 2011, the Bayanihan Annex was launched to complement the operations of the main Bayanihan Center. <br><br>

        Today, the Bayanihan Center, with its main and annex facilities, has a total of 16 Function Rooms broken down as follows:
        <p style="text-indent:3%; padding:0%; margin:0%;">1.) JY Campos Hall A <br></p>
        <p style="text-indent:3%; padding:0%; margin:0%;">2.) JY Campos Hall B <br></p>
        <p style="text-indent:3%; padding:0%; margin:0%;">3.) MK Tan Hall <br></p>
        <p style="text-indent:3%; padding:0%; margin:0%;">4.) 13 Small Conference Rooms from A to M<br><br></p>

        These Function Rooms are complemented by spacious lobbies in both the main and annex facility, powerful air-conditioning units, built-in sound and lighting system, and ample, secured parking spaces. <br><br>

        With the increasing trend of its bookings, truly, the Bayanihan Center is gradually becoming the preferred events and meetings venue in this part of the city.
    </p>

  {{-- <img class="img-responsive wow fadeIn" src="{{asset('img/function.jpg')}}" alt="">

  <div class="col-md-6">
    <h3 class="wow bounceInDown">
      ANYTHING...
    </h3>
    <p class="wow bounceInLeft" style="text-align: justify;">
      Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>
  </div>

  <div class="col-md-6">
    <h3 class="wow bounceInDown">
      ANYTIME...
    </h3>
    <p class="wow bounceInRight" style="text-align: justify;">
      Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
    </p>
  </div> --}}
</div>
@endsection
