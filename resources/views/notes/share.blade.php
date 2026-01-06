@extends('layout.app')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('notes.index', $note->folder_id) }}"
                style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                ← Back to Notes
            </a>
            <h1>Share Note: {{ $note->title }}</h1>
        </div>

        @if(session('success'))
            <div
                style="background: #ECFDF5; border-left: 4px solid #10B981; color: #047857; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12"></polyline>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div
                style="background: #FEF2F2; border-left: 4px solid #EF4444; color: #991B1B; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="15" y1="9" x2="9" y2="15"></line>
                    <line x1="9" y1="9" x2="15" y2="15"></line>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="card">
            <form action="{{ route('notes.share', $note->id) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label">Recipient Username</label>
                    <input type="text" name="username" class="form-input" required placeholder="Enter username">
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.5rem;">The recipient will
                        receive this file in their "Shared with Me" inbox</p>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Share Note</button>
                </div>
            </form>
        </div>
    </div>
@endsection