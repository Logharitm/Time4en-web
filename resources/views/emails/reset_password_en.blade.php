@component('mail::message')
    # Reset Password

    Hello {{ $user->name }},

    We received a request to reset your password.

    @component('mail::button', ['url' => $resetUrl])
        Reset Password
    @endcomponent

    If you did not request this, you can safely ignore this email.

    Thanks,
    {{ config('app.name') }}
@endcomponent
