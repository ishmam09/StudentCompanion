<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }



    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'nullable|string|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
            'role' => 'required|in:student,teacher',
            'id_number' => 'required|string', // Student ID or Teacher ID
            'enrollment_year' => 'nullable|required_if:role,student|integer',
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store in Session (temp)
        Session::put('registration_data', $validated);
        Session::put('registration_otp', $otp);

        // Send Email (Log it too for local testing)
        try {
            Mail::to($validated['email'])->send(new \App\Mail\OTPVerification($otp));
        } catch (\Exception $e) {
            Log::info("Could not send email: " . $e->getMessage());
            Log::info("OTP for {$validated['email']} is: $otp");
        }
        
        // Always Log for localhost development
        Log::info("OTP for {$validated['email']}: $otp");

        return redirect()->route('otp.verify');
    }

    public function showVerifyOtp()
    {
        if (!Session::has('registration_data')) {
            return redirect()->route('register');
        }
        return view('auth.verify-otp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|digits:6',
        ]);

        $sessionOtp = Session::get('registration_otp');
        $data = Session::get('registration_data');

        if (!$sessionOtp || !$data) {
            return redirect()->route('register')->withErrors(['email' => 'Session expired. Please register again.']);
        }

        if ($request->otp != $sessionOtp) {
            return back()->with('error', 'Invalid OTP Code.');
        }

        // OTP Valid - Create User
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => $data['username'] ?? null,
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        if ($data['role'] === 'student') {
            Student::create([
                'user_id' => $user->id,
                'student_id' => $data['id_number'],
                'enrollment_year' => $data['enrollment_year'],
            ]);
        } elseif ($data['role'] === 'teacher') {
            Teacher::create([
                'user_id' => $user->id,
                'teacher_id' => $data['id_number'],
            ]);
        }

        // Clear Session
        Session::forget(['registration_data', 'registration_otp']);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function checkUsername(Request $request)
    {
        $request->validate(['username' => 'required|string']);
        $exists = User::where('username', $request->username)->exists();
        return response()->json(['available' => !$exists]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
