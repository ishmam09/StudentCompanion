@extends('layout.app')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('notes.index', $folderId) }}"
                style="color: var(--text-secondary); text-decoration: none; display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                ← Back to Notes
            </a>
            <h1>Upload File</h1>
        </div>

        <div class="card">
            <form action="{{ route('notes.store-note') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="folder_id" value="{{ $folderId }}">

                <div class="form-group">
                    <label class="form-label">Select File</label>
                    <input type="file" name="file" class="form-input" required>
                    <p style="font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.5rem;">Supported formats: PDF,
                        DOCX, TXT, and more</p>
                </div>

                <div style="margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Upload</button>
                </div>
            </form>
        </div>
    </div>
@endsection