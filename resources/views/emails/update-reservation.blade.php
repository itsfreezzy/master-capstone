@component('mail::message')
# Reservation Update

Client {{ $customer->name }} has updated the information for <br>
{{ $reservation->code }} - {{ $reservation->eventtitle }}. <br>
Please take note of the changes.

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}<br><br>

<strong>THIS EMAIL IS AUTO-GENERATED BY THE SYSTEM. DO NOT REPLY TO THIS EMAIL.</strong>
@endcomponent
