<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Student Companion</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Verify Email</h1>
                <p class="auth-subtitle">We sent a 6-digit code to your email.</p>
            </div>

            <form method="POST" action="{{ route('otp.verify.submit') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="otp">Enter OTP Code</label>
                    <input type="text" id="otp" name="otp" class="form-input" style="letter-spacing: 0.5em; text-align: center; font-size: 1.5rem;" maxlength="6" required autofocus>
                    @error('otp') <div class="error-message">{{ $message }}</div> @enderror
                    @if(session('error')) <div class="error-message">{{ session('error') }}</div> @endif
                </div>

                <button type="submit" class="btn btn-primary">Verify & Register</button>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <form action="{{ route('register') }}" method="GET">
                        <button type="submit" class="btn-link" style="background: none; border: none; font-size: 0.9rem;">Resend Code / Restart</button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
