<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassroomPost extends Model
{
    protected $fillable = ['virtual_classroom_id', 'user_id', 'content'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
