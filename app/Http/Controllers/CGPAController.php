<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CGPAController extends Controller
{

    public function index()
    {
        return view('cgpa-calculator', ['courses' => [['name' => '', 'credits' => '', 'marks' => '']]]);
    }

    public function calculate(Request $request)
    {
        $courses = $request->input('courses', []);

        if (!is_array($courses)) {
            $courses = [['name' => '', 'credits' => '', 'marks' => '']];
        }

        $action = $request->input('action', 'calculate');

        if ($action === 'add_course') {
            $courses[] = ['name' => '', 'credits' => '', 'marks' => ''];
            return view('cgpa-calculator', ['courses' => $courses]);
        }

        if (str_starts_with($action, 'remove_course_')) {
            $indexToRemove = (int) substr($action, 14);
            if (isset($courses[$indexToRemove])) {
                unset($courses[$indexToRemove]);
                $courses = array_values($courses); // Reindex
            }
            // Ensure at least one row
            if (count($courses) === 0) {
                $courses[] = ['name' => '', 'credits' => '', 'marks' => ''];
            }
            return view('cgpa-calculator', ['courses' => $courses]);
        }

        if ($action === 'reset') {
            return redirect()->route('cgpa');
        }


        $request->validate([
            'courses.*.name' => 'nullable|string',
            'courses.*.credits' => 'required|numeric|min:0',
            'courses.*.marks' => 'required|numeric|min:0|max:100',
        ]);

        $totalCredits = 0;
        $totalPoints = 0;
        $details = [];

        foreach ($courses as $course) {
            // Skip empty rows if any allowed
            if (!isset($course['credits'], $course['marks']) || $course['credits'] === '' || $course['marks'] === '') {
                continue;
            }

            $credits = (float) $course['credits'];
            $marks = (float) $course['marks'];

            $gp = $this->marksToPoint($marks);
            $grade = $this->pointsToGrade($gp);

            $details[] = [
                'name' => $course['name'] ?? '-',
                'credits' => $credits,
                'marks' => $marks,
                'point' => $gp,
                'grade' => $grade,
            ];

            $totalCredits += $credits;
            $totalPoints += ($gp * $credits);
        }

        $gpa = $totalCredits > 0 ? ($totalPoints / $totalCredits) : 0;

        return view('cgpa-calculator', [
            'results' => [
                'totalCredits' => $totalCredits,
                'gpa' => round($gpa, 2),
                'details' => $details,
            ],
            'courses' => $courses
        ]);
    }

    private function marksToPoint($marks)
    {
        if ($marks >= 90)
            return 4.00;
        if ($marks >= 85)
            return 3.70;
        if ($marks >= 80)
            return 3.30;
        if ($marks >= 75)
            return 3.00;
        if ($marks >= 70)
            return 2.70;
        if ($marks >= 65)
            return 2.30;
        if ($marks >= 60)
            return 2.00;
        if ($marks >= 55)
            return 1.70;
        if ($marks >= 50)
            return 1.30;
        return 0.00;
    }

    private function pointsToGrade($point)
    {
        if ($point == 4.00)
            return 'A';
        if ($point >= 3.70)
            return 'A-';
        if ($point >= 3.30)
            return 'B+';
        if ($point >= 3.00)
            return 'B-';
        if ($point >= 2.70)
            return 'C+';
        if ($point >= 2.30)
            return 'C-';
        if ($point >= 2.00)
            return 'D+';
        if ($point >= 1.70)
            return 'D-';
        if ($point >= 1.30)
            return 'E';
        return 'F';
    }
}



