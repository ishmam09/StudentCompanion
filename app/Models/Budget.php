<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Budget extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'period',
        'start_date',
        'end_date',
        'is_active'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getSpentAmountAttribute()
    {
        return $this->category->transactions()
            ->expenses()
            ->whereBetween('transaction_date', [$this->start_date, $this->end_date ?? now()])
            ->sum('amount');
    }

    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->spent_amount;
    }

    public function getPercentageUsedAttribute()
    {
        return ($this->spent_amount / $this->amount) * 100;
    }
}
