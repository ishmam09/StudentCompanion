<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VirtualClassroom extends Model
{
    protected $fillable = [
        'course_code',
        'teacher_id',
        'classroom_name',
        'class_code',
        'room_id',
        'is_active',
    ];

    public function teacher() {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students() {
        return $this->belongsToMany(User::class, 'classroom_user', 'virtual_classroom_id', 'user_id');
    }
}
