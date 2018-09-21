@component('mail::message')
# Payment Verified

We have received and verified your {{ $payment->paymenttype }} payment with the amount of P{{ number_format($payment->amount, 2) }} for: <br>
<strong>Event Title:</strong> {{ $reservation->code }} - {{ $reservation->eventtitle }} <br>
<strong>Event Date :</strong> {{ date('F d, Y', strtotime($reservation->eventdate)) }} <br>

You still have a balance of <strong>P{{ number_format($reservation->balance, 2) }}</strong>. Please pay the remaining balance 2 weeks before or earlier prior to the event date.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
