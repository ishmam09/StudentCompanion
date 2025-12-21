<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courses extends Model
{
    use HasFactory;

    protected $table = 'courses';

    protected $fillable = [
        'id',
        'code',
        'title',
        'credit',
        'created_at',
        'updated_at',
        'grade',
    ];
}

