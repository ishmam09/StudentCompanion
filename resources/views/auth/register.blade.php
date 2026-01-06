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
            transition: color 0.2s;
        }

        .validation-item.valid {
            color: #059669;
        }

        /* Green */
        .validation-item.invalid {
            color: #ef4444;
        }

        /* Red */

        .validation-icon {
            width: 16px;
            display: inline-block;
            text-align: center;
        }

        .input-error {
            border-color: #ef4444 !important;
        }

        .input-success {
            border-color: #059669 !important;
        }

        .username-feedback {
            font-size: 0.85rem;
            margin-top: 0.25rem;
            display: none;
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
                    <label class="form-label" for="role">I am a...</label>
                    <select id="role" name="role" class="form-input">
                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                    </select>
                </div>

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
                    <label class="form-label" for="username">Username / Teacher Initial</label>
                    <input type="text" id="username" name="username" class="form-input" value="{{ old('username') }}"
                        placeholder="e.g. john_doe (Student) or TJW (Teacher)">
                    <p class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">Teachers: Max 4 characters.
                    </p>
                    @error('username') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="id_number">Student ID / Teacher ID</label>
                    <input type="text" id="id_number" name="id_number" class="form-input" required
                        placeholder="e.g. 12345678" value="{{ old('id_number') }}">
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
                    <p class="text-muted" style="font-size: 0.8rem; margin-top: 0.25rem;">Min 8 chars, 1 uppercase, 1
                        number, 1 symbol.</p>
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
</body>

</html>