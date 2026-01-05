<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Deadline;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $pendingTasksCount = $user->deadlines()
            ->where('status', 'pending')
            ->count();

        $upcomingDeadlinesCount = $user->deadlines()
            ->where('status', 'pending')
            ->where('due_date', '<=', now()->addHours(24))
            ->count();

        return view('dashboard', compact('pendingTasksCount', 'upcomingDeadlinesCount'));
    }
}
