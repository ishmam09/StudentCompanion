@extends('layout.app')

@section('content')
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 1.875rem; font-weight: 700; color: var(--text-primary);">Dashboard</h1>
        <p style="color: var(--text-secondary);">Welcome back, {{ Auth::user()->name }}!</p>
    </div>

    <div class="card-grid">
        <div class="card">
            <div class="card-title">Pending Tasks</div>
            <div class="card-value">0</div>
        </div>
        <div class="card">
            <div class="card-title">Upcoming Deadlines</div>
            <div class="card-value">0</div>
        </div>
        <div class="card">
            <div class="card-title">CGPA</div>
            <div class="card-value">
                @if(Auth::user()->role === 'student' && Auth::user()->student->cgpa)
                    {{ Auth::user()->student->cgpa }}
                @else
                    <?php echo $CGPA; ?>
                @endif
            </div>
        </div>
        <div class="card">
            <div class="card-title">My Notes</div>
            <div class="card-value">{{ \App\Models\Note::where('user_id', Auth::id())->count() }}</div>
        </div>
        <!-- Add more summary cards here -->
    </div>

    <!-- Future sections -->
    <div style="margin-top: 3rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; margin-bottom: 1rem;">Recent Activity</h2>
        <div class="card">
            <p style="color: var(--text-secondary);">No recent activity to show.</p>
        </div>
    </div>
@endsection
