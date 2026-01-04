<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'user_id',
        'note_id',
        'questions',
        'user_answers',
        'score',
        'total_questions',
    ];

    protected $casts = [
        'questions' => 'array',
        'user_answers' => 'array',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
