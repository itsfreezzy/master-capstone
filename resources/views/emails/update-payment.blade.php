@component('mail::message')
# Introduction

Client {{ $customer->name }} has updated their {{ $payment->paymenttype }} payment for <br>
{{ $reservation->code }} - {{ $reservation->eventtitle }}. Please verify payment.

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
