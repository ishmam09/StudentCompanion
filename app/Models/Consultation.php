<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Consultation extends Model
{
    protected $fillable = ['student_id', 'teacher_name', 'teacher_email', 'appointment_date', 'topic', 'status'];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}