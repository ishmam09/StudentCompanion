@extends('layout.app')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <!-- Welcome Hero -->
        <div style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); color: white; padding: 3rem; border-radius: var(--radius-lg); margin-bottom: 3rem; position: relative; overflow: hidden; box-shadow: var(--shadow-card);">
            <!-- Decorative circle -->
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; border-radius: 50%; background: radial-gradient(circle, rgba(212, 175, 55, 0.2) 0%, transparent 70%);"></div>
            
            <div style="position: relative; z-index: 1;">
                <h1 style="font-family: var(--font-heading); font-size: 2.5rem; margin-bottom: 0.5rem; color: white;">Welcome back, {{ Auth::user()->name }}</h1>
                <p style="color: #94A3B8; font-size: 1.1rem; max-width: 600px; margin-bottom: 2rem;">
                    You have <strong style="color: var(--accent-color);">{{ $pendingTasksCount }} pending tasks</strong> requiring your attention today. Stay focused and keep pushing forward.
                </p>
                
                <div style="display: flex; gap: 1rem;">
                    <a href="{{ route('deadlines.create') }}" class="btn" style="background: var(--accent-color); color: var(--primary-color); border: none;">
                        + New Deadline
                    </a>
                    <a href="{{ route('notes.index') }}" class="btn" style="background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(5px);">
                        View Notes
                    </a>
                </div>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="card-grid" style="margin-bottom: 3rem;">
            <!-- Pending Tasks -->
            <div class="card metric-card">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="width: 40px; height: 40px; background: #FFFBEB; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#B45309" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                    </div>
                    @if($pendingTasksCount > 0)
                        <span style="font-size: 0.75rem; font-weight: 700; color: #B45309; background: #FFFBEB; padding: 0.2rem 0.6rem; border-radius: 99px;">ACTION NEEDED</span>
                    @endif
                </div>
                <div class="card-value">{{ $pendingTasksCount }}</div>
                <div class="card-title">Pending Tasks</div>
            </div>

            <!-- Upcoming Deadlines -->
            <div class="card metric-card">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="width: 40px; height: 40px; background: #ECFDF5; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#047857" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    </div>
                </div>
                <div class="card-value">{{ $upcomingDeadlinesCount }}</div>
                <div class="card-title">Upcoming Deadlines</div>
            </div>

            <!-- Notes -->
            <div class="card metric-card">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="width: 40px; height: 40px; background: #EFF6FF; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1D4ED8" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                </div>
                <div class="card-value">{{ \App\Models\Note::where('user_id', Auth::id())->count() }}</div>
                <div class="card-title">My Notes</div>
            </div>

            <!-- CGPA -->
            <div class="card metric-card">
                 <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="width: 40px; height: 40px; background: #FEF2F2; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#B91C1C" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    </div>
                </div>
                <div class="card-value">
                    @if(Auth::user()->role === 'student' && Auth::user()->student->cgpa)
                        {{ Auth::user()->student->cgpa }}
                    @else
                        -
                    @endif
                </div>
                <div class="card-title">Current CGPA</div>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div>
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="font-family: var(--font-heading); font-size: 1.5rem; color: var(--primary-color);">Recent Activity</h2>
                <a href="#" style="color: var(--accent-color); font-weight: 600; text-decoration: none; font-size: 0.9rem;">View All</a>
            </div>
            
            <div class="card" style="padding: 0; overflow: hidden;">
                <!-- Placeholder for actual activity feed. For now, static content to show design. -->
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; gap: 1rem; align-items: center;">
                    <div style="width: 8px; height: 8px; background: var(--accent-color); border-radius: 50%;"></div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: var(--primary-color);">Logged in successfully</div>
                        <div style="font-size: 0.85rem; color: var(--text-secondary);">Today, {{ now()->format('h:i A') }}</div>
                    </div>
                </div>
                <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; gap: 1rem; align-items: center;">
                    <div style="width: 8px; height: 8px; background: var(--border-color); border-radius: 50%;"></div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: var(--primary-color);">Checked deadlines</div>
                        <div style="font-size: 0.85rem; color: var(--text-secondary);">Yesterday</div>
                    </div>
                </div>
                 <div style="padding: 1.5rem; display: flex; gap: 1rem; align-items: center;">
                    <div style="width: 8px; height: 8px; background: var(--border-color); border-radius: 50%;"></div>
                    <div style="flex: 1;">
                        <div style="font-weight: 600; color: var(--primary-color);">Updated profile information</div>
                        <div style="font-size: 0.85rem; color: var(--text-secondary);">2 days ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .metric-card {
            transition: all 0.3s ease;
        }
        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection
