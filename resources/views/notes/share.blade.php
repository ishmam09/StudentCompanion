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