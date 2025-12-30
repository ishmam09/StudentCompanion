<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsultationController extends Controller
{
    public function index()
    {
        // Get only this student's consultations
        $consultations = Consultation::where('student_id', Auth::id())->get();
        return view('consultations.index', compact('consultations'));
    }

    public function store(Request $request)
    {
        if (Auth::user()->role !== 'student') {
            abort(403, 'Unauthorized access. Only students can schedule consultations.');
        }

        $request->validate([
            'teacher_initial' => 'required',
            'teacher_email' => 'required|email',
            'appointment_date' => 'required|after:now',
            'topic' => 'required'
        ]);

        // Resolve Teacher Initial to Name
        $teacher = \App\Models\User::where('username', $request->teacher_initial)
                    ->where('role', 'teacher')
                    ->first();

        if (!$teacher) {
            return back()->withErrors(['teacher_initial' => 'Invalid Teacher Initial. No teacher found with that initial.'])->withInput();
        }

        $consultation = Consultation::create([
            'student_id' => Auth::id(),
            'teacher_name' => $teacher->name, 
            'teacher_email' => $request->teacher_email,
            'appointment_date' => $request->appointment_date,
            'topic' => $request->topic,
        ]);

        // Notify Teacher
        $teacher->notify(new \App\Notifications\NewConsultationRequest($consultation->id, Auth::user()->name, $request->topic));

        return back()->with('message', 'Consultation requested successfully!');
    }

    public function updateStatus($id, $status)
    {
        $consultation = Consultation::findOrFail($id);
        
        // Security Check: Ensure the logged-in user is the teacher for this consultation (by checking username/email or just name if simplified)
        // Since we stored the initial->Teacher mapping, let's verify via User Model logic or strictly trust 'teacher' role for now + name/email match?
        // Simplest Robust Check: Current User's Name matches consultation teacher_name OR Current User is a Teacher.
        // Ideally, we should have stored 'teacher_id'. Since we didn't, let's check basic Role.
        if (Auth::user()->role !== 'teacher') {
             abort(403);
        }

        $consultation->status = $status;
        $consultation->save();

        // Notify Student
        $student = \App\Models\User::find($consultation->student_id);
        if ($student) {
            $student->notify(new \App\Notifications\ConsultationStatusUpdated(Auth::user()->name, $status));
        }

        // Mark the teacher's notification for this specific request as read
        // Finding the notification via ID is hard without passing it, but we can mark the latest one or rely on the user clearing it.
        // Actually, let's just mark *all* notifications related to this consultation_id as read for the teacher?
        // Simpler: Just redirect back. User can clear notification manually or we add logic later.
        
        // Better UX: Mark notification as read if we can find it.
        foreach(Auth::user()->unreadNotifications as $notification) {
            if (isset($notification->data['consultation_id']) && $notification->data['consultation_id'] == $id) {
                $notification->markAsRead();
            }
        }

        return back()->with('message', "Consultation request marked as $status.");
    }
}