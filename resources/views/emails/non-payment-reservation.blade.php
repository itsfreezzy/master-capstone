@component('mail::message')
# Cancellation of Reservation

Dear Client {{ $customer->name }},
Your reservation "{{ $reservation->eventtitle }}" has been cancelled because you failed to pay the Reservation Fee of PhP 5,000.00.

Thanks,<br>
{{ config('app.name') }}
<br><br>
This email is auto-generated by the system. Please do not reply to this email.
@endcomponent
