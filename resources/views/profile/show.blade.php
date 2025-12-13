@extends('layout.app')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2rem; font-weight: 700; color: var(--primary-color);">Profile Management</h1>
        <p style="color: var(--text-secondary);">Manage your personal information</p>
    </div>

    {{-- Logic to check if profile is incomplete --}}
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
        <div style="background-color: #FEF2F2; color: #991B1B; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 2rem; border: 1px solid #FECACA; display: flex; align-items: center; gap: 0.75rem;">
            <span style="font-size: 1.25rem;">⚠️</span>
            <div>
                <strong>Profile Incomplete!</strong> Please complete your profile (DOB, Gender, Blood Group, CGPA) to get full access.
            </div>
        </div>
    @endif

    <div class="card" style="max-width: 800px;">
        @if (session('success'))
            <div style="background-color: #ECFDF5; color: #065f46; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid #A7F3D0;">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            
            <div style="display: flex; gap: 2rem; margin-bottom: 2rem; align-items: start;">
                 <div style="flex-shrink: 0;">
                    @if($user->profile_photo_path)
                        <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile" style="width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 4px solid var(--accent-color);">
                    @else
                        <div style="width: 100px; height: 100px; border-radius: 50%; background: linear-gradient(135deg, var(--accent-color), #FCD34D); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: 700; font-family: var(--font-heading); box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);">
                            {{ substr($user->name, 0, 1) }}
                        </div>
                    @endif
                 </div>
                 <div style="flex: 1;">
                     <label class="form-label" for="profile_photo">Profile Photo</label>
                     <input type="file" id="profile_photo" name="profile_photo" class="form-input" accept="image/*">
                     <p style="font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.25rem;">Recommended: Square JPG, PNG. Max 2MB.</p>
                     @error('profile_photo') <div class="error-message">{{ $message }}</div> @enderror
                 </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <!-- Basic Info -->
                <div class="form-group">
                    <label class="form-label" for="name">Full Name</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    @error('name') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input" value="{{ old('username', $user->username) }}">
                    @error('username') <div class="error-message">{{ $message }}</div> @enderror
                </div>

                <!-- Extended Info -->
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

            <hr style="border: 0; border-top: 1px solid var(--border-color); margin: 2rem 0;">

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <!-- Read Only Info -->
                <div class="form-group">
                    <label class="form-label" for="email">Email (Cannot be changed)</label>
                    <input type="email" id="email" class="form-input" value="{{ $user->email }}" disabled style="background-color: #f1f5f9; color: #94a3b8;">
                </div>

                <div class="form-group">
                    <label class="form-label" for="role">Role</label>
                    <input type="text" id="role" class="form-input" value="{{ ucfirst($user->role) }}" disabled style="background-color: #f1f5f9; color: #94a3b8;">
                </div>
                
                @if($user->role === 'student')
                     <div class="form-group">
                        <label class="form-label">Student ID</label>
                        <input type="text" class="form-input" value="{{ $user->student->student_id }}" disabled style="background-color: #f1f5f9; color: #94a3b8;">
                    </div>
                     <div class="form-group">
                        <label class="form-label">Enrollment Year</label>
                        <input type="text" class="form-input" value="{{ $user->student->enrollment_year }}" disabled style="background-color: #f1f5f9; color: #94a3b8;">
                    </div>
                @elseif($user->role === 'teacher')
                     <div class="form-group">
                        <label class="form-label">Teacher ID</label>
                        <input type="text" class="form-input" value="{{ $user->teacher->teacher_id }}" disabled style="background-color: #f1f5f9; color: #94a3b8;">
                    </div>
                @endif
            </div>

            <div style="margin-top: 2rem; text-align: right;">
                <button type="submit" class="btn btn-primary" style="width: auto; padding-left: 2rem; padding-right: 2rem;">Save Changes</button>
            </div>
        </form>
    </div>

    <!-- Danger Zone -->
    <div class="card" style="max-width: 800px; margin-top: 3rem; border-color: #FECACA; box-shadow: none;">
        <h2 style="font-size: 1.25rem; color: #991B1B; font-weight: 700; margin-bottom: 0.5rem;">Danger Zone</h2>
        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1.5rem;">Once you delete your account, there is no going back. Please be certain.</p>
        
        <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you strictly sure you want to delete your account? This action cannot be undone.');">
            @csrf
            @method('DELETE')
            
            <div class="form-group">
                 <label class="form-label" for="delete_password" style="color: #991B1B;">Confirm Password to Delete</label>
                 <input type="password" id="delete_password" name="password" class="form-input" required style="border-color: #FECACA;">
                 @if($errors->userDeletion->has('password'))
                    <div class="error-message">{{ $errors->userDeletion->first('password') }}</div>
                 @endif
            </div>

            <button type="submit" class="btn" style="background-color: #EF4444; color: white; width: auto;">Delete Account</button>
        </form>
    </div>
@endsection
