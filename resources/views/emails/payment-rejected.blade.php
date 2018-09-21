@component('mail::message')
# Payment Rejected

We have received and checked your {{ $payment->paymenttype }} payment for <br>
{{$reservation->code}} - {{$reservation->eventtitle}}.

We reject payments sent to us because of the following reasons:
1. Incorrect payment details entered.
2. Incorrect proof of payment sent.
3. Insufficient proof of payment sent.
4. All of the above.

{{-- @component('mail::button', ['url' => ''])
Button Text
@endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
