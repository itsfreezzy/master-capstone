@extends('layouts.websitelayout')

@section('title')
UNILAB Bayanihan Center
@endsection

@section('styles')
<style>
    .carousel-inner .item {
        width: 100%;
        height: auto;
    }
</style>
@endsection

@section('content')
<div class="wow fadeIn" style="animation-delay: 0.2s">
  <div id="myCarousel" class="carousel slide" data-ride="carousel">
      {{--  Indicators  --}}
      <ol class="carousel-indicators">
          <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          <li data-target="#myCarousel" data-slide-to="1"></li>
          <li data-target="#myCarousel" data-slide-to="2"></li>
      </ol>

      {{--  Wrapper for slides  --}}
      <div class="carousel-inner">
          <div class="item active">
              <img class="center-block" src="{{ asset('img/img1.jpg') }}" style="width: 1200px; height: 576px;">
          </div>
          <div class="item">
              <img class="center-block" src="{{ asset('img/img2.jpg') }}" style="width: 1200px; height: 576px;">
          </div>
          <div class="item">
              <img class="center-block" src="{{ asset('img/img3.jpg') }}" style="width: 1200px; height: 576px;">
          </div>
      </div>

      {{--  Left and right controls  --}}
      <a href="#myCarousel" class="left carousel-control" data-slide="prev">
          <span class="glyphicon glyphicon-chevron-left"></span>
          <span class="sr-only">Previous</span>
      </a>
      <a href="#myCarousel" class="right carousel-control" data-slide="next">
          <span class="glyphicon glyphicon-chevron-right"></span>
          <span class="sr-only">Next</span>
      </a>
  </div>
</div>

<div class="container-fluid">
    <h2 class="wow bounceInLeft" style="">What is Bayanihan Center?</h2>
    <p class=" wow bounceInLeft" style="margin-top:0%;padding-top:0%; animation-delay: 0.4s; text-align:justify">
        The Bayanihan Center came into existence as an offshoot of our company's need to have a bigger and more modern meetings venue within our company premises; a facility that will be able to accommodate our growing number of executives, employees, and clients. Its predecessor, the Bayanihan Hall, which was housed in one of our office building, could only accommodate a little over 200 executives during the company's periodic key personnel meeting. The idea of putting up a modern meetings and events facility to be built in a site where an aging warehouse stood became a reality when the Property Servicers Group started its ground breaking activites in the year 2007. By year-end of 2008, the Bayanihan Center was completed and had its first major event, the UNILAB ManCom's Christmas Party. <br><br>

        From that time onwards, the Center became the choice venue of meetings and events conducted by most of its Internal Clients, the UNILAB business units. The idea of opening the facility to External Clients outside of UNILAB business units was hatched in the latter part of the year 2009 to optimize the utilization of the Center. By 2010, the Bayanihan Center started accepting events and meeting schedules from external clients. <br><br>

        With the growing prospects of more events and meetings that can be served by the facility, the Bayanihan Annex was constructed in 2010. In July 2011, the Bayanihan Annex was launched to complement the operations of the main Bayanihan Center. <br><br>

        Today, the Bayanihan Center, with its main and annex facilities, has a total of 16 Function Rooms broken down as follows:
        <!-- <p style="text-indent:3%; padding:0%; margin:0%;">1.) JY Campos Hall A <br></p>
        <p style="text-indent:3%; padding:0%; margin:0%;">2.) JY Campos Hall B <br></p>
        <p style="text-indent:3%; padding:0%; margin:0%;">3.) MK Tan Hall <br></p>
        <p style="text-indent:3%; padding:0%; margin:0%;">4.) 13 Small Conference Rooms from A to M<br><br></p> -->

        <ol class="wow bounceInLeft">
          <li>
            JY Campos Hall A 
          </li>
          <li>
            JY Campos Hall B
          </li>
          <li>
            MK Tan Hall
          </li>
          <li>
            13 Small Conference Rooms from A to M
          </li>
        </ol>

        These Function Rooms are complemented by spacious lobbies in both the main and annex facility, powerful air-conditioning units, built-in sound and lighting system, and ample, secured parking spaces. <br><br>

        With the increasing trend of its bookings, truly, the Bayanihan Center is gradually becoming the preferred events and meetings venue in this part of the city.

        <li><div class="fb-share-button" data-href="https://unilab-bayanihancenter.herokuapp.com/" data-layout="button_count"></div></li>
        <li><a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script></li>
        <li><div class="g-plus" data-action="share" ></div></li>
    </p>
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

@endsection
