@extends('layouts.websitelayout') 
@section('title') Contact Us - UNILAB Bayanihan Center
@endsection
 
@section('content')
<div class="row">
	<div class="col-md-8">
		<h1>CONTACT</h1>

		<div class="col-md-6">
			<p>We would like to hear from you. You can get us on bcmorereservation@unilab.com.ph</p>
		</div>
	</div>

	<div class="col-md-8">
		<h2>Follow us on...</h2>

		<div class="col-md-2">
			<a href="https://www.facebook.com/Unilab"><img class="img-responsive wow pulse" src="{{asset('img/fb.png')}}" alt="" style="width: 95px; height: 95px; animation-iteration-count: infinite;"></a>
		</div>

		<div class="col-md-2">
			<a href="https://www.twitter.com/unilab_ph"><img class="img-responsive wow pulse" src="{{asset('img/twitter.png')}}" alt="" style="width: 95px; height: 95px; animation-iteration-count: infinite;"></a>
		</div>

		<div class="col-md-2">
			<a href="https://www.instagram.com/unilab/"><img class="img-responsive wow pulse" src="{{asset('img/instagram.png')}}" alt="" style="width: 95px; height: 95px; animation-iteration-count: infinite;"></a>
		</div>

		<div class="col-md-2">
			<a href="https://www.youtube.com/user/unilabph"><img class="img-responsive wow pulse" src="{{asset('img/youtube.png')}}" alt="" style="width: 95px; height: 95px; animation-iteration-count: infinite;"></a>
		</div>
	</div>
</div>

<div class="col-md-8">
	<h2>Just go to us at...</h2>
	<p><span class="fa fa-map-pin"></span> 8008 Pioneer St., Kapitolyo, Pasig City, Metro Manila, Philippines</p>
</div>

<div class="col-md-8">
	<h2>Call us at our phone numbers</h2>
	<p><span class="fa fa-phone"></span> (02) 858-1978 | (02) 858-1985</p>
</div>
</div>
@endsection