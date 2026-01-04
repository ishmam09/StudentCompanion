<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PomodoroController extends Controller
{
    public function index()
    {
        // Optional: strict check if strictly for students
        if (Auth::user()->role !== 'student') {
            return redirect()->route('dashboard');
        }

        return view('pomodoro.index');
    }
}
