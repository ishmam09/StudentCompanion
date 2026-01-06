<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Student Companion</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>

<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Forgot Password</h1>
                <p class="auth-subtitle">Enter your email to receive a reset code</p>
            </div>

            <form method="POST" action="">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" required autofocus>
                </div>

                <button type="button" class="btn btn-primary"
                    onclick="alert('Password reset link sending is not configured yet.')">Send Reset Code</button>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <a href="{{ route('login') }}" class="btn-link">Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>