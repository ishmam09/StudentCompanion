<x-mail::message>
# Verify Your Email Address

Hello,

Thank you for registering with **Student Companion**. Please use the following One-Time Password (OTP) to verify your email address and complete your registration.

<div style="text-align: center; margin: 2rem 0;">
    <span style="font-size: 2rem; font-weight: bold; letter-spacing: 0.2rem; color: #2d3748; background: #edf2f7; padding: 1rem 2rem; border-radius: 8px;">
        {{ $otp }}
    </span>
</div>

This code will expire in 10 minutes.

If you did not create an account, no further action is required.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
