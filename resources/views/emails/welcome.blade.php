@component('mail::message')
# Hello, {{ $customer->name }}!

Thank you for registering to UNILAB Bayanihan Center. <br>
You may now log in to your account and apply for a reservation.

@component('mail::button', ['url' => route('client.login')])
Log-in to UNILAB Bayanihan Center
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
