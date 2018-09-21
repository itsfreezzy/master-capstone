@component('mail::message')
# New Reservation

Client {{$customer->name}} has applied for a new reservation. <br>
<strong><p style="text-align: center; margin-bottom:0%; padding-bottom:0%">Reservation Information</p></strong>

@component('mail::table')
|             |                              |
| ----------- | ---------------------------- |
| Event Title | {{$reservation->eventtitle}} |
| Date & Time | {{ date('F d, Y', strtotime($reservation->eventdate)) }} \| {{date('h:iA', strtotime($reservationinfo->timestart))}} -  {{date('h:iA', strtotime($reservationinfo->timeend))}} |
| Ingress & Eggress Time | {{date('h:iA', strtotime($reservationinfo->timesingress))}} & {{date('h:iA', strtotime($reservationinfo->timeeggress))}} |
| Number of Attendees | {{ $reservationinfo->numofattendees }} |
| Preferred Function Room/s | @foreach($eventvenues as $key => $ev) {{$ev->name}} &nbsp;&nbsp; @endforeach |
| Caterer | {{ $reservationinfo->isaccredited ? 'Accredited' : 'False' }} - {{ $reservationinfo->caterer }} |
| Nature of Event/s | @foreach(explode(',', $reservationinfo->eventnature) as $en){{$en}} &nbsp;&nbsp; @endforeach|
| | |
| Event Organizer | {{ $reservation->eventorganizer }} |
| Email | {{ $reservation->eoemail }} |
| Contact No. | {{ $reservation->eocontactno }} |
| | |
| Contact Person/s | @foreach($reservationcontacts as $rc) {{$rc->contactname}} &nbsp;&nbsp; @endforeach |
| Telephone No. | @foreach($reservationcontacts as $rc) {{$rc->telno}} &nbsp;&nbsp; @endforeach |
| Mobile No. | @foreach($reservationcontacts as $rc) {{$rc->mobno}} &nbsp;&nbsp; @endforeach |
| Email | @foreach($reservationcontacts as $rc) {{$rc->email}} &nbsp;&nbsp; @endforeach |
@endcomponent

Thanks,<br>
{{ config('app.name') }} System
@endcomponent
