<?php

namespace App\Http\Controllers;

use App\Models\VirtualClassroom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ClassroomController extends Controller
{
    // List active classes
    public function index($courseId) {
        // Keeping this for compatibility or removing if no longer used.
        // Assuming dashboard handles the main view now.
        return redirect()->route('dashboard');
    }

    // Teacher creates a class
    public function store(Request $request) {
        if (auth()->user()->role !== 'teacher') {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'classroom_name' => 'required|string',
            'course_code' => 'required|string',
        ]);

        // Generate unique 5-digit code
        do {
            $class_code = str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT);
        } while (VirtualClassroom::where('class_code', $class_code)->exists());

        $room_id = Str::slug("sc-" . $class_code . "-" . now()->timestamp);
        
        $session = VirtualClassroom::create([
            'course_code' => $request->course_code,
            'teacher_id' => auth()->id(),
            'classroom_name' => $request->classroom_name,
            'class_code' => $class_code,
            'room_id' => $room_id,
        ]);

        return back()->with('success', "Classroom Created! Code: $class_code");
    }

    // Student joins by code
    public function joinByCode(Request $request) {
        $request->validate(['class_code' => 'required|string|size:5']);

        $classroom = VirtualClassroom::where('class_code', $request->class_code)->first();

        if (!$classroom) {
            return back()->with('error', 'Invalid Class Code.');
        }

        // Persistent Enrollment: Attach user if not already attached
        $user = auth()->user();
        if(!$user->enrolledClassrooms()->where('virtual_classroom_id', $classroom->id)->exists()) {
             $user->enrolledClassrooms()->syncWithoutDetaching([$classroom->id]);
        }
        
        return redirect()->route('classroom.join', $classroom->room_id);
    }

    // Join via ID (Teacher mainly)
    public function join($roomId) {
        $session = VirtualClassroom::where('room_id', $roomId)->firstOrFail();
        return view('classroom.room', compact('session'));
    }

    // Student leaves the class
    public function leave($roomId) {
        $classroom = VirtualClassroom::where('room_id', $roomId)->firstOrFail();
        
        auth()->user()->enrolledClassrooms()->detach($classroom->id);
        
        return redirect()->route('dashboard')->with('status', 'You have left the classroom: ' . $classroom->classroom_name);
    }

    // Teacher ends the class
    public function end($roomId) {
        VirtualClassroom::where('room_id', $roomId)
                        ->where('teacher_id', auth()->id())
                        ->update(['is_active' => false]);
        return redirect()->route('dashboard')->with('status', 'Class ended successfully.');
    }

    // Chat functionality
    public function getMessages($classId) {
        $messages = \App\Models\ClassroomMessage::where('virtual_classroom_id', $classId)
                    ->with('user:id,name')
                    ->orderBy('created_at', 'asc')
                    ->get();
        return response()->json($messages);
    }

    public function sendMessage(Request $request) {
        $request->validate([
            'virtual_classroom_id' => 'required',
            'message' => 'required|string',
        ]);

        $message = \App\Models\ClassroomMessage::create([
            'virtual_classroom_id' => $request->virtual_classroom_id,
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        return response()->json(['status' => 'success', 'message' => $message->load('user:id,name')]);
    }

    // Classroom Post functionality
    public function getPosts($classId) {
        $posts = \App\Models\ClassroomPost::where('virtual_classroom_id', $classId)
                    ->with('user:id,name')
                    ->orderBy('created_at', 'desc')
                    ->get();
        return response()->json($posts);
    }

    public function storePost(Request $request) {
        $request->validate([
            'virtual_classroom_id' => 'required',
            'content' => 'required|string',
        ]);

        $post = \App\Models\ClassroomPost::create([
            'virtual_classroom_id' => $request->virtual_classroom_id,
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);

        return response()->json(['status' => 'success', 'post' => $post->load('user:id,name')]);
    }
    public function destroy($id)
    {
        $classroom = \App\Models\VirtualClassroom::findOrFail($id);

        if ($classroom->teacher_id !== auth()->id()) {
            abort(403);
        }

        $classroom->delete();
        return redirect()->route('dashboard')->with('success', 'Classroom deleted successfully.');
    }
}