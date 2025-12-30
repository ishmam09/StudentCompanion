@extends('layout.app')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">
        
        <!-- Welcome Hero -->
        <div style="background: linear-gradient(135deg, var(--primary-color), var(--primary-light)); color: white; padding: 3rem; border-radius: var(--radius-lg); margin-bottom: 3rem; position: relative; overflow: hidden; box-shadow: var(--shadow-card);">
            <!-- Decorative circle -->
            <div style="position: absolute; top: -50px; right: -50px; width: 200px; height: 200px; border-radius: 50%; background: radial-gradient(circle, rgba(212, 175, 55, 0.2) 0%, transparent 70%);"></div>
            
            <div style="position: relative; z-index: 1;">
                <h1 style="font-family: var(--font-heading); font-size: 2.5rem; margin-bottom: 0.5rem; color: white;">Welcome back, {{ Auth::user()->name }}</h1>
                @if(Auth::user()->role === 'student')
                <p style="color: #94A3B8; font-size: 1.1rem; max-width: 600px; margin-bottom: 2rem;">
                    You have <strong style="color: var(--accent-color);">{{ $pendingTasksCount }} pending tasks</strong> requiring your attention today. Stay focused and keep pushing forward.
                </p>
                @endif
                
                <div style="display: flex; gap: 1rem;">
                    @if(Auth::user()->role === 'student')
                    <a href="{{ route('deadlines.create') }}" class="btn" style="background: var(--accent-color); color: var(--primary-color); border: none;">
                        + New Deadline
                    </a>
                    <a href="{{ route('notes.index') }}" class="btn" style="background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(5px);">
                        View Notes
                    </a>    
                    @endif

                    <a href="{{ route('todo.index') }}" class="btn" style="background: #ECC94B; color: #1a202c; font-weight: bold; border: none;">
                    Your To Do List
                    </a>

                    @if(Auth::user()->role === 'teacher')
                    <a href="#" class="btn" style="background: rgba(255,255,255,0.1); color: white; backdrop-filter: blur(5px);">
                        View Routine
                    </a>
                    @endif

                    @if(Auth::user()->role === 'student')
                    <a href="{{ route('consultations.index') }}" class="btn" style="background: #ECC94B; color: #1a202c; font-weight: bold; border: none;">
                    Schedule Consultation
                    </a>
                    @endif
                </div>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="card-grid" style="margin-bottom: 3rem;">
            <!-- Pending Tasks -->
            @if(Auth::user()->role === 'student')
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
            @endif

            <!-- Notes -->
            @if(Auth::user()->role === 'student')
            <div class="card metric-card">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <div style="width: 40px; height: 40px; background: #EFF6FF; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#1D4ED8" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                    </div>
                </div>
                <div class="card-value">{{ \App\Models\Note::where('user_id', Auth::id())->count() }}</div>
                <div class="card-title">My Notes</div>
            </div>
            @endif

            <!-- CGPA -->
            @if(Auth::user()->role === 'student')
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
            @endif
        </div>

        <!-- Virtual Classroom -->                 

        <!-- Virtual Classrooms -->
        <h2 style="font-family: var(--font-heading); font-size: 1.5rem; color: var(--primary-color); margin-bottom: 1.5rem;">
            Virtual Classrooms
        </h2>

        <div style="background: white; border-radius: 16px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);">
            @if(Auth::user()->role === 'teacher')
                <!-- Teacher View: Active Classes & Create -->
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                    
                    <!-- Left: Your Active Classes (Grid) -->
                    <div>
                        <h3 style="margin-top: 0; color: #334155; margin-bottom: 1rem;">Your Active Classrooms</h3>
                         @php
                            $myClasses = \App\Models\VirtualClassroom::where('teacher_id', Auth::id())->where('is_active', true)->orderBy('created_at', 'desc')->get();
                        @endphp
                        @if($myClasses->count() > 0)
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                                @foreach($myClasses as $class)
                                    <a href="{{ route('classroom.join', $class->room_id) }}" style="text-decoration: none; color: inherit;">
                                        <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 1.5rem; border-radius: 12px; color: white; transition: transform 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.1); cursor: pointer; position: relative;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                                            <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 0.5rem;">{{ $class->course_code }}</div>
                                            <div style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; line-height: 1.3;">{{ $class->classroom_name }}</div>
                                            
                                            <!-- Class Code Display for Teacher -->
                                            <div style="background: rgba(255,255,255,0.1); padding: 0.4rem 0.8rem; border-radius: 6px; font-family: monospace; font-size: 0.9rem; display: inline-block; color: #fbbf24; border: 1px dashed rgba(255,255,255,0.3);">
                                                {{ $class->class_code }}
                                            </div>

                                            <!-- Delete Button (Top Right) -->
                                            <object class="delete-btn-obj" style="position: absolute; top: 10px; right: 10px;">
                                                <form action="{{ route('classroom.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this classroom? All data will be lost.');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background: rgba(239, 68, 68, 0.2); border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #fca5a5; transition: all 0.2s;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </object>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div style="padding: 2rem; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1; text-align: center; color: #64748b;">
                                <svg xmlns="http://www.w3.org/2000/svg" style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.5;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                                <p style="margin: 0;">You haven't created any active classrooms yet.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Right: Create New Classroom -->
                    <div>
                        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; border: 1px solid #e2e8f0;">
                            <h3 style="margin-top: 0; color: #334155; margin-bottom: 1rem;">Create New</h3>
                            <form action="{{ route('classroom.store') }}" method="POST">
                                @csrf
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; color: #475569;">Classroom Name</label>
                                    <input type="text" name="classroom_name" placeholder="e.g. Week 1 Session" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px;">
                                </div>
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem; color: #475569;">Course Code</label>
                                    <input type="text" name="course_code" placeholder="e.g. CSE470" required style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px;">
                                </div>
                                <button type="submit" class="btn" style="background: var(--accent-color); color: var(--primary-color); border: none; width: 100%; padding: 0.75rem; border-radius: 8px; font-weight: 700;">
                                    + Create Classroom
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            @else
                <!-- Student View: Join Class & Active Enrollments -->
                <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                    <!-- Left: My Enrolled Interactive Classrooms -->
                    <div>
                         <h3 style="margin-top: 0; color: #334155; margin-bottom: 1rem;">Your Interactive Classrooms</h3>
                         @php
                             // Get enrolled classrooms
                             $enrolledClasses = Auth::user()->enrolledClassrooms()->orderBy('pivot_created_at', 'desc')->get();
                         @endphp
                         
                         @if($enrolledClasses->count() > 0)
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1rem;">
                                @foreach($enrolledClasses as $class)
                                    <a href="{{ route('classroom.join', $class->room_id) }}" style="text-decoration: none; color: inherit;">
                                        <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); padding: 1.5rem; border-radius: 12px; color: white; transition: transform 0.2s; box-shadow: 0 4px 6px rgba(0,0,0,0.1); cursor: pointer; position: relative;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                                            <div style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: #94a3b8; margin-bottom: 0.5rem;">{{ $class->course_code }}</div>
                                            <div style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; line-height: 1.3;">{{ $class->classroom_name }}</div>
                                            <div style="font-size: 0.8rem; color: #fbbf24; font-weight: 600;">Standard Class</div>

                                             <!-- Leave Button (Top Right) -->
                                             <object class="leave-btn-obj" style="position: absolute; top: 10px; right: 10px;">
                                                <form action="{{ route('classroom.leave', $class->room_id) }}" method="POST" onsubmit="return confirm('Are you sure you want to leave this class?');">
                                                    @csrf
                                                    <button type="submit" style="background: rgba(239, 68, 68, 0.2); border: none; width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; color: #fca5a5; transition: all 0.2s;" title="Leave Class">
                                                        <svg xmlns="http://www.w3.org/2000/svg" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </object>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                         @else
                            <div style="padding: 2rem; background: #f8fafc; border-radius: 12px; border: 1px dashed #cbd5e1; text-align: center; color: #64748b;">
                                You haven't joined any interactive classrooms yet.
                            </div>
                         @endif
                    </div>

                    <!-- Right: Join New Class -->
                    <div>
                        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; border: 1px solid #e2e8f0; position: sticky; top: 20px;">
                            <h3 style="margin-top: 0; color: #334155; font-size: 1.1rem;">Join New Enrollment</h3>
                            <form action="{{ route('classroom.join_code') }}" method="POST">
                                @csrf
                                <div style="margin-bottom: 1rem;">
                                    <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Enter 5-Digit Class Code</label>
                                    <input type="text" name="class_code" placeholder="e.g. 12345" required maxlength="5" style="width: 100%; padding: 0.75rem; border: 1px solid #cbd5e1; border-radius: 8px; font-size: 1.1rem; letter-spacing: 2px; text-align: center;">
                                </div>
                                <button type="submit" class="btn" style="background: var(--primary-color); color: white; border: none; width: 100%;">
                                    Join Class
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
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
        
            .pulse-badge {
            background: #ef4444;
            color: white;
            padding: 4px 10px;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 800;
            animation: pulse-animation 2s infinite;
        }

        @keyframes pulse-animation {
            0% { box-shadow: 0 0 0 0px rgba(239, 68, 68, 0.7); }
            100% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        
        }
    </style>
@endsection
