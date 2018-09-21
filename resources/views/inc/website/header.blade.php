<!-- <div class="row" style="position: ; z-index: 1; background: white; border-bottom: 1px solid black;">
    <a href="/">
        <div class="col-sm-1" >
            <img src="{{ asset('img/unilablogo.jpg') }}" alt="Logo" style="width: 100px; height: 100px;">
        </div>

        <div class="col-sm-3" style="padding-top: 22px; padding-bottom: 22px;">
            <h3>Bayanihan Center</h3>
        </div>
    </a>

    <a href="/">
        <div class="col-sm-1 text-center" style="padding-top: 45px; padding-bottom: 35px;">
            Home
        </div>
    </a>

    <a href="/amenities">
        <div class="col-sm-1 text-center" style="padding-top: 45px; padding-bottom: 35px;">
            Amenities
        </div>
    </a>

    <a href="/caterers">
        <div class="col-sm-1 text-center" style="padding-top: 38px; padding-bottom: 20px;">
            Accredited Caterers
        </div>
    </a>

    <a href="/rates">
        <div class="col-sm-1 text-center" style="padding-top: 45px; padding-bottom: 35px;">
            Rates
        </div>
    </a>

    <a href="/schedule">
        <div class="col-sm-1 text-center" style="padding-top: 45px; padding-bottom: 35px;">
            Schedule
        </div>
    </a>
    
    <a href="/reservation">
        <div class="col-sm-1 text-center" style="padding-top: 38px; padding-bottom: 20px;">
            Make a Reservation
        </div>
    </a>

    <a href="/about-us">
        <div class="col-sm-1 text-center" style="padding-top: 45px; padding-bottom: 35px;">
            About Us
        </div>
    </a>

    <a href="/contact-us">
        <div class="col-sm-1 text-center" style="padding-top: 38px; padding-bottom: 20px;">
            Contact Us
        </div>
    </a>
</div> -->

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="/" style="padding-top: 1%; margin-top:10%">
       <img src="{{ asset('img/ULBC Logo no BG v1.png') }}" alt="Logo" style="width: 40px; height: 40px;">
     </a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav">
        <li>
            <a href="/">
                Home
            </a>
        </li>
        <li>
            <a href="/amenities">
                Amenities
            </a>
        </li>
        <li>
            <a href="/caterers">
                Accredited Caterers
            </a>
        </li>
        <li>
            <a href="/rates">
                Rates
            </a>
        </li>
        <li>
            <a href="/schedule">
                Schedule
            </a>
        </li>
        <li>
            <a href="/customer/reservation/create">
                Make a Reservation
            </a>
        </li>
        <li>
            <a href="/about-us">
                About
            </a>
        </li>
        <li>
            <a href="/contact-us">
                Contact
            </a>
        </li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        @guest('customer')
        <li><a href="{{ route('client.register') }}"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
        <li><a href="{{ route('client.login') }}"><span class="glyphicon glyphicon-log-in"></span> Login</a></li>
        @endguest
        @auth('customer')
        <li><a href="{{ route('client.index') }}"><span class="fa fa-home"></span> Go to Client Interface</a></li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

<!-- GOES TO FOOTER Follow UNILAB
<a href="https://www.facebook.com/Unilab"><i class="fa fa-facebook-official"></i></a>
<a href="https://twitter.com/unilab_ph"><i class="fa fa-twitter"></i></a>
<a href="https://www.youtube.com/user/unilabph"><i class="fa fa-youtube-square"></i></a>
<a href="https://www.instagram.com/unilab/"><i class="fa fa-instagram"></i></a> -->
