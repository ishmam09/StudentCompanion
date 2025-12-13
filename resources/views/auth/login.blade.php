<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Student Companion</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title">Welcome Back</h1>
                <p class="auth-subtitle">Sign in to your account</p>
            </div>

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" required autofocus value="{{ old('email') }}">
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                        <label class="form-label" for="password" style="margin-bottom: 0;">Password</label>
                        {{-- Forgot Password Link --}}
                        <a href="{{ route('password.request') }}" class="btn-link" style="font-size: 0.85rem;">Forgot Password?</a>
                    </div>
                    <input type="password" id="password" name="password" class="form-input" required>
                </div>

                <button type="submit" class="btn btn-primary">Sign In</button>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">
                        Don't have an account? 
                        <a href="{{ route('register') }}" class="btn-link">Register</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
