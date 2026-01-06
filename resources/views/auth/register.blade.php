<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Student Companion</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
        .validation-item.valid { color: #059669; } /* Green */
        .validation-item.invalid { color: #ef4444; } /* Red */
        
        .validation-icon { width: 16px; display: inline-block; text-align: center; }
        
        .input-error { border-color: #ef4444 !important; }
        .input-success { border-color: #059669 !important; }
        
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
                    <select id="role" name="role" class="form-input" onchange="updateForm()">
                        <option value="student">Student</option>
                        <option value="teacher">Teacher</option>
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
                    <label class="form-label" id="username-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input" value="{{ old('username') }}">
                    <div id="username-error" class="username-feedback" style="color: #ef4444;">Username already taken</div>
                    <div id="username-success" class="username-feedback" style="color: #059669;">Username available</div>
                    @error('username') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" id="id-label" for="id_number">Student ID</label>
                    <input type="text" id="id_number" name="id_number" class="form-input" required placeholder="e.g. 12345678">
                    @error('id_number') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group" id="enrollment-group">
                    <label class="form-label" for="enrollment_year">Enrollment Year</label>
                    <input type="number" id="enrollment_year" name="enrollment_year" class="form-input" placeholder="e.g. 2024">
                    @error('enrollment_year') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-input" style="padding-right: 40px;" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                            <!-- SVG Eye Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
                    
                    <div style="margin-top: 0.5rem; background: #f8fafc; padding: 0.75rem; border-radius: 8px;">
                        <div id="req-length" class="validation-item"><span class="validation-icon">✕</span> Min 8 characters</div>
                        <div id="req-upper" class="validation-item"><span class="validation-icon">✕</span> At least 1 uppercase</div>
                        <div id="req-number" class="validation-item"><span class="validation-icon">✕</span> At least 1 number</div>
                        <div id="req-symbol" class="validation-item"><span class="validation-icon">✕</span> At least 1 special char</div>
                    </div>
                    @error('password') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" style="padding-right: 40px;" required>
                        <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                        </button>
                    </div>
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
    
    <script>
        // --- Password Toggle Logic ---
        function togglePassword(inputId, btn) {
            const input = document.getElementById(inputId);
            const icon = btn.querySelector('svg');
            
            if (input.type === 'password') {
                input.type = 'text';
                // Switch to "Eye Off" icon
                icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
            } else {
                input.type = 'password';
                // Switch back to "Eye" icon
                icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>';
            }
        }

        // --- Form Dynamic Logic ---
        function updateForm() {
            const role = document.getElementById('role').value;
            const idLabel = document.getElementById('id-label');
            const enrollmentGroup = document.getElementById('enrollment-group');
            const usernameLabel = document.getElementById('username-label');
            const usernameInput = document.getElementById('username');
            
            if (role === 'student') {
                idLabel.innerText = 'Student ID';
                enrollmentGroup.style.display = 'block';
                usernameLabel.innerText = 'Username';
                usernameInput.placeholder = 'e.g. john_doe';
                usernameInput.removeAttribute('maxlength');
            } else {
                idLabel.innerText = 'Teacher ID';
                enrollmentGroup.style.display = 'none';
                usernameLabel.innerText = 'Teacher Initial';
                usernameInput.placeholder = 'e.g. ROKS (Max 4)';
                usernameInput.setAttribute('maxlength', '4');
            }
        }

        // --- Live Validations ---
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const usernameInput = document.getElementById('username');
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Password Validation
            const reqs = {
                length: { regex: /.{8,}/, el: document.getElementById('req-length') },
                upper: { regex: /[A-Z]/, el: document.getElementById('req-upper') },
                number: { regex: /[0-9]/, el: document.getElementById('req-number') },
                symbol: { regex: /[!@#$%^&*(),.?":{}|<>]/, el: document.getElementById('req-symbol') }
            };

            passwordInput.addEventListener('input', function() {
                const val = this.value;
                for (const key in reqs) {
                    const isValid = reqs[key].regex.test(val);
                    const el = reqs[key].el;
                    const icon = el.querySelector('.validation-icon');
                    
                    if (isValid) {
                        el.classList.add('valid');
                        el.classList.remove('invalid');
                        icon.innerText = '✓';
                    } else {
                        el.classList.remove('valid');
                        el.classList.add('invalid'); 
                        icon.innerText = '✕';
                    }
                }
            });

            // Username Check (Debounced)
            let timeout = null;
            usernameInput.addEventListener('input', function() {
                const val = this.value;
                const errorEl = document.getElementById('username-error');
                const successEl = document.getElementById('username-success');
                
                clearTimeout(timeout);
                
                // Reset State
                this.classList.remove('input-error', 'input-success');
                errorEl.style.display = 'none';
                successEl.style.display = 'none';

                if (val.length === 0) return;

                timeout = setTimeout(() => {
                    fetch('{{ route("check.username") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ username: val })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.available) {
                            this.classList.add('input-success');
                            successEl.style.display = 'block';
                        } else {
                            this.classList.add('input-error');
                            errorEl.style.display = 'block';
                        }
                    })
                    .catch(console.error);
                }, 500); // 500ms delay
            });
        });
    </script>
</body>
</html>
