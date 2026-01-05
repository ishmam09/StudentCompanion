@extends('layout.app')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('notes.index', $folderId) }}"
                style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                ← Back to Notes
            </a>
            <h1>Create New Folder</h1>
        </div>

        <div class="card">
            <form action="{{ route('notes.store-folder') }}" method="POST">
                @csrf
                <input type="hidden" name="parent_id" value="{{ $folderId }}">

                <div class="form-group">
                    <label class="form-label">Folder Name</label>
                    <input type="text" name="name" class="form-input" required
                        placeholder="e.g. CSE470 - Software Engineering" autofocus>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Create Folder</button>
                </div>
            </form>
        </div>
    </div>
@endsection