@component('mail::message')
# Slot Taken

Dear Client, <br>
Your reservation has been cancelled because another client with the same event date and desired event venue/s has confirmed and paid the Reservation Fee for their reservation. <br> 
Please apply for a new reservation.

Thanks,<br>
{{ config('app.name') }}<br><br>

<strong>THIS EMAIL IS AUTO-GENERATED BY THE SYSTEM. DO NOT REPLY TO THIS EMAIL.</strong>
@endcomponent