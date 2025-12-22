<?php

namespace App\Http\Controllers;

use App\Models\Deadline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DeadlineController extends Controller
{
    public function index(Request $request)
    {
        $query = Auth::user()->deadlines()->orderBy('due_date', 'asc');

        if ($request->has('filter') && $request->filter === 'completed') {
            $query->where('status', 'completed');
        } else {
            $query->where('status', 'pending');
        }

        $deadlines = $query->get();

        return view('deadlines.index', compact('deadlines'));
    }

    public function create()
    {
        return view('deadlines.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'description' => 'nullable|string',
        ]);

        $deadline = Auth::user()->deadlines()->create([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => 'pending',
        ]);

        if ($deadline->due_date <= now()->addHours(24)) {
            $deadline->user->notify(new \App\Notifications\UpcomingDeadlineNotification($deadline));
            $deadline->update(['reminder_sent' => true]);
        }

        return redirect()->route('deadlines.index')->with('success', 'Deadline created successfully.');
    }

    public function edit(Deadline $deadline)
    {
        if ($deadline->user_id !== Auth::id()) {
            abort(403);
        }
        return view('deadlines.edit', compact('deadline'));
    }

    public function update(Request $request, Deadline $deadline)
    {
        if ($deadline->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'description' => 'nullable|string',
            'status' => 'required|in:pending,completed',
        ]);

        $deadline->update($request->all());

        // Helper to clear notification if completed
        if ($deadline->status === 'completed') {
            $deadline->user->unreadNotifications->filter(function($notification) use ($deadline) {
                return isset($notification->data['deadline_id']) && $notification->data['deadline_id'] === $deadline->id;
            })->markAsRead();
        } elseif ($deadline->status === 'pending') {
            // Reactivate notification
             $deadline->user->notifications()
                 ->where('read_at', '!=', null)
                 ->get()
                 ->filter(function($notification) use ($deadline) {
                     return isset($notification->data['deadline_id']) && $notification->data['deadline_id'] === $deadline->id;
                 })->each(function($notification) {
                     $notification->read_at = null;
                     $notification->save();
                 });
        }

        if (!$deadline->reminder_sent && $deadline->status === 'pending' && $deadline->due_date <= now()->addHours(24)) {
            $deadline->user->notify(new \App\Notifications\UpcomingDeadlineNotification($deadline));
            $deadline->update(['reminder_sent' => true]);
        }

        return redirect()->route('deadlines.index')->with('success', 'Deadline updated successfully.');
    }

    public function destroy(Deadline $deadline)
    {
        if ($deadline->user_id !== Auth::id()) {
            abort(403);
        }

        // Delete associated notifications
        $deadline->user->notifications()
            ->get()
            ->filter(function($notification) use ($deadline) {
                return isset($notification->data['deadline_id']) && $notification->data['deadline_id'] === $deadline->id;
            })->each->delete();

        $deadline->delete();

        return redirect()->route('deadlines.index')->with('success', 'Deadline deleted successfully.');
    }

    public function toggleStatus(Deadline $deadline)
    {
        if ($deadline->user_id !== Auth::id()) {
            abort(403);
        }

        $deadline->status = $deadline->status === 'pending' ? 'completed' : 'pending';
        $deadline->save();

        if ($deadline->status === 'completed') {
            $deadline->user->unreadNotifications->filter(function($notification) use ($deadline) {
                return isset($notification->data['deadline_id']) && $notification->data['deadline_id'] === $deadline->id;
            })->markAsRead();
        } elseif ($deadline->status === 'pending') {
            // Reactivate notification
             $deadline->user->notifications()
                 ->where('read_at', '!=', null)
                 ->get()
                 ->filter(function($notification) use ($deadline) {
                     return isset($notification->data['deadline_id']) && $notification->data['deadline_id'] === $deadline->id;
                 })->each(function($notification) {
                     $notification->read_at = null;
                     $notification->save();
                 });
        }

        return back()->with('success', 'Deadline status updated.');
    }
}
