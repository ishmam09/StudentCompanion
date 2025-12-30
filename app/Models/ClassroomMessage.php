<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassroomMessage extends Model
{
    protected $fillable = ['virtual_classroom_id', 'user_id', 'message'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
