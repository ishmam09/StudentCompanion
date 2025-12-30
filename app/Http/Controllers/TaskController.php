<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index() {
        $tasks = Auth::user()->tasks()->get();
        return view('todo', compact('tasks'));
    }

    public function store(Request $request) {
        $request->validate(['title' => 'required', 'quadrant' => 'required']);
        Auth::user()->tasks()->create($request->all());
        return back()->with('message', 'Task added successfully!');
    }

    public function destroy(Task $task) {
        $task->delete();
        return back()->with('message', 'Task deleted!');
    }
}