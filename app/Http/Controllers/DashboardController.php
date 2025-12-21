<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Courses;

class DashboardController extends Controller
{
    public function index()
    {
        $courses = Courses::whereNotNull('grade')->get();

        $totalCredits = $courses->sum('credit');

        $cgpa = $totalCredits > 0
            ? $courses->sum(fn ($c) => $c->credit * $c->grade) / $totalCredits
           : 0;
         
        return view('dashboard',['CGPA' => round($cgpa,2)]);
    }
}
