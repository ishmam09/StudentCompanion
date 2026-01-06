<?php

namespace App\Http\Controllers;

use App\Models\ClassRoutine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassRoutineController extends Controller
{
    /**
     * Display the routine.
     */
    public function index()
    {
        // Use H:i format for keys to ensure easier matching
        $timeSlots = [
            '08:00' => '09:20',
            '09:30' => '10:50',
            '11:00' => '12:20',
            '12:30' => '13:50',
            '14:00' => '15:20',
            '15:30' => '16:50',
        ];

        $days = ['Saturday', 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'];

        $routines = ClassRoutine::where('user_id', Auth::id())->get();

        // Organize routines by Day -> Start Time (H:i)
        $routineMap = [];
        foreach ($routines as $routine) {
            // Ensure strict format matching
            $startTime = \Carbon\Carbon::parse($routine->start_time)->format('H:i');
            $routineMap[$routine->day][$startTime] = $routine;
        }

        return view('routines.index', compact('timeSlots', 'days', 'routineMap'));
    }

    /**
     * Store a new class routine.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course' => 'required|string|max:255',
            'day' => 'required|string|in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday',
            'start_time' => 'required|date_format:H:i',
            'room' => 'nullable|string|max:255',
        ]);

        // Calculate End Time (1 hour 20 minutes duration)
        // Note: The input start_time should ideally match one of the keys in $timeSlots
        // But for calculation:
        $startTime = \Carbon\Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = $startTime->copy()->addMinutes(80); // 1h 20m = 80 min

        ClassRoutine::create([
            'user_id' => Auth::id(),
            'course' => $request->course,
            'day' => $request->day,
            'start_time' => $request->start_time, // saving as H:i string might need strict format for DB time column? DB is time, so H:i:s usually preferred or H:i works.
            'end_time' => $endTime->format('H:i'),
            'room' => $request->room,
        ]);

        return redirect()->back()->with('success', 'Class added successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $routine = ClassRoutine::where('user_id', Auth::id())->findOrFail($id);
        $routine->delete();

        return redirect()->route('class-routines.index')->with('success', 'Class removed successfully!');
    }
}
