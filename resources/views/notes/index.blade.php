@extends('layout.app')

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
    <div>
        <h1 style="font-size: 2rem; font-weight: 700; color: var(--primary-color);">My Notes</h1>
        
        <!-- Breadcrumbs -->
        <nav style="margin-top: 0.5rem; color: var(--text-secondary); font-size: 0.9rem;">
            <a href="{{ route('notes.index') }}" style="color: var(--accent-color); text-decoration: none;">Home</a>
            @foreach($breadcrumbs as $crumb)
                <span style="margin: 0 0.5rem;">/</span>
                @if($loop->last)
                    <span>{{ $crumb->name }}</span>
                @else
                    <a href="{{ route('notes.index', $crumb->id) }}" style="color: var(--accent-color); text-decoration: none;">{{ $crumb->name }}</a>
                @endif
            @endforeach
        </nav>
    </div>

    <div style="display: flex; gap: 1rem;">
        <button onclick="document.getElementById('newFolderModal').style.display='flex'" class="btn" style="background: white; border: 1px solid var(--border-color); color: var(--primary-color);">
            + New Folder
        </button>
        <button onclick="document.getElementById('uploadFileModal').style.display='flex'" class="btn btn-primary">
            Upload File
        </button>
    </div>
</div>

@if(session('success'))
    <div style="background-color: #ECFDF5; color: #065f46; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid #A7F3D0;">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div style="background-color: #FEF2F2; color: #991B1B; padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1.5rem; border: 1px solid #FECACA;">
        {{ session('error') }}
    </div>
@endif

<!-- Shared with Me (Only at root) -->
@if(!$currentFolder && empty($breadcrumbs))
    <h3 style="font-size: 1.25rem; margin-bottom: 1rem; color: var(--text-primary);">Inbox</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        <a href="{{ route('notes.shared.index') }}" class="card" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; text-align: center; transition: transform 0.2s; border: 2px solid var(--accent-color); background: rgba(212, 175, 55, 0.05);">
            <div style="font-size: 3rem; margin-bottom: 0.5rem;">📥</div>
            <div style="font-weight: 600; color: var(--primary-color);">Shared with Me</div>
            <div style="font-size: 0.8rem; color: var(--text-secondary);">Inbox</div>
        </a>
    </div>
@endif

<!-- Folders Grid -->
@if($folders->count() > 0)
    <h3 style="font-size: 1.25rem; margin-bottom: 1rem; color: var(--text-primary);">Folders</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
        @foreach($folders as $folder)
            <div style="position: relative;">
                <a href="{{ route('notes.index', $folder->id) }}" class="card" style="padding: 1.5rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; text-align: center; transition: transform 0.2s; height: 100%;">
                    <div style="font-size: 3rem; margin-bottom: 0.5rem;">📁</div>
                    <div style="font-weight: 600; color: var(--primary-color);">{{ $folder->name }}</div>
                    <div style="font-size: 0.8rem; color: var(--text-secondary);">{{ $folder->notes->count() + $folder->children->count() }} items</div>
                </a>
                <form action="{{ route('notes.destroy-folder', $folder->id) }}" method="POST" onsubmit="return confirm('Delete folder \'{{ $folder->name }}\'? All contents will be deleted.');" style="position: absolute; top: 10px; right: 10px;">
                    @csrf @method('DELETE')
                    <button type="submit" style="background: none; border: none; cursor: pointer; color: #ef4444; font-size: 1.2rem; opacity: 0.7;" title="Delete Folder">✖️</button>
                </form>
            </div>
        @endforeach
    </div>
@endif

