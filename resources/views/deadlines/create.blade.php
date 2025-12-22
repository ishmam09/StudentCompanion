@extends('layout.app')

@section('content')
    <div style="max-width: 600px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <a href="{{ route('deadlines.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.9rem;">← Back to Deadlines</a>
            <h1 style="font-size: 1.875rem; font-weight: 700; color: var(--text-primary); margin-top: 0.5rem;">Create New Deadline</h1>
        </div>

        <div class="card" style="padding: 2rem;">
            <form method="POST" action="{{ route('deadlines.store') }}">
                @csrf
                
                <div style="margin-bottom: 1.5rem;">
                    <label for="title" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required 
                        style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px; background: var(--background); color: var(--text-primary);">
                    @error('title')
                        <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-bottom: 1.5rem;">
                    <label for="description" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Description (Optional)</label>
                    <textarea id="description" name="description" rows="3" 
                        style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px; background: var(--background); color: var(--text-primary);">{{ old('description') }}</textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="due_date" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Due Date & Time</label>
                        <input type="datetime-local" id="due_date" name="due_date" value="{{ old('due_date') }}" required 
                            style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px; background: var(--background); color: var(--text-primary);">
                        @error('due_date')
                            <div style="color: #ef4444; font-size: 0.85rem; margin-top: 0.25rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="priority" style="display: block; margin-bottom: 0.5rem; font-weight: 600; color: var(--text-primary);">Priority</label>
                        <select id="priority" name="priority" required 
                            style="width: 100%; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: 8px; background: var(--background); color: var(--text-primary);">
                            <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                    <a href="{{ route('deadlines.index') }}" class="btn" style="padding: 0.75rem 1.5rem; background: var(--background); color: var(--text-primary);">Cancel</a>
                    <button type="submit" class="btn-primary" style="padding: 0.75rem 2rem;">Create Deadline</button>
                </div>
            </form>
        </div>
    </div>
@endsection
