@component('mail::message')
    # Introduction

    Here is your OTP.

    @component('mail::button', ['url' => '#'])

    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
