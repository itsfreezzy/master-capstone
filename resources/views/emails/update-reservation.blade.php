@component('mail::message')
# Reservation Update

Client {{ $customer->name }} has updated the information for <br>
{{ $reservation->code }} - {{ $reservation->eventtitle }}. <br>
Please take note of the changes.

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
