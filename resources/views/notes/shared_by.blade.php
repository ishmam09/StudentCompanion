@extends('layout.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; color: var(--primary-color);">Notes from {{ $sender->name }}</h1>
    <nav style="margin-top: 0.5rem; color: var(--text-secondary); font-size: 0.9rem;">
        <a href="{{ route('notes.index') }}" style="color: var(--accent-color); text-decoration: none;">My Notes</a>
        <span style="margin: 0 0.5rem;">/</span>
        <a href="{{ route('notes.shared.index') }}" style="color: var(--accent-color); text-decoration: none;">Inbox</a>
        <span style="margin: 0 0.5rem;">/</span>
        <span>{{ $sender->name }}</span>
    </nav>
</div>

@if($notes->count() > 0)
    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
                <tr>
                    <th style="text-align: left; padding: 1rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 600;">Name</th>
                    <th style="padding: 1rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 600;">Type</th>
                    <th style="padding: 1rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 600;">Shared At</th>
                    <th style="text-align: right; padding: 1rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 600;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notes as $note)
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 1rem; display: flex; align-items: center; gap: 0.75rem;">
                            <span style="font-size: 1.25rem;">📄</span>
                            <span style="font-weight: 500; color: var(--primary-color);">{{ $note->title }}</span>
                        </td>
                        <td style="padding: 1rem; color: var(--text-secondary); text-transform: uppercase; font-size: 0.85rem;">{{ $note->file_type ?? 'FILE' }}</td>
                        <td style="padding: 1rem; color: var(--text-secondary); font-size: 0.85rem;">{{ $note->created_at->format('M d, Y') }}</td>
                        <td style="padding: 1rem; text-align: right;">
                             <a href="{{ route('notes.download', $note->id) }}" class="btn" style="padding: 0.5rem; background: #f1f5f9; color: var(--primary-color);" title="Download">⬇️</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="card" style="padding: 2rem; text-align: center; color: var(--text-secondary);">
        No notes available.
    </div>
@endif
@endsection
