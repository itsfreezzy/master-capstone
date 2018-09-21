@component('mail::message')
# New Reservation

Mr./Ms./Mrs. {{$customer->name}} / {{$reservation->eventorganizer}},<br>
This email serves as proof that we have received your reservation. Here are the details of your reservation: <br><br>

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
{{-- PAYMENT TERMS --}}

<h5><strong>PAYMENT TERMS</strong></h5>
<ul>
    <li>Reservation Fee - PhP 5,000 (paid upon confirmation, Non-refundable)</li>
    <li>50% downpayment (paid 30 days after confirmation)</li>
    <li>50% full payment (paid 30 days before the event)</li>
    <li>Security Deposit - PhP 10,000 (lodged 15 days before the event, returned less charges 3 days after the event)</li>
</ul><br>

{{-- CANCELLATION CHARGES --}}
<h5><strong>CANCELLATION CHARGES</strong></h5>
<ul>
    <li>2 months prior to function date &emsp; - &emsp; 50% of required deposit</li>
    <li>1 month prior to function date &emsp; - &emsp; Forfeiture of required deposit</li>
    <li>2 weeks prior to function date &emsp; - &emsp; 100% cancellation charge</li>
</ul><br>

{{-- HOUSE RULES AND REGULATIONS --}}
<h5><strong>HOUSE RULES AND REGULATIONS</strong></h5>
<ul>
    <li>The Center is a <strong>NO SMOKING</strong> facility.</li>
    <li>Hanging, pinning, pasting, and nailing of any promo/display/ad/announcement materials shall not be allowed on the wall or any part of the facility. STAND ALONE display/ads materials and booths shall be the preferred exhibits.</li>
    <li>No promo/ad/display/booths shall be placed within 2-meter radius beside the busts of Mr. JY Campos and Mr. MK Tan</li>
    <li>Disposal of food and waste materials shall be the responsibility of the organizer. Please follow the <strong>"CLEAN AS YOU GO"</strong> policy.</li>
    <li>Pets are not allowed inside the center.</li>
    <li>Any damages done to the function rooms shall be the accountability of the organizer. Corresponding charges shall be billed to and paid by the organizers.</li>
</ul><br>

<br> Please pay the Reservation Fee of PhP 5,000 to confirm your reservation slot ASAP. <br><br>
As for the guidelines for paying the fees related to the reservation, you can pay at any bank (Bank of the Philippine Island preferrably): <br>
<strong>Account Name: United Laboratories Inc.</strong> <br>
<strong>Account Number: 0183-3481-67 - Bank of the Philippine Island</strong> <br><br>
<strong>NOTE: </strong> All payments for Bayanihan Center are required to be directly deposited to the said account name and number. For confirmation and validation, please send us a scanned copy of the deposit slip once payment is made for processing of the official receipt. For <strong>SECURITY DEPOSIT of PhP 10,000 (CASH ONLY),</strong> which is refundable after the event (if there are no charges incurred), shall be given at the Bayanihan Center Office.


Thanks,<br>
{{ config('app.name') }}
@endcomponent

{{-- | Ingress & Eggress Date | {{ date('F d, Y', strtotime($reservationinfo->dateingress)) }}  & {{ date('F d, Y', strtotime($reservationinfo->dateeggress)) }}  | --}}