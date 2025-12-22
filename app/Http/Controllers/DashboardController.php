<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Courses;
use App\Models\Deadline;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Calculate pending tasks and upcoming deadlines
        $pendingTasksCount = $user->deadlines()
            ->where('status', 'pending')
            ->count();
            
        $upcomingDeadlinesCount = $user->deadlines()
            ->where('status', 'pending')
            ->where('due_date', '>', now())
            ->count();

        return view('dashboard', compact('pendingTasksCount', 'upcomingDeadlinesCount'));
    }
}
