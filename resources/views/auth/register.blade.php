<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Companion</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .validation-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 0.25rem;
        }
    </style>
</head>

<body>
    <div class="auth-container">
        <div class="auth-card" style="max-width: 550px;">
            <div class="auth-header">
                <h1 class="auth-title">Create Account</h1>
                <p class="auth-subtitle">Join Student Companion today</p>
            </div>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label" for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input" required value="{{ old('name') }}">
                    @error('name') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email Address</label>
                    <input type="email" id="email" name="email" class="form-input" required value="{{ old('email') }}">
                    @error('email') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input" value="{{ old('username') }}">
                    @error('username') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="role">I am a...</label>
                    <select id="role" name="role" class="form-input">
                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label" for="id_number">ID Number</label>
                    <input type="text" id="id_number" name="id_number" class="form-input" required
                        placeholder="Student or Teacher ID" value="{{ old('id_number') }}">
                    @error('id_number') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="enrollment_year">Enrollment Year (Students Only)</label>
                    <input type="number" id="enrollment_year" name="enrollment_year" class="form-input"
                        placeholder="e.g. 2024" value="{{ old('enrollment_year') }}">
                    @error('enrollment_year') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <input type="password" id="password" name="password" class="form-input" required>
                    <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 5px;">
                        Min 8 chars, 1 uppercase, 1 number, 1 special char.
                    </p>
                    @error('password') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                        required>
                </div>

                <button type="submit" class="btn btn-primary">Create Account</button>

                <div style="text-align: center; margin-top: 1.5rem;">
                    <p style="color: var(--text-secondary); font-size: 0.9rem;">
                        Already have an account?
                        <a href="{{ route('login') }}" class="btn-link">Sign In</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</body>

</html>