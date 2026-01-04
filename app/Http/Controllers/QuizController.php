<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Quiz;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    protected $gemini;

    public function __construct(GeminiService $gemini)
    {
        $this->gemini = $gemini;
    }

    public function generate(Note $note)
    {
        // 1. Authorization Check
        if ($note->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Check if file exists
        if (!Storage::disk('public')->exists($note->file_path)) {
            return back()->with('error', 'File not found on server.');
        }

        try {
            // 3. Prepare file for Gemini
            $fileContent = base64_encode(Storage::disk('public')->get($note->file_path));
            $mimeType = Storage::disk('public')->mimeType($note->file_path);

            // 4. Generate Quiz
            $questions = $this->gemini->generateQuiz($fileContent, $mimeType);

            // 5. Save Quiz
            $quiz = Quiz::create([
                'user_id' => Auth::id(),
                'note_id' => $note->id,
                'questions' => $questions,
                'total_questions' => count($questions)
            ]);

            return redirect()->route('quiz.show', $quiz->id);

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to generate quiz: ' . $e->getMessage());
        }
    }

    public function show(Quiz $quiz)
    {
        if ($quiz->user_id !== Auth::id()) {
            abort(403);
        }

        if ($quiz->score !== null) {
            return redirect()->route('quiz.result', $quiz->id);
        }

        return view('quiz.show', compact('quiz'));
    }

    public function submit(Request $request, Quiz $quiz)
    {
        if ($quiz->user_id !== Auth::id()) {
            abort(403);
        }

        $answers = $request->input('answers', []);
        $score = 0;
        $questions = $quiz->questions;

        // Grade the quiz
        foreach ($questions as $index => $question) {
            $userAnswer = $answers[$index] ?? null;
            if ($userAnswer === $question['answer']) {
                $score++;
            }
        }

        $quiz->update([
            'score' => $score,
            'user_answers' => $answers
        ]);

        return redirect()->route('quiz.result', $quiz->id);
    }

    public function result(Quiz $quiz)
    {
        if ($quiz->user_id !== Auth::id()) {
            abort(403);
        }

        return view('quiz.result', compact('quiz'));
    }
}
