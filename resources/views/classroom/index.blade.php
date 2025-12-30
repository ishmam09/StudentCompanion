@extends('layouts.app')
@section('content')
<div class="container">
    <h2>Live Sessions for Course #{{ $courseId }}</h2>

    <div class="card mb-4 p-3">
        <form action="{{ route('classroom.start', $courseId) }}" method="POST">
            @csrf
            <input type="text" name="session_title" placeholder="Topic Name" class="form-control mb-2" required>
            <button type="submit" class="btn btn-success">Start New Live Session</button>
        </form>
    </div>

    <h4>Active Classrooms</h4>
    <div class="list-group">
        @forelse($sessions as $session)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $session->session_title }}</strong><br>
                    <small>Teacher: {{ $session->teacher->name }}</small>
                </div>
                <div>
                    <a href="{{ route('classroom.join', $session->room_id) }}" class="btn btn-primary">Join</a>
                    @if(auth()->id() == $session->teacher_id)
                        <a href="{{ route('classroom.end', $session->room_id) }}" class="btn btn-danger btn-sm">End</a>
                    @endif
                </div>
            </div>
        @empty
            <p>No active classes. Ask your teacher to start one!</p>
        @endforelse
    </div>
</div>
@endsection