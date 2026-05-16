@component('mail::message')
# Reset Your Password

Hello {{ $user->name }},

We received a request to reset your Puffcart password. Click the button below to create a new password:

@component('mail::button', ['url' => route('password.reset-form', ['token' => $token])])
Reset Password
@endcomponent

This password reset link will expire in 1 hour.

If you didn't request a password reset, you can ignore this email or reply to let us know.

Thanks,<br>
{{ config('app.name') }} Team
@endcomponent