<!-- Files List -->
<h3 style="font-size: 1.25rem; margin-bottom: 1rem; color: var(--text-primary);">Files</h3>
@if($notes->count() > 0)
    <div class="card" style="padding: 0; overflow: hidden;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
                <tr>
                    <th style="text-align: left; padding: 1rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 600;">Name</th>
                    <th style="padding: 1rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 600;">Type</th>
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
                        <td style="padding: 1rem; text-align: right;">
                            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; align-items: center;">
                                <button onclick="openShareModal('{{ $note->id }}')" class="btn" style="height: 48px; width: 48px; padding: 0; display: flex; align-items: center; justify-content: center; background: #f0fdf4; color: #15803d; border-radius: 10px;" title="Share">
                                    <span style="font-size: 1.5rem;">📤</span>
                                </button>
                                <a href="#" class="btn" style="height: 48px; padding: 0 1.25rem; display: flex; align-items: center; background: #e0f2fe; color: #0284c7; border-radius: 10px; text-decoration: none; font-size: 0.95rem; font-weight: 600;">Generate Quiz ✨</a>
                                <a href="{{ route('notes.download', $note->id) }}" class="btn" style="height: 48px; width: 48px; padding: 0; display: flex; align-items: center; justify-content: center; background: #f1f5f9; color: var(--primary-color); border-radius: 10px; text-decoration: none;" title="Download">
                                    <span style="font-size: 1.5rem;">⬇️</span>
                                </a>
                                <form action="{{ route('notes.destroy-note', $note->id) }}" method="POST" onsubmit="return confirm('Delete this file?');" style="display: inline;">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn" style="height: 48px; width: 48px; padding: 0; display: flex; align-items: center; justify-content: center; background: #fee2e2; color: #991b1b; border-radius: 10px;" title="Delete">
                                        <span style="font-size: 1.5rem;">🗑️</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <div style="text-align: center; padding: 3rem; border: 2px dashed var(--border-color); border-radius: var(--radius-md); color: var(--text-secondary);">
        <div style="font-size: 2rem; margin-bottom: 1rem;">📝</div>
        <p>No files in this folder.</p>
    </div>
@endif

<!-- New Folder Modal -->
<div id="newFolderModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2rem;">
        <h3 style="margin-top: 0; margin-bottom: 1.5rem;">New Folder</h3>
        <form action="{{ route('notes.store-folder') }}" method="POST">
            @csrf
            <input type="hidden" name="parent_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
            <div class="form-group">
                <label class="form-label">Folder Name</label>
                <input type="text" name="name" class="form-input" required placeholder="e.g. CSE470">
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" onclick="document.getElementById('newFolderModal').style.display='none'" class="btn" style="background: transparent; border: 1px solid var(--border-color);">Cancel</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
    </div>
</div>

<!-- Upload File Modal -->
<div id="uploadFileModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2rem;">
        <h3 style="margin-top: 0; margin-bottom: 1.5rem;">Upload File</h3>
        <form action="{{ route('notes.store-note') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="folder_id" value="{{ $currentFolder ? $currentFolder->id : '' }}">
            <div class="form-group">
                <label class="form-label">Select File</label>
                <input type="file" name="file" class="form-input" required>
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" onclick="document.getElementById('uploadFileModal').style.display='none'" class="btn" style="background: transparent; border: 1px solid var(--border-color);">Cancel</button>
                <button type="submit" class="btn btn-primary">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Share Modal -->
<div id="shareModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 50; align-items: center; justify-content: center;">
    <div class="card" style="width: 100%; max-width: 400px; padding: 2rem;">
        <h3 style="margin-top: 0; margin-bottom: 1.5rem;">Share Note</h3>
        <form id="shareForm" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Recipient Username</label>
                <input type="text" name="username" class="form-input" required placeholder="Enter username">
            </div>
            <div style="display: flex; gap: 1rem; justify-content: flex-end; margin-top: 1.5rem;">
                <button type="button" onclick="document.getElementById('shareModal').style.display='none'" class="btn" style="background: transparent; border: 1px solid var(--border-color);">Cancel</button>
                <button type="submit" class="btn btn-primary">Share</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openShareModal(noteId) {
        const form = document.getElementById('shareForm');
        form.action = `/notes/share/${noteId}`;
        document.getElementById('shareModal').style.display = 'flex';
    }
</script>
@endsection
