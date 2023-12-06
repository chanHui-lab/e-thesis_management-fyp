<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = ['comment_text', 'commentable_id', 'commentable_type', 'student_id', 'lecturer_id'];

    public function commentable()
    {
        return $this->morphTo();
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id')->withDefault();
    }

    public function lecturer()
    {
        return $this->belongsTo(Supervisor::class, 'lecturer_id')->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'student_id')->withDefault();
    }

}
