@extends('layout.app')

@section('content')
    <div style="max-width: 1000px; margin: 0 auto;">
        <!-- Header -->
        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 3rem;">
            <div>
                <h1 style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--primary-color); margin-bottom: 0.5rem;">Deadline Tracker</h1>
                <p style="color: var(--text-secondary); font-size: 1.1rem; max-width: 600px;">
                    Stay ahead of your schedule. tracked assignments and persistent notifications.
                </p>
            </div>
            <a href="{{ route('deadlines.create') }}" class="btn btn-primary" style="width: auto; padding-left: 1.5rem; padding-right: 1.5rem; display: flex; gap: 0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                New Task
            </a>
        </div>

        @if(session('success'))
            <div style="background: #ECFDF5; border-left: 4px solid #10B981; color: #047857; padding: 1rem; border-radius: 4px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"></polyline></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Filters (Pill Style) -->
        <div style="margin-bottom: 2rem; display: flex; gap: 0.5rem; background: white; padding: 0.5rem; border-radius: 99px; width: fit-content; box-shadow: var(--shadow-soft);">
            <a href="{{ route('deadlines.index') }}" 
               class="filter-pill {{ !request('filter') ? 'active' : '' }}">
               Pending Tasks
            </a>
            <a href="{{ route('deadlines.index', ['filter' => 'completed']) }}" 
               class="filter-pill {{ request('filter') === 'completed' ? 'active' : '' }}">
               Completed History
            </a>
        </div>

        <!-- Deadlines List -->
        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @forelse($deadlines as $deadline)
                <div class="card deadline-card" id="deadline-{{ $deadline->id }}">
                    <!-- Status Toggle (Left) -->
                    <div style="padding-right: 1.5rem; border-right: 1px solid var(--border-color);">
                        <form method="POST" action="{{ route('deadlines.toggle', $deadline->id) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="status-toggle {{ $deadline->status }}" title="Mark as {{ $deadline->status === 'completed' ? 'Pending' : 'Completed' }}">
                                @if($deadline->status === 'completed')
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                                @endif
                            </button>
                        </form>
                    </div>

                    <!-- Details (Middle) -->
                    <div style="flex: 1; padding: 0 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 0.25rem;">
                            <h3 style="font-size: 1.1rem; font-weight: 600; color: var(--primary-color); margin: 0; 
                                       text-decoration: {{ $deadline->status === 'completed' ? 'line-through' : 'none' }};
                                       color: {{ $deadline->status === 'completed' ? 'var(--text-secondary)' : 'var(--primary-color)' }};">
                                {{ $deadline->title }}
                            </h3>
                            <!-- Priority Badge -->
                            <span class="badge badge-{{ $deadline->priority }}">
                                {{ $deadline->priority }}
                            </span>
                        </div>
                        
                        <div style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 0.5rem; line-height: 1.4;">
                            {{ Str::limit($deadline->description, 120) ?: 'No additional details provided.' }}
                        </div>

                        <div style="display: flex; align-items: center; gap: 1.5rem; font-size: 0.85rem; color: var(--text-secondary);">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                {{ $deadline->due_date->format('M d, Y h:i A') }}
                            </div>
                            @if($deadline->status === 'pending')
                                <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 600; 
                                            color: {{ $deadline->due_date->isPast() ? '#EF4444' : ($deadline->due_date->diffInHours() < 24 ? '#F59E0B' : 'var(--accent-color)') }}">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    {{ $deadline->due_date->diffForHumans() }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions (Right) -->
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <a href="{{ route('deadlines.edit', $deadline->id) }}" class="btn-icon" title="Edit">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                        </a>
                        <form method="POST" action="{{ route('deadlines.destroy', $deadline->id) }}" onsubmit="return confirm('Are you sure you want to delete this deadline?');" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon danger" title="Delete">
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div style="text-align: center; padding: 4rem 2rem; background: white; border-radius: var(--radius-lg); border: 1px dashed var(--border-color);">
                    <div style="background: var(--background); width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; font-size: 2rem;">
                        ✨
                    </div>
                    <h3 style="font-family: var(--font-heading); font-size: 1.5rem; color: var(--primary-color); margin-bottom: 0.5rem;">All caught up!</h3>
                    <p style="color: var(--text-secondary); margin-bottom: 2rem;">You have no pending tasks. Enjoy your free time or manage your history.</p>
                    <a href="{{ route('deadlines.create') }}" class="btn btn-primary" style="padding-left: 2rem; padding-right: 2rem;">Create New Task</a>
                </div>
            @endforelse
        </div>
    </div>

    <style>
        .filter-pill {
            padding: 0.5rem 1.5rem;
            border-radius: 99px;
            font-weight: 600;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }
        .filter-pill:hover {
            color: var(--primary-color);
            background: var(--background);
        }
        .filter-pill.active {
            background: var(--primary-color);
            color: var(--accent-color);
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.1);
        }

        .deadline-card {
            display: flex;
            align-items: center;
            padding: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .deadline-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-card);
            border-color: rgba(212, 175, 55, 0.2); /* Accent border on hover */
        }

        .status-toggle {
            width: 26px;
            height: 26px;
            border-radius: 50%;
            border: 2px solid var(--border-color);
            background: transparent;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            padding: 0;
        }
        .status-toggle:hover {
            border-color: var(--accent-color);
            transform: scale(1.1);
        }
        .status-toggle.completed {
            background: #10B981;
            border-color: #10B981;
        }

        .badge {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            padding: 0.2rem 0.6rem;
            border-radius: 6px;
            letter-spacing: 0.05em;
        }
        .badge-low { background: #ECFDF5; color: #047857; }
        .badge-medium { background: #FFFBEB; color: #B45309; }
        .badge-high { background: #FEF2F2; color: #B91C1C; }

        .btn-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            color: var(--text-secondary);
            transition: all 0.2s;
            background: transparent;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-icon:hover {
            background: var(--background);
            color: var(--primary-color);
        }
        .btn-icon.danger:hover {
            background: #FEF2F2;
            color: #EF4444;
        }
    </style>
@endsection
