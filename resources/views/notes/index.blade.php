@extends('layout.app')

@section('content')
    <div style="max-width: 1200px; margin: 0 auto;">

        <!-- Header -->
        <div style="margin-bottom: 3rem; display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h1
                    style="font-family: var(--font-heading); font-size: 2.5rem; color: var(--primary-color); margin-bottom: 0.5rem;">
                    My Notes</h1>

                <!-- Breadcrumbs -->
                <nav
                    style="color: var(--text-secondary); font-size: 0.95rem; display: flex; align-items: center; gap: 0.5rem;">
                    <a href="{{ route('notes.index') }}"
                        style="color: var(--accent-color); text-decoration: none; display: flex; align-items: center; gap: 0.25rem; font-weight: 500;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        </svg>
                        Home
                    </a>
                    @foreach($breadcrumbs as $crumb)
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="9 18 15 12 9 6"></polyline>
                        </svg>
                        @if($loop->last)
                            <span style="font-weight: 600; color: var(--primary-color);">{{ $crumb->name }}</span>
                        @else
                            <a href="{{ route('notes.index', $crumb->id) }}"
                                style="color: var(--accent-color); text-decoration: none; font-weight: 500;">{{ $crumb->name }}</a>
                        @endif
                    @endforeach
                </nav>
            </div>

            <div style="display: flex; gap: 1rem;">
                <a href="{{ route('notes.create-folder', ['folder_id' => $currentFolder->id ?? null]) }}" class="btn"
                    style="background: white; border: 1px solid var(--border-color); color: var(--primary-color); display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    </svg>
                    New Folder
                </a>
                <a href="{{ route('notes.upload', ['folder_id' => $currentFolder->id ?? null]) }}" class="btn btn-primary"
                    style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" y1="3" x2="12" y2="15"></line>
                    </svg>
                    Upload File
                </a>
            </div>
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

        <!-- Shared with Me (Only at root) -->
        @if(!$currentFolder && empty($breadcrumbs))
            <h3
                style="font-family: var(--font-heading); font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--primary-color);">
                Quick Access</h3>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
                <a href="{{ route('notes.shared.index') }}" class="card folder-card"
                    style="padding: 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; text-align: center; border: 2px solid var(--accent-color); background: linear-gradient(135deg, rgba(212, 175, 55, 0.05), rgba(212, 175, 55, 0.02));">
                    <div
                        style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--accent-color), #FCD34D); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem; box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="var(--primary-color)"
                            stroke-width="2">
                            <path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path>
                            <polyline points="16 6 12 2 8 6"></polyline>
                            <line x1="12" y1="2" x2="12" y2="15"></line>
                        </svg>
                    </div>
                    <div style="font-weight: 700; color: var(--primary-color); font-size: 1.1rem; margin-bottom: 0.25rem;">
                        Shared with Me</div>
                    <div style="font-size: 0.85rem; color: var(--text-secondary);">Inbox</div>
                </a>
            </div>
        @endif

        <!-- Folders Grid -->
        @if($folders->count() > 0)
            <h3
                style="font-family: var(--font-heading); font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--primary-color);">
                Folders</h3>
            <div
                style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 3rem;">
                @foreach($folders as $folder)
                    <div style="position: relative;">
                        <a href="{{ route('notes.index', $folder->id) }}" class="card folder-card"
                            style="padding: 2rem; display: flex; flex-direction: column; align-items: center; justify-content: center; text-decoration: none; text-align: center; height: 100%;">
                            <div
                                style="width: 60px; height: 60px; background: #EFF6FF; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1rem;">
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#1D4ED8" stroke-width="2">
                                    <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                                </svg>
                            </div>
                            <div style="font-weight: 700; color: var(--primary-color); font-size: 1.05rem; margin-bottom: 0.25rem;">
                                {{ $folder->name }}
                            </div>
                            <div style="font-size: 0.85rem; color: var(--text-secondary);">
                                {{ $folder->notes->count() + $folder->children->count() }} items
                            </div>
                        </a>
                        <form action="{{ route('notes.destroy-folder', $folder->id) }}" method="POST"
                            onsubmit="return confirm('Delete folder \'{{ $folder->name }}\'? All contents will be deleted.');"
                            style="position: absolute; top: 12px; right: 12px;">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-icon danger"
                                style="width: 32px; height: 32px; background: white; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                                title="Delete Folder">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Files List -->
        <h3
            style="font-family: var(--font-heading); font-size: 1.25rem; margin-bottom: 1.5rem; color: var(--primary-color);">
            Files</h3>
        @if($notes->count() > 0)
            <div class="card" style="padding: 0; overflow: hidden;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead style="background: var(--background); border-bottom: 2px solid var(--border-color);">
                        <tr>
                            <th
                                style="text-align: left; padding: 1.25rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                                Name</th>
                            <th
                                style="padding: 1.25rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                                Type</th>
                            <th
                                style="text-align: right; padding: 1.25rem; font-size: 0.85rem; color: var(--text-secondary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($notes as $note)
                            <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.2s;"
                                onmouseover="this.style.background='var(--background)'"
                                onmouseout="this.style.background='transparent'">
                                <td style="padding: 1.25rem;">
                                    <div style="display: flex; align-items: center; gap: 1rem;">
                                        <div
                                            style="width: 40px; height: 40px; background: #F0FDF4; border-radius: 10px; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#15803D"
                                                stroke-width="2">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                            </svg>
                                        </div>
                                        <span
                                            style="font-weight: 600; color: var(--primary-color); font-size: 0.95rem;">{{ $note->title }}</span>
                                    </div>
                                </td>
                                <td style="padding: 1.25rem; text-align: center;">
                                    <span
                                        style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; padding: 0.25rem 0.75rem; border-radius: 6px; background: var(--background); color: var(--text-secondary); letter-spacing: 0.05em;">{{ $note->file_type ?? 'FILE' }}</span>
                                </td>
                                <td style="padding: 1.25rem;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-end; align-items: center;">
                                        <a href="{{ route('notes.share-page', $note->id) }}" class="btn-icon"
                                            style="background: #F0FDF4; color: #15803D; text-decoration: none;" title="Share">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <circle cx="18" cy="5" r="3"></circle>
                                                <circle cx="6" cy="12" r="3"></circle>
                                                <circle cx="18" cy="19" r="3"></circle>
                                                <line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line>
                                                <line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line>
                                            </svg>
                                        </a>
                                        <a href="#" class="btn"
                                            style="padding: 0.5rem 1rem; background: linear-gradient(135deg, #E0F2FE, #BAE6FD); color: #0284C7; border: none; font-weight: 600; font-size: 0.85rem; display: flex; align-items: center; gap: 0.5rem; text-decoration: none;">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"></path>
                                                <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                            </svg>
                                            Quiz
                                        </a>
                                        <a href="{{ route('notes.download', $note->id) }}" class="btn-icon"
                                            style="background: var(--background); color: var(--primary-color);" title="Download">
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                stroke-width="2">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="7 10 12 15 17 10"></polyline>
                                                <line x1="12" y1="15" x2="12" y2="3"></line>
                                            </svg>
                                        </a>
                                        <form action="{{ route('notes.destroy-note', $note->id) }}" method="POST"
                                            onsubmit="return confirm('Delete this file?');" style="display: inline;">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-icon danger" title="Delete">
                                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2">
                                                    <polyline points="3 6 5 6 21 6"></polyline>
                                                    <path
                                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                                    </path>
                                                </svg>
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
            <div
                style="text-align: center; padding: 4rem 2rem; border: 2px dashed var(--border-color); border-radius: var(--radius-lg); background: var(--background);">
                <div
                    style="width: 80px; height: 80px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; box-shadow: var(--shadow-soft);">
                    <svg width="36" height="36" viewBox="0 0 24 24" fill="none" stroke="var(--text-secondary)" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                    </svg>
                </div>
                <h3
                    style="font-family: var(--font-heading); font-size: 1.25rem; color: var(--primary-color); margin-bottom: 0.5rem;">
                    No files here yet</h3>
                <p style="color: var(--text-secondary); margin-bottom: 1.5rem;">Upload your first file to get started</p>
                <a href="{{ route('notes.upload', ['folder_id' => $currentFolder->id ?? null]) }}" class="btn btn-primary"
                    style="text-decoration: none;">Upload File</a>
            </div>
        @endif
    </div>

    <style>
        .folder-card {
            transition: all 0.3s ease;
        }

        .folder-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 24px -10px rgba(0, 0, 0, 0.15);
        }

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
        }

        .btn-icon:hover {
            transform: scale(1.1);
        }

        .btn-icon.danger {
            background: #FEE2E2;
            color: #991B1B;
        }

        .btn-icon.danger:hover {
            background: #FEF2F2;
            color: #EF4444;
        }
    </style>
@endsection