<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Deadline extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'due_date',
        'priority',
        'status',
        'reminder_sent',
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'reminder_sent' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
