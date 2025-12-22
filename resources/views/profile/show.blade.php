@extends('layout.app')

@section('content')
    <div style="max-width: 900px; margin: 0 auto;">
        
        <!-- Header -->
        <div style="margin-bottom: 3rem;">
            <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--primary-color); margin-bottom: 0.5rem;">Profile Management</h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem;">Manage your personal information and account settings</p>
        </div>

        {{-- Profile Incomplete Warning --}}
        @php
            $incomplete = false;
            if (!$user->date_of_birth || !$user->gender || !$user->blood_group) {
                $incomplete = true;
            }
            if ($user->role === 'student' && !$user->student->cgpa) {
                $incomplete = true;
            }
        @endphp

        @if($incomplete)
            <div style="background: linear-gradient(135deg, #FEF2F2, #FEE2E2); border-left: 4px solid #EF4444; padding: 1.25rem; border-radius: var(--radius-md); margin-bottom: 2rem; display: flex; align-items: start; gap: 1rem; box-shadow: var(--shadow-soft);">
                <div style="width: 40px; height: 40px; background: #FEE2E2; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
                </div>
                <div style="flex: 1;">
                    <div style="font-weight: 700; color: #991B1B; margin-bottom: 0.25rem;">Profile Incomplete</div>
                    <div style="color: #B91C1C; font-size: 0.9rem;">Please complete all required fields (Date of Birth, Gender, Blood Group{{ $user->role === 'student' ? ', CGPA' : '' }}) to unlock full platform features.</div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div style="background: #ECFDF5; border-left: 4px solid #10B981; color: #047857; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            
            <!-- Profile Photo Section -->
            <div class="card" style="margin-bottom: 2rem;">
                <h2 style="font-family: var(--font-heading); font-size: 1.25rem; color: var(--primary-color); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    Profile Picture
                </h2>
                
                <div style="display: flex; gap: 2rem; align-items: center;">
                    <div style="flex-shrink: 0;">
                        @if($user->profile_photo_path)
                            <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile" style="width: 120px; height: 120px; border-radius: 50%; object-fit: cover; border: 4px solid var(--accent-color); box-shadow: 0 8px 20px rgba(212, 175, 55, 0.2);">
                        @else
                            <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, var(--accent-color), #FCD34D); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 700; font-family: var(--font-heading); box-shadow: 0 8px 20px rgba(212, 175, 55, 0.3);">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                    <div style="flex: 1;">
                        <label class="form-label" for="profile_photo">Upload New Photo</label>
                        <input type="file" id="profile_photo" name="profile_photo" class="form-input" accept="image/*" style="margin-bottom: 0.5rem;">
                        <p style="font-size: 0.85rem; color: var(--text-secondary);">Recommended: Square image (JPG or PNG). Maximum file size: 2MB.</p>
                        @error('profile_photo') <div class="error-message">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="card" style="margin-bottom: 2rem;">
                <h2 style="font-family: var(--font-heading); font-size: 1.25rem; color: var(--primary-color); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                    Personal Information
                </h2>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name *</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-input" value="{{ old('username', $user->username) }}">
                        @error('username') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="date_of_birth">Date of Birth</label>
                        <input type="date" id="date_of_birth" name="date_of_birth" class="form-input" value="{{ old('date_of_birth', $user->date_of_birth) }}">
                        @error('date_of_birth') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="gender">Gender</label>
                        <select id="gender" name="gender" class="form-input">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="blood_group">Blood Group</label>
                        <select id="blood_group" name="blood_group" class="form-input">
                            <option value="">Select Blood Group</option>
                            @foreach(['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'] as $bg)
                                <option value="{{ $bg }}" {{ old('blood_group', $user->blood_group) === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                            @endforeach
                        </select>
                        @error('blood_group') <div class="error-message">{{ $message }}</div> @enderror
                    </div>

                    @if($user->role === 'student')
                        <div class="form-group">
                            <label class="form-label" for="cgpa">CGPA (0.00 - 4.00)</label>
                            <input type="number" id="cgpa" name="cgpa" class="form-input" step="0.01" min="0" max="4" value="{{ old('cgpa', $user->student->cgpa) }}">
                            @error('cgpa') <div class="error-message">{{ $message }}</div> @enderror
                        </div>
                    @endif
                </div>
            </div>

            <!-- Account Information (Read-Only) -->
            <div class="card" style="margin-bottom: 2rem; background: var(--background);">
                <h2 style="font-family: var(--font-heading); font-size: 1.25rem; color: var(--primary-color); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    Account Information
                    <span style="font-size: 0.7rem; font-weight: 700; color: var(--text-secondary); background: white; padding: 0.2rem 0.6rem; border-radius: 4px; margin-left: auto;">READ ONLY</span>
                </h2>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div class="form-group">
                        <label class="form-label" for="email">Email Address</label>
                        <input type="email" id="email" class="form-input" value="{{ $user->email }}" disabled style="background-color: white; color: var(--text-secondary); cursor: not-allowed;">
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="role">Account Role</label>
                        <input type="text" id="role" class="form-input" value="{{ ucfirst($user->role) }}" disabled style="background-color: white; color: var(--text-secondary); cursor: not-allowed;">
                    </div>
                    
                    @if($user->role === 'student')
                        <div class="form-group">
                            <label class="form-label">Student ID</label>
                            <input type="text" class="form-input" value="{{ $user->student->student_id }}" disabled style="background-color: white; color: var(--text-secondary); cursor: not-allowed;">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Enrollment Year</label>
                            <input type="text" class="form-input" value="{{ $user->student->enrollment_year }}" disabled style="background-color: white; color: var(--text-secondary); cursor: not-allowed;">
                        </div>
                    @elseif($user->role === 'teacher')
                        <div class="form-group">
                            <label class="form-label">Teacher ID</label>
                            <input type="text" class="form-input" value="{{ $user->teacher->teacher_id }}" disabled style="background-color: white; color: var(--text-secondary); cursor: not-allowed;">
                        </div>
                    @endif
                </div>
            </div>

            <!-- Save Button -->
            <div style="display: flex; justify-content: flex-end; margin-bottom: 3rem;">
                <button type="submit" class="btn btn-primary" style="width: auto; padding-left: 2.5rem; padding-right: 2.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"></path><polyline points="17 21 17 13 7 13 7 21"></polyline><polyline points="7 3 7 8 15 8"></polyline></svg>
                    Save Changes
                </button>
            </div>
        </form>

        <!-- Danger Zone -->
        <div class="card" style="border: 2px solid #FEE2E2; background: linear-gradient(135deg, #FFFBEB, #FEF2F2); box-shadow: 0 4px 12px rgba(239, 68, 68, 0.1);">
            <div style="display: flex; align-items: start; gap: 1rem; margin-bottom: 1.5rem;">
                <div style="width: 40px; height: 40px; background: #FEE2E2; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#DC2626" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="15" y1="9" x2="9" y2="15"></line><line x1="9" y1="9" x2="15" y2="15"></line></svg>
                </div>
                <div>
                    <h2 style="font-family: var(--font-heading); font-size: 1.25rem; color: #991B1B; margin-bottom: 0.5rem;">Danger Zone</h2>
                    <p style="color: #B91C1C; font-size: 0.9rem; margin: 0;">Once you delete your account, there is no going back. All your data will be permanently removed. Please be absolutely certain.</p>
                </div>
            </div>
            
            <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('⚠️ FINAL WARNING: Are you absolutely sure you want to permanently delete your account? This action CANNOT be undone and all your data will be lost forever.');">
                @csrf
                @method('DELETE')
                
                <div class="form-group" style="margin-bottom: 1rem;">
                    <label class="form-label" for="delete_password" style="color: #991B1B;">Confirm Your Password</label>
                    <input type="password" id="delete_password" name="password" class="form-input" required style="border-color: #FCA5A5;" placeholder="Enter your password to confirm deletion">
                    @if($errors->userDeletion->has('password'))
                        <div class="error-message">{{ $errors->userDeletion->first('password') }}</div>
                    @endif
                </div>

                <button type="submit" class="btn" style="background: #EF4444; color: white; width: auto; display: flex; align-items: center; gap: 0.5rem;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                    Permanently Delete Account
                </button>
            </form>
        </div>
    </div>
@endsection
