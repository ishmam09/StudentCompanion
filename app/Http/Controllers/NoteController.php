<?php

namespace App\Http\Controllers;

use App\Models\Folder;
use App\Models\Note;
use App\Models\NoteShare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NoteController extends Controller
{
    public function index($folderId = null)
    {
        $user = Auth::user();
        
        $currentFolder = null;
        if ($folderId) {
            $currentFolder = Folder::where('id', $folderId)->where('user_id', $user->id)->firstOrFail();
            $folders = $currentFolder->children()->orderBy('created_at', 'desc')->get();
            $notes = $currentFolder->notes()->orderBy('created_at', 'desc')->get();
        } else {
            $folders = Folder::where('user_id', $user->id)->whereNull('parent_id')->orderBy('created_at', 'desc')->get();
            $notes = Note::where('user_id', $user->id)->whereNull('folder_id')->orderBy('created_at', 'desc')->get();
        }

        // Breadcrumbs logic
        $breadcrumbs = [];
        if ($currentFolder) {
            $temp = $currentFolder;
            while ($temp) {
                array_unshift($breadcrumbs, $temp);
                $temp = $temp->parent;
            }
        }

        return view('notes.index', compact('folders', 'notes', 'currentFolder', 'breadcrumbs'));
    }

    public function storeFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:folders,id'
        ]);

        Folder::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Folder created successfully.');
    }

    public function storeNote(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'folder_id' => 'nullable|exists:folders,id'
        ]);

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        
        // Store in nice structure: notes/user_id/folder_id/filename
        $folderPath = 'notes/' . Auth::id();
        if ($request->folder_id) {
            $folderPath .= '/' . $request->folder_id;
        }
        
        $path = $file->storeAs($folderPath, $originalName . '_' . time() . '.' . $extension, 'public');

        Note::create([
            'user_id' => Auth::id(),
            'folder_id' => $request->folder_id,
            'title' => $originalName,
            'file_path' => $path,
            'file_type' => $extension,
        ]);

        return back()->with('success', 'File uploaded successfully.');
    }

    public function download(Note $note)
    {
        // Check if owner
        if ($note->user_id === Auth::id()) {
            return Storage::disk('public')->download($note->file_path, $note->title);
        }

        // Check if shared with me
        $isShared = NoteShare::where('note_id', $note->id)
                             ->where('to_user_id', Auth::id())
                             ->exists();

        if ($isShared) {
            return Storage::disk('public')->download($note->file_path, $note->title);
        }

        abort(403);
    }
    
    public function destroyFolder(Folder $folder)
    {
         if ($folder->user_id !== Auth::id()) {
            abort(403);
        }
        // Logic to delete files physically would go here, omitting for brevity/mvp (files stay orphaned in storage but DB cleaned via cascade)
        $folder->delete();
        return back()->with('success', 'Folder deleted.');
    }

    public function destroyNote(Note $note)
    {
         if ($note->user_id !== Auth::id()) {
            abort(403);
        }
        Storage::disk('public')->delete($note->file_path);
        $note->delete();
        return back()->with('success', 'Note deleted.');
    }

    public function shareNote(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['username' => 'required']);

        $recipient = User::where('username', $request->username)->first();
        
        if (!$recipient) {
            return back()->with('error', 'User with username "' . $request->username . '" does not exist.');
        }

        if ($recipient->id === Auth::id()) {
             return back()->with('error', 'You cannot share notes with yourself.');
        }

        // Check if already shared
        $exists = NoteShare::where('note_id', $note->id)
                           ->where('to_user_id', $recipient->id)
                           ->exists();
        
        if ($exists) {
            return back()->with('error', 'Already shared with this user.');
        }

        NoteShare::create([
            'note_id' => $note->id,
            'from_user_id' => Auth::id(),
            'to_user_id' => $recipient->id,
        ]);

        $recipient->notify(new \App\Notifications\NoteShared(Auth::user(), $note));

        return back()->with('success', 'Note shared with ' . $recipient->name);
    }

    public function sharedIndex()
    {
        // Get all unique users who have shared notes with me
        $senderIds = NoteShare::where('to_user_id', Auth::id())
                              ->distinct()
                              ->pluck('from_user_id');
        
        $senders = User::whereIn('id', $senderIds)->get();

        return view('notes.shared_index', compact('senders'));
    }

    public function sharedBy($userId)
    {
        $sender = User::findOrFail($userId);
        
        // Get notes shared by this user to me
        $shares = NoteShare::where('from_user_id', $userId)
                           ->where('to_user_id', Auth::id())
                           ->with('note')
                           ->get();
        
        $notes = $shares->pluck('note');

        return view('notes.shared_by', compact('sender', 'notes'));
    }
}
