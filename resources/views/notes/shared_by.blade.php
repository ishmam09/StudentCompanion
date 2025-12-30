@extends('layout.app')

@section('content')
<div style="margin-bottom: 2rem;">
    <h1 style="font-size: 2rem; font-weight: 700; color: var(--primary-color);">Notes from {{ $sender->name }}</h1>
    <nav style="color: var(--text-secondary); font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
        <a href="{{ route('notes.index') }}" style="color: var(--accent-color); text-decoration: none; display: flex; align-items: center; gap: 0.25rem; font-weight: 500;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path></svg>
            My Notes
        </a>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
        <a href="{{ route('notes.shared.index') }}" style="color: var(--accent-color); text-decoration: none; font-weight: 500;">Inbox</a>
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="9 18 15 12 9 6"></polyline></svg>
        <span style="font-weight: 600; color: var(--primary-color);">{{ $sender->name }}</span>
    </nav>
</div>

@if($notes->count() > 0)
    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: var(--background); border-bottom: 2px solid var(--border-color);">
                <tr>
                    <th style="text-align: left; padding: 1.25rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Name</th>
                    <th style="padding: 1.25rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Type</th>
                    <th style="padding: 1.25rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Shared At</th>
                    <th style="text-align: right; padding: 1.25rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notes as $note)
                    <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.2s;" onmouseover="this.style.background='var(--background)'" onmouseout="this.style.background='transparent'">
                        <td style="padding: 1.25rem;">
                            <div style="display: flex; align-items: center; gap: 1rem;">
                                <div style="width: 40px; height: 40px; background: #F0FDF4; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#15803D" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                                </div>
                                <span style="font-weight: 600; color: var(--primary-color); font-size: 0.95rem;">{{ $note->title }}</span>
                            </div>
                        </td>
                        <td style="padding: 1.25rem; text-align: center;">
                             <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; padding: 0.25rem 0.75rem; border-radius: 6px; background: var(--background); color: var(--text-secondary); letter-spacing: 0.05em;">{{ $note->file_type ?? 'FILE' }}</span>
                        </td>
                        <td style="padding: 1.25rem; color: var(--text-secondary); font-size: 0.85rem;">{{ $note->created_at->format('M d, Y') }}</td>
                        <td style="padding: 1.25rem;">
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                                 <a href="{{ route('notes.download', $note->id) }}" class="btn-icon" style="background: var(--background); color: var(--primary-color);" title="Download">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
                                 </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <style>
        .btn-icon {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            transition: all 0.2s;
            background: transparent;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }
        .btn-icon:hover {
            transform: scale(1.1);
            background: #e2e8f0; 
        }
    </style>
@else
    <div class="card" style="padding: 2rem; text-align: center; color: var(--text-secondary);">
        No notes available.
    </div>
@endif
@endsection
