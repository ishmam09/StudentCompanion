@extends('layout.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding-top: 2rem;">
    <!-- Header -->
    <div style="margin-bottom: 2rem; text-align: center;">
        <h1 style="font-family: var(--font-heading); font-size: 2rem; color: var(--primary-color); margin-bottom: 0.5rem;">AI Quiz: {{ $quiz->note->title }}</h1>
        <p style="color: var(--text-secondary);">Answer the following 20 questions based on your note.</p>
    </div>

    <!-- Quiz Form -->
    <form action="{{ route('quiz.submit', $quiz->id) }}" method="POST">
        @csrf
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            @foreach($quiz->questions as $index => $q)
                <div class="card" style="padding: 1.5rem;">
                    <h3 style="font-size: 1.1rem; color: var(--primary-color); margin-bottom: 1rem;">
                        <span style="color: var(--accent-color); font-weight: 700; margin-right: 0.5rem;">{{ $index + 1 }}.</span>
                        {{ $q['question'] }}
                    </h3>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($q['options'] as $option)
                            <label style="display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem; border: 1px solid var(--border-color); border-radius: var(--radius-md); cursor: pointer; transition: all 0.2s;" onmouseover="this.style.background='var(--background)'" onmouseout="this.style.background='transparent'">
                                <input type="radio" name="answers[{{ $index }}]" value="{{ $option }}" required style="width: 1.2rem; height: 1.2rem; accent-color: var(--accent-color);">
                                <span style="font-size: 0.95rem; color: var(--text-primary);">{{ $option }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 3rem; text-align: center; margin-bottom: 4rem;">
            <button type="submit" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.1rem;">Submit Quiz</button>
        </div>
    </form>
</div>
@endsection
