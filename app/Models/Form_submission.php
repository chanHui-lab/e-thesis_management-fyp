<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form_submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id',
        'submission_post_id',
        'form_title',
        'form_description',
        // 'form_date',
        'student_id',
        'supervisor_id',
        'form_files',
        'form_year',
        'form_versionNumber',
    ];

    // FormSubmission.php

    public function submissionPost()
    {
        return $this->belongsTo(SubmissionPost::class, 'submission_post_id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'stu_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(Supervisor::class, 'supervisor_id', 'lecturer_id');
    }
}
