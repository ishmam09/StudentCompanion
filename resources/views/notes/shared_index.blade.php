@extends('layout.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; color: var(--primary-color);">Shared with Me</h1>
    <nav style="color: var(--text-secondary); font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
        <a href="{{ route('notes.index') }}" style="color: var(--accent-color); text-decoration: none; display: flex; align-items: center; gap: 0.25rem; font-weight: 500;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
            My Notes
        </a>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
        <span style="font-weight: 600; color: var(--primary-color);">Inbox</span>
    </nav>
</div>

@if($senders->count() > 0)
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem;">
        @foreach($senders as $sender)
            <a href="{{ route('notes.shared.by', $sender->id) }}" class="card" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; text-align: center; transition: transform 0.2s;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: var(--accent-color); color: var(--primary-color); display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                    {{ substr($sender->name, 0, 1) }}
                </div>
                <div style="font-weight: 600; color: var(--primary-color);">{{ $sender->name }}</div>
            </a>
        @endforeach
    </div>
@else
    <div style="text-align: center; padding: 3rem; border: 2px dashed var(--border-color); border-radius: var(--radius-md); color: var(--text-secondary);">
        <p>No one has shared any notes with you yet.</p>
    </div>
@endif
@endsection
