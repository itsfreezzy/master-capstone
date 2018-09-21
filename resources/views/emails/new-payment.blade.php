@component('mail::message')
# Payment Submitted

Client {{ $customer->name }} has submitted a {{ $payment->paymenttype }} payment for <br>
{{$reservation->code}} - {{$reservation->eventtitle}}. Please verify payment.

@component('mail::button', ['url' => route('admin.payments.index')])
Verify Payment
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
