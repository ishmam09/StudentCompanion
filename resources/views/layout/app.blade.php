<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Student Companion') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <div class="app-container">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div class="nav-left">
                <button class="sidebar-toggle" id="sidebarToggle" title="Toggle Sidebar">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <a href="{{ route('dashboard') }}" class="brand-logo">
                    <span>📚</span> Student Companion
                </a>
            </div>

            <!-- Important Things (Right Side) -->
            <div style="display: flex; align-items: center; gap: 1.5rem;">
                <!-- Placeholder Search -->
                <div style="position: relative; display: none; @media(min-width: 768px){display:block;}">
                    <input type="text" placeholder="Search..." style="background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); padding: 0.5rem 1rem; border-radius: 20px; color: white; width: 200px;">
                </div>

                <!-- Notification -->
                <div style="position: relative;" class="notification-wrapper">
                    <button class="btn" onclick="toggleNotifications()" style="padding: 0.5rem; background: transparent; color: white; position: relative;">
                        <span style="font-size: 1.25rem;">🔔</span>
                        @if(Auth::user()->unreadNotifications->count() > 0)
                            <span style="position: absolute; top: 0; right: 0; background: #ef4444; color: white; font-size: 0.6rem; width: 16px; height: 16px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                                {{ Auth::user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>
                    <!-- Dropdown -->
                    <div id="notificationDropdown" style="display: none; position: absolute; right: 0; top: 120%; width: 320px; background: white; border-radius: 12px; box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); overflow: hidden; z-index: 60;">
                        <div style="padding: 1rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <span style="font-weight: 600; color: var(--primary-color);">Notifications</span>
                            @if(Auth::user()->unreadNotifications->count() > 0)
                                <a href="{{ route('notifications.mark-all-read') }}" style="font-size: 0.75rem; color: var(--accent-color); text-decoration: none;">Mark all read</a>
                            @endif
                        </div>
                        <div style="max-height: 300px; overflow-y: auto;">
                            @forelse(Auth::user()->unreadNotifications as $notification)
                                <div class="notification-item" style="display: flex; gap: 0.75rem; padding: 1rem; border-bottom: 1px solid var(--border-color); align-items: start; transition: background 0.2s;">
                                    <div style="background: var(--background); padding: 0.5rem; border-radius: 8px; font-size: 1.25rem;">
                                        {{ $notification->data['icon'] ?? '📢' }}
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-size: 0.85rem; color: var(--primary-color); line-height: 1.4;">{{ $notification->data['message'] }}</div>
                                        <div style="font-size: 0.7rem; color: var(--text-secondary); margin-top: 0.25rem;">{{ $notification->created_at->diffForHumans() }}</div>
                                        
                                        @if(isset($notification->data['type']) && $notification->data['type'] === 'consultation_request')
                                            @php
                                                $consultation = \App\Models\Consultation::find($notification->data['consultation_id'] ?? null);
                                            @endphp
                                            
                                            @if($consultation && $consultation->status === 'pending')
                                                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                                                    <a href="{{ $notification->data['action_url_accept'] }}" style="padding: 0.25rem 0.75rem; background: #dcfce7; color: #166534; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-decoration: none;">Accept</a>
                                                    <a href="{{ $notification->data['action_url_reject'] }}" style="padding: 0.25rem 0.75rem; background: #fee2e2; color: #991b1b; border-radius: 4px; font-size: 0.75rem; font-weight: 600; text-decoration: none;">Reject</a>
                                                </div>
                                            @elseif($consultation && $consultation->status === 'approved')
                                                <div style="margin-top: 0.25rem; font-size: 0.75rem; color: #15803d; font-weight: 600;">✓ Accepted</div>
                                            @elseif($consultation && $consultation->status === 'rejected')
                                                 <div style="margin-top: 0.25rem; font-size: 0.75rem; color: #b91c1c; font-weight: 600;">✕ Rejected</div>
                                            @endif
                                        @else
                                            <a href="{{ route('notifications.read', $notification->id) }}" style="display: block; margin-top: 0.25rem; font-size: 0.75rem; color: var(--accent-color); text-decoration: none;">View Details</a>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div style="padding: 1.5rem; text-align: center; color: var(--text-secondary); font-size: 0.9rem;">
                                    No new notifications
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="user-profile-top" style="display: flex; align-items: center; gap: 0.75rem; border-left: 1px solid rgba(255,255,255,0.2); padding-left: 1.5rem;">
                    @if(Auth::user()->profile_photo_path)
                        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Avatar" style="width: 36px; height: 36px; border-radius: 10px; object-fit: cover; border: 2px solid var(--accent-color);">
                    @else
                        <div style="width: 36px; height: 36px; background: var(--accent-color); color: var(--primary-color); border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 700;">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div style="display: flex; flex-direction: column;">
                        <span style="font-size: 0.9rem; font-weight: 600; color: white;">{{ Auth::user()->name }}</span>
                    </div>
                </div>
                
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn" title="Logout">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        <!-- Sidebar Overlay -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="sidebar" id="appSidebar">
            <nav class="nav-links">
                <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('profile.show') }}" class="nav-item {{ request()->routeIs('profile.show') ? 'active' : '' }}">
                    Profile Management
                </a>
                @if(Auth::user()->role === 'student')
                <a href="{{ route('notes.index') }}" class="nav-item {{ request()->routeIs('notes.*') ? 'active' : '' }}">
                   My Notes
                </a>
                @endif
                <!-- Future items will go here -->
                <a href="#" class="nav-item">Class Routine</a>
                @if(Auth::user()->role === 'student')
                <a href="{{ route('deadlines.index') }}" class="nav-item {{ request()->routeIs('deadlines.*') ? 'active' : '' }}">Deadline Tracker</a>
                @endif
                <a href="{{ route('todo.index') }}" class="nav-item {{ request()->routeIs('todo.*') ? 'active-yellow' : '' }}">
                Your To Do List
                </a>

                @if(Auth::user()->role === 'student')
                <a href="{{ route('consultations.index') }}" class="nav-item {{ request()->routeIs('consultations.*') ? 'active-yellow' : '' }}">
                    Consultation Scheduler
                </a>
                @endif
                <a href="{{ route('notifications.index') }}" class="nav-item {{ request()->routeIs('notifications.*') ? 'active-yellow' : '' }}">Smart Notifications</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('appSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }

            toggleBtn.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);
        });

        function toggleNotifications() {
            const dropdown = document.getElementById('notificationDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }
    </script>
</body>
</html>
