@extends('layout.app')

@section('content')
    <div style="max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 2rem;">
            <h1 style="margin-bottom: 0.5rem;">Citation Generator (PHP)</h1>
            <p class="text-muted">Server-side citation generation.</p>
        </div>

        <!-- Main Generator Card -->
        <div class="card" style="margin-bottom: 2rem;">
            <form action="{{ route('citation.index') }}" method="GET" id="configForm">
                <!-- Controls -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Citation Style</label>
                        <select name="style" class="form-input">
                            <option value="apa" {{ $style == 'apa' ? 'selected' : '' }}>APA 7th Edition</option>
                            <option value="mla" {{ $style == 'mla' ? 'selected' : '' }}>MLA 9th Edition</option>
                            <option value="chicago" {{ $style == 'chicago' ? 'selected' : '' }}>Chicago 17th Edition</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Source Type</label>
                        <select name="type" class="form-input">
                            <option value="website" {{ $type == 'website' ? 'selected' : '' }}>Website</option>
                            <option value="book" {{ $type == 'book' ? 'selected' : '' }}>Book</option>
                            <option value="journal" {{ $type == 'journal' ? 'selected' : '' }}>Journal Article</option>
                        </select>
                    </div>
                </div>
                <div style="margin-bottom: 1.5rem;">
                    <button type="submit" class="btn" style="padding: 0.5rem 1rem; font-size: 0.9rem;">Update
                        Configuration</button>
                </div>
            </form>

            <form action="{{ route('citation.index') }}" method="POST">
                @csrf
                <input type="hidden" name="style" value="{{ $style }}">
                <input type="hidden" name="type" value="{{ $type }}">

                <!-- Dynamic Fields (Server Rendered) -->
                <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;">
                    <!-- Common Field: Author -->
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Author (Last, First)</label>
                        <input type="text" name="author" class="form-input" placeholder="e.g. Doe, John"
                            value="{{ old('author') }}">
                    </div>

                    @if($type == 'website')
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Page Title</label>
                            <input type="text" name="title" class="form-input" value="{{ old('title') }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Website Name</label>
                            <input type="text" name="website_name" class="form-input" value="{{ old('website_name') }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Publication Date</label>
                            <input type="date" name="date" class="form-input" value="{{ old('date') }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">URL</label>
                            <input type="text" name="url" class="form-input" placeholder="https://" value="{{ old('url') }}">
                        </div>
                    @endif

                    @if($type == 'book')
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Book Title</label>
                            <input type="text" name="title" class="form-input" value="{{ old('title') }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Publisher</label>
                            <input type="text" name="publisher" class="form-input" value="{{ old('publisher') }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Year Published</label>
                            <input type="text" name="year" class="form-input" placeholder="2023" value="{{ old('year') }}">
                        </div>
                    @endif

                    @if($type == 'journal')
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Article Title</label>
                            <input type="text" name="title" class="form-input" value="{{ old('title') }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Journal Name</label>
                            <input type="text" name="journal_name" class="form-input" value="{{ old('journal_name') }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Volume</label>
                            <input type="text" name="volume" class="form-input" value="{{ old('volume') }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Issue</label>
                            <input type="text" name="issue" class="form-input" value="{{ old('issue') }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Pages</label>
                            <input type="text" name="pages" class="form-input" value="{{ old('pages') }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">Year</label>
                            <input type="text" name="year" class="form-input" value="{{ old('year') }}">
                        </div>
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label">DOI (Optional)</label>
                            <input type="text" name="doi" class="form-input" value="{{ old('doi') }}">
                        </div>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    ✨ Generate Citation
                </button>
            </form>
        </div>

        @if(isset($result))
            <!-- Result Area -->
            <div id="resultArea" class="card" style="border: 2px solid var(--accent-color); margin-bottom: 2rem;">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 1rem;">
                    <label class="form-label" style="color: var(--accent-color);">Generated Citation</label>
                </div>
                <div
                    style="font-family: 'Times New Roman', serif; font-size: 1.1rem; line-height: 1.6; padding: 1rem; background: var(--background); border-radius: var(--radius-sm); border: 1px solid var(--border-color);">
                    {!! $result !!}
                </div>
            </div>
        @endif

        <!-- History -->
        <div>
            <h3 style="margin-bottom: 1rem;">Recent History (Session)</h3>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse($history as $item)
                    <div class="card" style="padding: 1rem;">
                        <div style="display:flex; justify-content:space-between; margin-bottom:0.5rem;">
                            <span
                                style="font-size:0.75rem; font-weight:700; color:var(--accent-color); text-transform:uppercase;">{{ strtoupper($item['style']) }}
                                • {{ ucfirst($item['type']) }}</span>
                            <span class="text-muted" style="font-size: 0.8rem;">{{ $item['timestamp']->diffForHumans() }}</span>
                        </div>
                        <div style="font-family: 'Times New Roman', serif; font-size: 1rem;">{!! $item['citation'] !!}</div>
                    </div>
                @empty
                    <p class="text-muted" style="font-style: italic;">No recent citations this session.</p>
                @endforelse
            </div>
        </div>
    </div>
@endsection