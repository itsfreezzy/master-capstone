@component('mail::message')
# Forgot Password Request

Dear {{ $customer->name }}, <br>
You have requested your password to be changed. This is your new password: {{ $newpassword }}. <br>
Please change your password to your desired one ASAP.

@component('mail::button', ['url' => route('client.show.profile') ])
Change my Password!
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
