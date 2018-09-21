@component('mail::message')
# Cancelled Reservation

Your reservation {{ $reservation->code }} - {{ $reservation->eventtitle }} <br>
has been cancelled for the following reason/s: <br>
{{ $reservation->cancelGrounds }}

We hope you understand,<br>
{{ config('app.name') }}
@endcomponent
