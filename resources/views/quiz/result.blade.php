@extends('layout.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto; padding-top: 2rem;">
    <!-- Score Card -->
    <div class="card" style="text-align: center; padding: 3rem; margin-bottom: 3rem; background: linear-gradient(135deg, white, #F0F9FF); border: 2px solid var(--accent-color);">
        <h1 style="font-family: var(--font-heading); font-size: 2rem; color: var(--primary-color); margin-bottom: 0.5rem;">Quiz Results</h1>
        <div style="font-size: 4rem; font-weight: 800; color: var(--accent-color); line-height: 1; margin: 1.5rem 0;">
            {{ $quiz->score }} <span style="font-size: 2rem; color: var(--text-secondary); font-weight: 600;">/ {{ $quiz->total_questions }}</span>
        </div>
        <p style="font-size: 1.1rem; color: var(--text-secondary);">
            @if($quiz->score >= 16)
                Outstanding job! You've mastered this material. 🌟
            @elseif($quiz->score >= 10)
                Good effort! Review the notes to improve your score. 📚
            @else
                Keep studying! You'll get it next time. 💪
            @endif
        </p>
        <div style="margin-top: 2rem;">
            <a href="{{ route('notes.index') }}" class="btn btn-primary">Back to Notes</a>
        </div>
    </div>

    <!-- Answer Key -->
    <h2 style="font-family: var(--font-heading); font-size: 1.5rem; color: var(--primary-color); margin-bottom: 1.5rem;">Answer Key</h2>
    <div style="display: flex; flex-direction: column; gap: 1.5rem; margin-bottom: 4rem;">
        @foreach($quiz->questions as $index => $q)
            <div class="card" style="padding: 1.5rem;">
                <h3 style="font-size: 1.1rem; color: var(--primary-color); margin-bottom: 1rem;">
                    <span style="color: var(--accent-color); font-weight: 700; margin-right: 0.5rem;">{{ $index + 1 }}.</span>
                    {{ $q['question'] }}
                </h3>
                
                <div style="display: flex; flex-direction: column; gap: 0.5rem; margin-top: 1rem;">
                    @php
                        $userAnswer = $quiz->user_answers[$index] ?? null;
                        $correctAnswer = $q['answer'];
                        $isCorrect = $userAnswer === $correctAnswer;
                    @endphp

                    @foreach($q['options'] as $option)
                        @php
                            $style = "padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid var(--border-color); color: var(--text-primary);";
                            
                            // Styling Logic
                            if ($option === $correctAnswer) {
                                $style = "padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid #10B981; background: #ECFDF5; color: #047857; font-weight: 600;";
                            } elseif ($option === $userAnswer && !$isCorrect) {
                                $style = "padding: 0.75rem; border-radius: var(--radius-md); border: 1px solid #EF4444; background: #FEF2F2; color: #991B1B; font-weight: 500;";
                            }
                        @endphp

                        <div style="{{ $style }}">
                            <div style="display: flex; align-items: center; justify-content: space-between;">
                                <span>{{ $option }}</span>
                                @if($option === $correctAnswer)
                                    <span style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: #059669;">Correct Answer</span>
                                @elseif($option === $userAnswer)
                                    <span style="font-size: 0.8rem; text-transform: uppercase; font-weight: 700; color: #DC2626;">Your Answer</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
