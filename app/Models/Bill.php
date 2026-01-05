<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'amount',
        'due_date',
        'frequency',
        'is_automatic',
        'reminder_enabled',
        'reminder_days_before',
        'status'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'is_automatic' => 'boolean',
        'reminder_enabled' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('due_date', '>', now())
                    ->where('status', 'pending')
                    ->orderBy('due_date');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('status', 'pending');
    }
}
